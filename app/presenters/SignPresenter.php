<?php

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use PP\IncorrectCredentialsException;
use PP\SignModel;
use PP\User\PasswordCredentials;

/**
 * @author Andrej Souček
 */
class SignPresenter extends Presenter {

	/**
	 * @var SignModel
	 */
	private $model;

	public function __construct(SignModel $model) {
		parent::__construct();
		$this->model = $model;
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionFbLogin() {
		try {
			$this->getUser()->login($this->model->getFacebookCredentials());
		} catch (AuthenticationException $e) {
			$this->flashMessage("Error while connecting to Facebook");
		}
		$this->redirect('Sign:');
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionLogOut() {
		$this->getUser()->logout();
		$this->redirect('Sign:');
	}

	public function renderDefault() {
		$this->template->currentUserName = $this->getCurrentUserName();
		$this->template->fbLoginUrl = $this->getFbLoginUrl();
	}

	public function getCurrentUserName() : string {
		return $this->getUser()->getIdentity() ? $this->getUser()->getIdentity()->username : "unknown";
	}

	/**
	 * @return string
	 * @throws \Nette\Application\UI\InvalidLinkException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function getFbLoginUrl() : string {
		return $this->model->generateLoginUrl($this->link('//fbLogin'));
	}

	protected function createComponentLoginForm() : Form {
		$form = new Form;
		$form->addText('email', 'E-mail')
			->setRequired('Please enter your e-mail.')
			->addRule($form::EMAIL, 'The e-mail must be in correct format.')
			->setAttribute('placeholder', 'E-mail');
		$form->addPassword('password', 'Password')
			->setRequired('Please enter your password.')
			->setAttribute('placeholder', 'Password');
		$form->addSubmit('send', 'Log in');
		$form->onSuccess[] = [$this, 'processLoginForm'];
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function processLoginForm(Form $form) : void {
		$values = $form->getValues();
		try {
			$this->getUser()->login(new PasswordCredentials($values->email, $values->password));
			$this->redirect('Dashboard:');
		} catch (AuthenticationException $e) {
			$form->addError("Incorrect e-mail or password.");
		}
	}

	protected function createComponentRegisterForm(): Form {
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
			$this->flashMessage('Sign up successful, now you can log in.');
			$this->redirect('Homepage:');
		} catch (IncorrectCredentialsException $e) {
			$form->addError('This account already exists.');
		}
	}
}
