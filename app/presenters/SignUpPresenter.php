<?php

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use PP\User\DuplicateNameException;
use PP\User\UserModel;

/**
 * @author Andrej Souček
 */
class SignUpPresenter extends Presenter {

	/**
	 * @var UserModel
	 */
	private $userModel;

	public function __construct(UserModel $userModel) {
		$this->userModel = $userModel;
	}

	protected function createComponentForm(): Form {
		$form = new Form();
		$form->addText('username', 'Username:')
			->setRequired('Please fill in your username.')
			->setAttribute('placeholder', 'Username');
		$form->addText('email', 'E-mail:')
			->setRequired('Please fill in an e-mail.')
			->addRule($form::EMAIL, 'The e-mail must be in correct format.')
			->setAttribute('placeholder', 'E-mail');
		$form->addPassword('password1', 'Password')
			->setRequired('Please fill in both of the password fields.')
			->setAttribute('placeholder', 'Password');
		$form->addPassword('password2', 'Password again:')
			->setRequired('Please fill in both of the password fields.')
			->setAttribute('placeholder', 'Password again');
		$form->addSubmit('send', 'Sign up');
		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function processForm(Form $form): void {
		$values = $form->getValues();
		try {
			if ($values->password1 === $values->password2) {
				$this->userModel->registerUser($values->username, $values->email, null, $values->password1);
				$this->flashMessage('Sign up successful, now you can log in.');
				$this->redirect('Homepage:');
			} else {
				$form->addError('The passwords do not match.');
			}
		} catch (DuplicateNameException $e) {
			$form->addError('This account already exists.');
		}
	}
}
