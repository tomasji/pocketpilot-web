<?php

namespace PP\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use PP\HomepageModel;
use PP\User\PasswordCredentials;

class HomepagePresenter extends Presenter {

	/**
	 * @var HomepageModel
	 */
	private $model;

	public function __construct(HomepageModel $model) {
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
		$this->redirect('Homepage:');
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionLogOut() {
		$this->getUser()->logout();
		$this->redirect('Homepage:');
	}

	public function renderDefault() {
		$this->template->currentUserName = $this->getCurrentUserName();
		$this->template->fbLoginUrl = $this->getFbLoginUrl();
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
}
