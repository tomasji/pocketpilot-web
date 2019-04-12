<?php

declare(strict_types=1);

namespace PP\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\UnexpectedValueException;
use PP\Navbar;
use PP\Track\TrackCreate;
use PP\Track\TrackDelete;
use PP\Track\TrackEntry;
use PP\Track\TrackRead;
use PP\Authentication;
use PP\Track\TrackUpdate;
use PP\Webpack;

/**
 * @author Andrej Souček
 * @User
 */
class TracksPresenter extends Presenter {

	use Authentication;
	use Navbar;
	use Webpack;

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

	public function __construct(TrackRead $read, TrackCreate $create, TrackUpdate $update, TrackDelete $delete) {
		parent::__construct();
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
			$this->delete->process($id);
			$this->flashMessage("Track '$name' has been deleted.");
		} catch (\PDOException $t) {
			$this->flashMessage("An error occurred while deleting the track.");
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
			$this->flashMessage('Track must have more than 1 point');
			$this->redrawControl();
			return;
		} else {
			try {
				if ($values->trackId) {
					$this->update->process($values->trackId, $values->name, $wpts);
				} else {
					$this->create->process($values->name, $this->getUser()->getId(), $wpts);
				}
				$this->payload->forceRedirect = true;
				$this->flashMessage("Track '$values->name' has been saved.");
				$this->redirect('Tracks:');
			} catch (\PDOException $e) {
				$this->flashMessage('An error occurred while saving the track.');
				$this->redrawControl();
			}
		}
	}

	protected function createComponentForm(): Form {
		$form = new Form();
		$form->addText('name', 'Track name')
			->addRule(Form::REQUIRED, 'Please fill in the name.')
			->addRule(Form::MAX_LENGTH, 'Please fill in the name.', 50);
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
