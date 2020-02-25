<?php

declare(strict_types=1);

namespace PP\Presenters;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use PP\Controls\ApiKeyForm;
use PP\Controls\ApiKeyFormFactory;
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
	 * @var ApiKeyFormFactory
	 */
	private $apiKeyFormFactory;

	public function __construct(ApiKeyFormFactory $apiKeyFormFactory) {
		parent::__construct();
		$this->apiKeyFormFactory = $apiKeyFormFactory;
	}

	protected function createComponentForm(): ApiKeyForm {
		$form = $this->apiKeyFormFactory->create();
		$form->onSuccess[] = function() {
			$this->redirect('this');
		};
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
			'token' => $this->read->fetchBy($this->getUser()->getIdentity()->email)->getToken()
		];
	}
}
