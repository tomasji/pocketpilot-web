<?php

namespace PP\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use PP\User\PasswordReset;
use PP\User\UserRead;
use PP\Webpack;

/**
 * @author Andrej Souček
 */
class PasswordRecoveryPresenter extends Presenter {

	use Webpack;

	/**
	 * @var UserRead
	 */
	private $userRead;

	/** @var PasswordReset */
	private $pwReset;

	public function __construct(UserRead $userRead, PasswordReset $pwReset) {
		parent::__construct();
		$this->userRead = $userRead;
		$this->pwReset = $pwReset;
	}

	public function actionReset($token) : void {
		if (isset($token) && !$this->pwReset->isTokenValid($token)) {
			$this->flashMessage('Invalid token.');
			$this->redirect('Homepage:');
		}
	}

	protected function createComponentRecoveryForm() : Form {
		$form = new Form();
		$form->addText('email', 'E-mail')
			->setRequired('Please enter your e-mail.')
			->addRule($form::EMAIL, 'The e-mail must be in correct format.')
			->setHtmlAttribute('placeholder', 'E-mail');
		$form->addSubmit('send', 'Reset password');

		$form->onSuccess[] = array($this, 'processRecoveryForm');
		return $form;
	}

	protected function createComponentNewPasswordForm() : Form {
		$form = new Form();
		$form->addPassword('password', 'Password')
			->setRequired('Please fill in both of the password fields.')
			->setHtmlAttribute('placeholder', 'Password');
		$form->addPassword('password_confirm', 'Password again:')
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
	 */
	public function processRecoveryForm(Form $form) : void {
		$user = $this->userRead->fetchByEmail($form->values->email);
		if ($user) {
			$this->pwReset->sendLinkTo($user);
			$this->flashMessage("An e-mail has been sent to {$user->getEmail()}.");
			$this->redirect('Homepage:');
		} else {
			$form->addError('E-mail not found.');
		}
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws Nette\Application\AbortException
	 */
	public function processNewPasswordForm(Form $form) : void {
		$this->pwReset->changePassword($this->getParameter('token'), $form->values->password);
		$this->flashMessage('Password change has been successful. Now you can log in.');
		$this->redirect('Homepage:');
	}
}
