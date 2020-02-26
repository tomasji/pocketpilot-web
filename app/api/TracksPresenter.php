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
		parent::__construct();
		$this->read = $read;
	}

	public function checkRequirements($element): void {
		parent::checkRequirements($this->getReflection());
		if (!$this->getUser()->isLoggedIn()) {
			$this->getHttpResponse()->setCode(403);
			$this->sendResponse(new JsonResponse(['error' => 'User not authenticated']));
		}
	}

	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function actionRead(): void {
		$tracks = $this->read->fetchForUser($this->getUser()->getId());
		$tracks = array_map(function($track) {
			$x = new \stdClass();
			$x->id = $track->getId();
			$x->track = json_decode($track->getTrack());
			$x->name = $track->getName();
			$x->length = $track->getLength();
			$x->createdAt = $track->getCreated();
			return $x;
		}, array_values($tracks));
		$this->sendResponse(new JsonResponse($tracks));
	}

	public function sendTemplate(): void {
	}
}
