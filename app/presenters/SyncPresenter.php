<?php

declare(strict_types=1);

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Forms\Form;
use PP\Authentication;
use PP\Navbar;
use PP\User\UserRead;
use PP\Webpack;

/**
 * @author Andrej Souček
 */
class SyncPresenter extends Presenter {

	use Authentication;
	use Navbar;
	use Webpack;

	/**
	 * @var UserRead
	 */
	private $read;

	public function __construct(UserRead $read) {
		parent::__construct();
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
