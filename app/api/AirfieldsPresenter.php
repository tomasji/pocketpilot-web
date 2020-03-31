<?php

declare(strict_types=1);

namespace PP\API;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
use PP\Airfield\AirfieldRead;

/**
 * @author Andrej Souček
 */
class AirfieldsPresenter extends Presenter
{

    /**
     * @var AirfieldRead
     */
    private $read;

    public function __construct(AirfieldRead $read)
    {
        parent::__construct();
        $this->read = $read;
    }

    public function checkRequirements($element): void
    {
        parent::checkRequirements($this->getReflection());
        if (!$this->getUser()->isLoggedIn()) {
            $this->getHttpResponse()->setCode(403);
            $this->sendResponse(new JsonResponse(['error' => 'User not authenticated']));
        }
    }

    /**
     * @param string $lng
     * @param string $lat
     * @throws \Nette\Application\AbortException
     */
    public function actionRead(string $lng, string $lat): void
    {
        $this->assertCoordinates($lng, $lat);
        $point = $this->read->fetchClosestTo($lng, $lat);
        if ($point) {
            $latlng = new \stdClass();
            $latlng->lat = $point->getLatitude();
            $latlng->lon = $point->getLongitude();
            $x = new \stdClass();
            $x->name = $point->getName();
            $x->description = $point->getDescription();
            $x->center = $latlng;
            $this->sendResponse(new JsonResponse($x));
        }
        $this->sendResponse(new JsonResponse(new \stdClass()));
    }

    private function assertCoordinates(string $lng, string $lat): void
    {
        try {
            Validators::assert($lng, 'numeric');
            Validators::assert($lat, 'numeric');
        } catch (AssertionException $e) {
            $this->getHttpResponse()->setCode(500);
            $this->sendResponse(new JsonResponse(['error' => 'Bad coordinates']));
        }
    }

    public function sendTemplate(): void
    {
    }
}
