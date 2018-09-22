<?php

namespace PP\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;

class HomepagePresenter extends Presenter {

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionLogOut() {
		$this->getUser()->logout();
		$this->redirect('Homepage:');
	}

	public function renderDefault() {
		$this->template->currentUserName = $this->getUser()->getIdentity() ? $this->getUser()->getIdentity()->username : null;
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
			$this->getUser()->login($values->email, $values->password);
//			$this->redirect('Map:');
			$this->flashMessage("SUCCESS");
			$this->redirect('Homepage:');
		} catch (AuthenticationException $e) {
			$form->addError("Incorrect e-mail or password.");
		}
	}
}
