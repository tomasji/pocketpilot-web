<?php

declare(strict_types=1);

namespace PP\Presenters;

use GettextTranslator\Gettext;
use Nette\Forms\Form;
use PP\DirResolver;
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

	public function __construct(DirResolver $dirResolver, Gettext $translator, UserRead $read) {
		parent::__construct($dirResolver, $translator);
		$this->read = $read;
	}

	protected function createComponentForm(): Form {
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addText('email', 'E-mail')->setHtmlAttribute('readonly', 'readonly');
		$form->addText('token', 'Secret key')->setHtmlAttribute('readonly', 'readonly');
//		$form->addSubmit('submit', 'Generate new');
		$form->setDefaults($this->getDefaults());
		return $form;
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
