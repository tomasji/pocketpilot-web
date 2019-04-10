<?php

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use PP\Navbar;
use PP\Track\TrackEntry;
use PP\Track\TrackRead;
use PP\Authentication;

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

	public function __construct(TrackRead $read) {
		parent::__construct();
		$this->read = $read;
	}

	public function renderDefault() {
		$this->template->tracks = $this->getTracks();
	}

	public function renderMap($id) {
		bdump($this->getTracks()[$id]);
	}

	private function getTracks() {
		if (empty($tracks)) {
			$this->tracks = $this->read->fetchBy($this->user->getId());
		}
		return $this->tracks;
	}
}
