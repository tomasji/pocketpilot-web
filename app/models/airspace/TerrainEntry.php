<?php

declare(strict_types=1);

namespace PP\Airspace;

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

    public function getElevation(): float
    {
        return $this->elevation;
    }

}
