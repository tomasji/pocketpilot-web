<?php

declare(strict_types=1);

namespace PP\Presenters;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use Nette\UnexpectedValueException;
use PP\DirResolver;
use PP\Navbar;
use PP\Track\TrackCreate;
use PP\Track\TrackDelete;
use PP\Track\TrackEntry;
use PP\Track\TrackRead;
use PP\Authentication;
use PP\Track\TrackUpdate;

/**
 * @author Andrej Souček
 * @User
 */
class TracksPresenter extends AppPresenter {

	use Authentication;
	use Navbar;

	/**
	 * @var TrackRead
	 */
	private $read;

	/**
	 * @var TrackEntry[]
	 */
	private $tracks;

	/**
	 * @var TrackCreate
	 */
	private $create;

	/**
	 * @var TrackUpdate
	 */
	private $update;

	/**
	 * @var TrackDelete
	 */
	private $delete;

	public function __construct(
		DirResolver $dirResolver, Gettext $translator,
		TrackRead $read, TrackCreate $create, TrackUpdate $update, TrackDelete $delete
	) {
		parent::__construct($dirResolver, $translator);
		$this->read = $read;
		$this->create = $create;
		$this->update = $update;
		$this->delete = $delete;
	}

	public function renderDefault(): void {
		$this->template->tracks = $this->getTracks();
		$this->template->maximum = $this->getMaximum();
	}

	public function getTracks(): array {
		if (empty($tracks)) {
			$this->tracks = $this->read->fetchBy($this->user->getId());
		}
		return $this->tracks;
	}

	public function getMaximum(): int {
		switch ($this->user->getRoles()[0]) {
			case 'admin':
			case 'premium':
				return 100;
			case 'user':
				return 5;
			default:
				throw new UnexpectedValueException('Unknown role.');
		}
	}

	public function renderMap($id): void {
		if ($id) {
			if (isset($this->getTracks()[$id]) && $this->getTracks()[$id]->getUserId() === $this->getUser()->getId()) {
				$this->template->trackJson = $this->getTracks()[$id]->getTrack();
			} else {
				$this->redirect('Tracks:');
			}
		} else {
			$this->template->trackJson = null;
		}
	}

	public function handleDelete($id, $name): void {
		try {
			$this->delete->process((int)$id);
			$this->flashMessage($this->translator->translate("Track '%s' has been deleted.", $name));
		} catch (\PDOException $t) {
			$this->flashMessage($this->translator->translate("An error occurred while deleting the track."));
		}
		$this->redrawControl();
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 */
	public function processForm(Form $form): void {
		$values = $form->getValues();
		$wpts = json_decode($values->waypoints, true);
		if (count($wpts) <= 1) {
			$this->flashMessage($this->translator->translate('Track must have more than 1 point'));
			$this->redrawControl();
			return;
		} else {
			try {
				if ($values->trackId) {
					$this->update->process((int)$values->trackId, $values->name, $wpts);
				} else {
					$this->create->process($values->name, $this->getUser()->getId(), $wpts);
				}
				$this->payload->forceRedirect = true;
				$this->flashMessage($this->translator->translate("Track '%s' has been saved.", $values->name));
				$this->redirect('Tracks:');
			} catch (\PDOException $e) {
				$this->flashMessage($this->translator->translate('An error occurred while saving the track.'));
				$this->redrawControl();
			}
		}
	}

	protected function createComponentForm(): Form {
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addText('name', 'Track name')
			->addRule(Form::REQUIRED, 'Please fill in the name.')
			->addRule(Form::MAX_LENGTH, 'The name cannot be longer than %d letters.', 50);
		$form->addHidden('waypoints');
		$form->addHidden('trackId');
		$form->addSubmit('save', 'Save');
		$form->getElementPrototype()->addClass('ajax');
		$form->setDefaults($this->getDefaults());
		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}

	private function getDefaults(): array {
		$id = $this->getParameter('id');
		if ($id) {
			return [
				'trackId' => $id,
				'name' => $this->getTracks()[$id]->getName()
			];
		}
		return [];
	}
}
