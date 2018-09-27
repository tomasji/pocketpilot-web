<?php

namespace PP\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use PP\Facebook\FacebookCredentials;
use PP\Facebook\FacebookModel;
use PP\User\PasswordCredentials;

class HomepagePresenter extends Presenter {

	/**
	 * @var FacebookModel
	 */
	private $fb;

	public function __construct(FacebookModel $fb) {
		parent::__construct();
		$this->fb = $fb;
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionFbLogin() {
		try {
			$fbUser = $this->fb->getFbUser();
			$this->getUser()->login(new FacebookCredentials($fbUser->getEmail(), $fbUser->getId(), $fbUser->getFirstName()));
		} catch (AuthenticationException $e) {
			$this->flashMessage("Error while connecting to Facebook");
		}
		$this->redirect('Homepage:');
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionLogOut() {
		$this->getUser()->logout();
		$this->redirect('Homepage:');
	}

	/**
	 * @throws \Nette\Application\UI\InvalidLinkException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function renderDefault() {
		$this->template->currentUserName = $this->getUser()->getIdentity() ? $this->getUser()->getIdentity()->username : null;
		$this->template->fbLoginUrl = $this->fb->generateLoginUrl($this->link('//fbLogin'));
	}

	protected function createComponentForm() : Form {
		$form = new Form;
		$form->addText('email', 'E-mail')
			->setRequired('Please enter your e-mail.')
			->addRule($form::EMAIL, 'The e-mail must be in correct format.')
			->setAttribute('placeholder', 'E-mail');
		$form->addPassword('password', 'Password')
			->setRequired('Please enter your password.')
			->setAttribute('placeholder', 'Password');
		$form->addSubmit('send', 'Log in');
		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 */
	public function processForm(Form $form) : void {
		$values = $form->getValues();
		try {
			$this->getUser()->login(new PasswordCredentials($values->email, $values->password));
//			$this->redirect('Map:');
			$this->flashMessage("SUCCESS");
			$this->redirect('Homepage:');
		} catch (AuthenticationException $e) {
			$form->addError("Incorrect e-mail or password.");
		}
	}
}
