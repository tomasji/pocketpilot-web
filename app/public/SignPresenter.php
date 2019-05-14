<?php

declare(strict_types=1);

namespace PP\Presenters;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use PP\DirResolver;
use PP\IncorrectCredentialsException;
use PP\SignModel;
use PP\User\PasswordCredentials;

/**
 * @author Andrej Souček
 */
class SignPresenter extends AppPresenter {

	/**
	 * @var SignModel
	 */
	private $model;

	public function __construct(DirResolver $dirResolver, Gettext $translator, SignModel $model) {
		parent::__construct($dirResolver, $translator);
		$this->model = $model;
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionFbLogin(): void {
		try {
			$this->getUser()->login($this->model->getFacebookCredentials());
		} catch (AuthenticationException $e) {
			$this->flashMessage($this->translator->translate("Error while connecting to Facebook"));
		}
		$this->redirect('Sign:');
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionLogOut(): void {
		$this->getUser()->logout();
		$this->redirect('Sign:');
	}

	public function renderDefault(): void {
		$this->template->currentUserName = $this->getCurrentUserName();
		$this->template->fbLoginUrl = $this->getFbLoginUrl();
	}

	public function getCurrentUserName(): string {
		return $this->getUser()->getIdentity() ? $this->getUser()->getIdentity()->username : "unknown";
	}

	/**
	 * @return string
	 * @throws \Nette\Application\UI\InvalidLinkException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function getFbLoginUrl(): string {
		return $this->model->generateLoginUrl($this->link('//fbLogin'));
	}

	protected function createComponentLoginForm(): Form {
		$form = new Form;
		$form->setTranslator($this->translator);
		$form->addText('email', 'E-mail')
			->setRequired('Please enter your e-mail.')
			->addRule($form::EMAIL, 'The e-mail must be in correct format.')
			->setHtmlAttribute('placeholder', 'E-mail');
		$form->addPassword('password', 'Password')
			->setRequired('Please enter your password.')
			->setHtmlAttribute('placeholder', 'Password');
		$form->addSubmit('send', 'Log in');
		$form->onSuccess[] = [$this, 'processLoginForm'];
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 */
	public function processLoginForm(Form $form): void {
		$values = $form->getValues();
		try {
			$this->getUser()->login(new PasswordCredentials($values->email, $values->password));
			$this->redirect('Dashboard:');
		} catch (AuthenticationException $e) {
			$form->addError($this->translator->translate("Incorrect e-mail or password."));
		}
	}

	protected function createComponentRegisterForm(): Form {
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addText('username', 'Username')
			->setRequired('Please fill in your username.')
			->setHtmlAttribute('placeholder', 'Username');
		$form->addText('email', 'E-mail')
			->setRequired('Please fill in an e-mail.')
			->addRule($form::EMAIL, 'The e-mail must be in correct format.')
			->setHtmlAttribute('placeholder', 'E-mail');
		$form->addPassword('password', 'Password')
			->setRequired('Please fill in both of the password fields.')
			->setHtmlAttribute('placeholder', 'Password');
		$form->addPassword('password_confirm', 'Password again')
			->addRule(Form::EQUAL, 'Passwords do not match', $form['password'])
			->setRequired('Please fill in both of the password fields.')
			->setHtmlAttribute('placeholder', 'Password again')
			->setOmitted(true);
		$form->addSubmit('send', 'Submit');
		$form->onSuccess[] = [$this, 'processRegisterForm'];
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 * @throws \Nette\Utils\AssertionException
	 * @throws IncorrectCredentialsException
	 */
	public function processRegisterForm(Form $form): void {
		$values = $form->getValues();
		try {
			$this->model->registerUser($values->username, $values->email, null, $values->password);
			$this->flashMessage($this->translator->translate('Sign up successful, now you can log in.'));
			$this->redirect('Homepage:');
		} catch (IncorrectCredentialsException $e) {
			$form->addError($this->translator->translate('This account already exists.'));
		}
	}
}
