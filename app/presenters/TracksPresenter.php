<?php

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use PP\Track\TrackEntry;
use PP\Track\TrackRead;

/**
 * @author Andrej Souček
 */
class TracksPresenter extends Presenter {

	/**
	 * @var TrackRead
	 */
	private $read;

	/**
	 * @var TrackEntry[]
	 */
	private $tracks;

	public function __construct(TrackRead $read) {
		parent::__construct();
		$this->read = $read;
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionDefault() {
		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:');
		}
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionLogOut() {
		$this->getUser()->logout();
		$this->redirect('Sign:');
	}

	public function renderDefault() {
		$this->template->tracks = $this->getTracks();
	}

	public function renderMap($id) {
		bdump($this->getTracks()[$id]);
		$this->template->hideNavbar = true;
	}

	private function getTracks() {
		if (empty($tracks)) {
			$this->tracks = $this->read->fetchBy($this->user->getId());
		}
		return $this->tracks;
	}
}
