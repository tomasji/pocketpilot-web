<?php

namespace PP\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use PP\Navbar;
use PP\Track\TrackCreate;
use PP\Track\TrackEntry;
use PP\Track\TrackRead;
use PP\Authentication;
use PP\Track\TrackUpdate;

/**
 * @author Andrej Souček
 * @User
 */
class TracksPresenter extends Presenter {

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

	public function __construct(TrackRead $read, TrackCreate $create, TrackUpdate $update) {
		parent::__construct();
		$this->read = $read;
		$this->create = $create;
		$this->update = $update;
	}

	public function renderDefault() {
		$this->template->tracks = $this->getTracks();
	}

	public function renderMap($id) {
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

	public function processForm(Form $form) {
		$values = $form->getValues();
		if ($values->trackId) {
			$this->update->process($values->trackId, $values->name, json_decode($values->waypoints, true));
		} else {
			$this->create->process($values->name, $this->getUser()->getId(), json_decode($values->waypoints, true));
		}
	}

	protected function createComponentForm() : Form {
		$form = new Form();
		$form->addText('name')
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

	private function getDefaults() {
		$id = $this->getParameter('id');
		if ($id) {
			return [
				'trackId' => $id,
				'name' => $this->getTracks()[$id]->getName()
			];
		}
		return [];
	}

	private function getTracks() {
		if (empty($tracks)) {
			$this->tracks = $this->read->fetchBy($this->user->getId());
		}
		return $this->tracks;
	}
}
