<?php

declare(strict_types=1);

namespace PP\Terrain;

use Nette\SmartObject;

class TerrainEntry
{
    use SmartObject;

    /**
     * @var float
     */
    private $relativeDistance;

    /**
     * @var float
     */
    private $elevation;

    public function __construct(float $relativeDistance, float $elevation)
    {
        $this->relativeDistance = $relativeDistance;
        $this->elevation = $elevation;
    }

    public function getRelativeDistance(): float
    {
        return $this->relativeDistance;
    }

    public function getElevationMetres(): float
    {
        return $this->elevation;
    }

    public function getElevationFeet(): float
    {
        return $this->elevation;
    }

    private function mToFt(): float
    {
        return round($this->elevation * 3.2808399);
    }
}
