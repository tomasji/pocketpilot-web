<?php

declare(strict_types=1);

namespace PP\Controls;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use PP\User\PasswordCredentials;

/**
 * @author Andrej Souček
 */
class LoginForm extends BaseControl
{

    public array $onSuccess = [];

    private Gettext $translator;

    private User $user;

    private string $fbLoginUrl;

    public function __construct(Gettext $translator, User $user, string $fbLoginUrl)
    {
        $this->translator = $translator;
        $this->user = $user;
        $this->fbLoginUrl = $fbLoginUrl;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/loginForm.latte');
        $this->template->setTranslator($this->translator);
        $this->template->fbLoginUrl = $this->getFbLoginUrl();
        $this->template->lang = $this->getLang();
        $this->template->render();
    }

    public function getFbLoginUrl(): string
    {
        return $this->fbLoginUrl;
    }

    public function getLang(): string
    {
        return $this->translator->getLang();
    }

    protected function createComponentForm(): Form
    {
        $form = new Form();
        $form->setTranslator($this->translator);
        $form->addText('email', 'E-mail')
            ->setRequired('Please enter your e-mail.')
            ->addRule($form::EMAIL, 'The e-mail must be in correct format.')
            ->setHtmlAttribute('placeholder', 'E-mail');
        $form->addPassword('password', 'Password')
            ->setRequired('Please enter your password.')
            ->setHtmlAttribute('placeholder', 'Password');
        $form->addSubmit('send', 'Log in');
        $form->onSuccess[] = [$this, 'processForm'];
        return $form;
    }

    /**
     * @internal
     */
    public function processForm(Form $form): void
    {
        $values = $form->getValues();
        try {
            $this->user->login(new PasswordCredentials($values->email, $values->password));
            $this->onSuccess();
        } catch (AuthenticationException $e) {
            $form->addError($this->translator->translate("Incorrect e-mail or password."));
        }
    }
}

interface LoginFormFactory
{
    public function create(string $fbLoginUrl): LoginForm;
}
