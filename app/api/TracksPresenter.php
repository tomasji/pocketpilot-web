<?php

declare(strict_types=1);

namespace PP\API;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use PP\Track\TrackRead;

/**
 * @author Andrej Souček
 */
class TracksPresenter extends Presenter {

	/**
	 * @var TrackRead
	 */
	private $read;

	public function __construct(TrackRead $read) {
		$this->read = $read;
	}

	public function actionRead() {
		$tracks = $this->read->fetchBy($this->user->getId());
		$tracks = array_map(function($track) {
			$x = new \stdClass();
			$x->id = $track->getId();
			$x->track = $track->getTrack();
			$x->name = $track->getName();
			$x->length = $track->getLength();
			$x->createdAt = $track->getCreated();
			return $x;
		}, $tracks);
		$this->sendResponse(new JsonResponse($tracks));
	}

	public function sendTemplate(): void {
	}
}
