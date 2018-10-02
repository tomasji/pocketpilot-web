<?php

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use PP\IncorrectCredentialsException;
use PP\SignUpModel;

/**
 * @author Andrej Souček
 */
class SignUpPresenter extends Presenter {

	/**
	 * @var SignUpModel
	 */
	private $model;

	public function __construct(SignUpModel $model) {
		parent::__construct();
		$this->model = $model;
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
		$form->addPassword('password', 'Password')
			->setRequired('Please fill in both of the password fields.')
			->setAttribute('placeholder', 'Password');
		$form->addPassword('password_confirm', 'Password again:')
			->addRule(Form::EQUAL, 'Passwords do not match', $form['password'])
			->setRequired('Please fill in both of the password fields.')
			->setAttribute('placeholder', 'Password again')
			->setOmitted(true);
		$form->addSubmit('send', 'Sign up');
		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 * @throws \Nette\Utils\AssertionException
	 * @throws IncorrectCredentialsException
	 */
	public function processForm(Form $form): void {
		$values = $form->getValues();
		try {
			$this->model->registerUser($values->username, $values->email, null, $values->password);
			$this->flashMessage('Sign up successful, now you can log in.');
			$this->redirect('Homepage:');
		} catch (IncorrectCredentialsException $e) {
			$form->addError('This account already exists.');
		}
	}
}
