<?php

declare(strict_types=1);

namespace PP\API;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
use PP\POI\POIRead;

/**
 * @author Andrej Souček
 */
class POIPresenter extends Presenter
{

    /**
     * @var POIRead
     */
    private $read;

    public function __construct(POIRead $read)
    {
        parent::__construct();
        $this->read = $read;
    }

    /**
     * @param string $lng
     * @param string $lat
     * @param int $range
     * @param bool $hasRunway
     * @throws \Nette\Application\AbortException
     */
    public function actionRead(string $lng, string $lat, int $range = 500, ?bool $hasRunway = null): void
    {
        $this->assertCoordinates($lng, $lat);
        $this->assertRange($range);
        $point = $this->read->fetchClosestPoiTo($lng, $lat, $range, $hasRunway);
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

    /**
     * @param int $range
     * @throws \Nette\Application\AbortException
     */
    private function assertRange(int $range): void
    {
        if ($range > 100000) {
            $this->getHttpResponse()->setCode(400);
            $this->sendResponse(new JsonResponse(['error' => 'Range must be lower than 100 000 m.']));
        }
    }

    private function assertCoordinates(string $lng, string $lat): void
    {
        try {
            Validators::assert($lng, 'numeric');
            Validators::assert($lat, 'numeric');
        } catch (AssertionException $e) {
            $this->getHttpResponse()->setCode(400);
            $this->sendResponse(new JsonResponse(['error' => 'Bad coordinates']));
        }
    }

    public function sendTemplate(): void
    {
    }
}
