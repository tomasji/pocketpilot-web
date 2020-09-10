<?php

declare(strict_types=1);

namespace PP\API;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\Responses\VoidResponse;
use Nette\Application\UI\Presenter;
use PP\Airspace\AirspaceEntry;
use PP\Airspace\AirspaceRead;

/**
 * @author Andrej Souček
 */
class AirspacePresenter extends Presenter
{

    /**
     * @var AirspaceRead
     */
    private $read;

    public function __construct(AirspaceRead $read)
    {
        parent::__construct();
        $this->read = $read;
    }

    /**
     * @params string $params
     * @throws \Nette\Application\AbortException
     */
    public function actionRead(string $path): void
    {
        $latlngs = array_map(static function ($s) {
            return explode(',', $s);
        }, explode('|', $path));
        if (count($latlngs) < 2) {
            $this->getHttpResponse()->setCode(400);
            $this->sendResponse(new JsonResponse(['error' => 'Path must contain at least two points.']));
        }
        $intersections = $this->read->fetchIntersections($latlngs);
        $xs = [];
        /** @var AirspaceEntry $intersection */
        foreach ($intersections as $intersection) {
            $x = new \stdClass();
            $x->name = $intersection->getName();
            $x->type = $intersection->getType();
            $xs[] = $x;
        }
        $this->sendResponse(new JsonResponse($xs));
    }

    public function sendTemplate(): void
    {
    }
}
