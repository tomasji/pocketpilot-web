<?php

declare(strict_types=1);

namespace PP\Presenters;

use Nette\Forms\Form;
use PP\Authentication;
use PP\DirResolver;
use PP\Navbar;
use PP\User\UserRead;

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

	public function __construct(DirResolver $dirResolver, UserRead $read) {
		parent::__construct($dirResolver);
		$this->read = $read;
	}

	protected function createComponentForm(): Form {
		$form = new Form();
		$form->addText('id', 'ID')->setHtmlAttribute('readonly', 'readonly');
		$form->addText('token', 'Secret key')->setHtmlAttribute('readonly', 'readonly');
		$form->addSubmit('submit', 'Generate new');
		$form->setDefaults($this->getDefaults());
		return $form;
	}

	/**
	 * @return array
	 * @throws \Nette\Utils\AssertionException
	 */
	private function getDefaults(): array {
		return [
			'id' => $this->getUser()->getId(),
			'token' => $this->read->fetchById($this->getUser()->getId())->getToken()
		];
	}
}
