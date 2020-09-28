<?php

declare(strict_types=1);

namespace PP\Airspace;

class RelativePosition
{

    /**
     * @var float
     */
    private $in;

    /**
     * @var float
     */
    private $out;

    public function __construct(float $in, float $out)
    {
        $this->in = $in;
        $this->out = $out;
    }

    /**
     * @return float
     */
    public function getIn(): float
    {
        return $this->in;
    }

    /**
     * @return float
     */
    public function getOut(): float
    {
        return $this->out;
    }
}
