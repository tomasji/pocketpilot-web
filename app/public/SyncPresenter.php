<?php

declare(strict_types=1);

namespace PP\Presenters;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use PP\DirResolver;
use PP\User\UserRead;
use PP\User\UserUpdate;

/**
 * @author Andrej Souček
 */
class SyncPresenter extends AppPresenter {

	use Authentication;
	use Navbar;

	/**
	 * @var UserRead
	 */
	private $read;

	/**
	 * @var UserUpdate
	 */
	private $update;

	public function __construct(DirResolver $dirResolver, Gettext $translator, UserRead $read, UserUpdate $update) {
		parent::__construct($dirResolver, $translator);
		$this->read = $read;
		$this->update = $update;
	}

	protected function createComponentForm(): Form {
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addText('email', 'E-mail')->setHtmlAttribute('readonly', 'readonly');
		$form->addText('token', 'Secret key')->setHtmlAttribute('readonly', 'readonly');
		$form->addSubmit('submit', 'Generate new key');
		$form->setDefaults($this->getDefaults());
		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}

	/**
	 * @internal
	 * @throws \Nette\Application\AbortException
	 */
	public function processForm() {
		$this->update->regenerateTokenFor($this->getUser()->getIdentity());
		$this->redirect('this');
	}

	/**
	 * @return array
	 * @throws \Nette\Utils\AssertionException
	 */
	private function getDefaults(): array {
		return [
			'email' => $this->getUser()->getIdentity()->email,
			'token' => $this->read->fetch($this->getUser()->getIdentity()->email)->getToken()
		];
	}
}
