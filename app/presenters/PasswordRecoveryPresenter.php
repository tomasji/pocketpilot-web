<?php

declare(strict_types=1);

namespace PP\Presenters;

use GettextTranslator\Gettext;
use Nette;
use Nette\Application\UI\Form;
use PP\DirResolver;
use PP\IncorrectCredentialsException;
use PP\User\PasswordReset;
use PP\User\UserRead;

/**
 * @author Andrej Souček
 */
class PasswordRecoveryPresenter extends AppPresenter {

	/**
	 * @var UserRead
	 */
	private $userRead;

	/** @var PasswordReset */
	private $pwReset;

	public function __construct(DirResolver $dirResolver, Gettext $translator, UserRead $userRead, PasswordReset $pwReset) {
		parent::__construct($dirResolver, $translator);
		$this->userRead = $userRead;
		$this->pwReset = $pwReset;
	}

	public function actionReset($token): void {
		if (isset($token) && !$this->pwReset->isTokenValid($token)) {
			$this->flashMessage($this->translator->translate('Invalid token.'));
			$this->redirect('Homepage:');
		}
	}

	protected function createComponentRecoveryForm(): Form {
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addText('email', 'E-mail')
			->setRequired('Please enter your e-mail.')
			->addRule($form::EMAIL, 'The e-mail must be in correct format.')
			->setHtmlAttribute('placeholder', 'E-mail');
		$form->addSubmit('send', 'Reset password');

		$form->onSuccess[] = array($this, 'processRecoveryForm');
		return $form;
	}

	protected function createComponentNewPasswordForm(): Form {
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addPassword('password', 'Password')
			->setRequired('Please fill in both of the password fields.')
			->setHtmlAttribute('placeholder', 'Password');
		$form->addPassword('password_confirm', 'Password again')
			->addRule(Form::EQUAL, 'Passwords do not match', $form['password'])
			->setRequired('Please fill in both of the password fields.')
			->setHtmlAttribute('placeholder', 'Password again')
			->setOmitted(true);
		$form->addSubmit('send', 'Save');

		$form->onSuccess[] = array($this, 'processNewPasswordForm');
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws Nette\Application\AbortException
	 * @throws Nette\Utils\AssertionException
	 * @throws Nette\Application\UI\InvalidLinkException
	 */
	public function processRecoveryForm(Form $form): void {
		try {
			$user = $this->userRead->fetchByEmail($form->values->email);
			$this->pwReset->sendLinkTo($user);
			$this->flashMessage(sprintf($this->translator->translate("An e-mail has been sent to %s."), $user->getEmail()));
			$this->redirect('Sign:');
		} catch(IncorrectCredentialsException $e) {
			$form->addError($this->translator->translate('E-mail not found. Please sign up.'));
		}
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws Nette\Application\AbortException
	 */
	public function processNewPasswordForm(Form $form): void {
		$this->pwReset->changePassword($this->getParameter('token'), $form->values->password);
		$this->flashMessage($this->translator->translate('Password change has been successful. Now you can log in.'));
		$this->redirect('Homepage:');
	}
}
