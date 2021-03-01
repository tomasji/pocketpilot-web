<?php

declare(strict_types=1);

namespace PP\API;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use PP\Airspace\AirspaceEntry;
use PP\Airspace\AirspaceRead;
use PP\Airspace\RelativePosition;
use PP\Airspace\TerrainEntry;

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
        $airspaces = [];
        /** @var AirspaceEntry $intersection */
        foreach ($intersections as $intersection) {
            $x = new \stdClass();
            $x->name = $intersection->getName();
            $x->type = $intersection->getType();
            $verticalBounds = new \stdClass();
            $verticalBounds->lower = $intersection->getVerticalBounds()->getLowerBound();
            $verticalBounds->lowerDatum = $intersection->getVerticalBounds()->getLowerBoundDatum();
            $verticalBounds->upper = $intersection->getVerticalBounds()->getUpperBound();
            $verticalBounds->upperDatum = $intersection->getVerticalBounds()->getUpperBoundDatum();
            $x->verticalBounds = $verticalBounds;
            $horizontalBounds = [];
            /** @var RelativePosition $relativePosition */
            foreach ($intersection->getHorizontalBounds() as $relativePosition) {
                $pos = new \stdClass();
                $pos->in = $relativePosition->getIn();
                $pos->out = $relativePosition->getOut();
                $horizontalBounds[] = $pos;
            }
            $x->horizontalBounds = $horizontalBounds;
            $airspaces[] = $x;
        }
        $terrain = $this->read->fetchTerrain($latlngs);
        $terrainPoints = [];
        /** @var TerrainEntry $point */
        foreach ($terrain as $point) {
            $x = new \stdClass();
            $x->relativeDistance = $point->getRelativeDistance();
            $x->elevation = $point->getElevation();
            $terrainPoints[] = $x;
        }

        $response = new \stdClass();
        $response->airspace = $airspaces;
        $response->terrain = $terrainPoints;
        $this->sendResponse(new JsonResponse($response));
    }

    public function sendTemplate(): void
    {
    }
}
