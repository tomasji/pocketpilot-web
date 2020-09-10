<?php

declare(strict_types=1);

namespace PP\Airspace;

use Nette\SmartObject;

/**
 * @author Andrej Souček
 */
class AirspaceEntry
{
    use SmartObject;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var VerticalBounds
     */
    private $verticalBounds;

    public function __construct(string $name, string $type, VerticalBounds $verticalBounds)
    {
        $this->name = $name;
        $this->type = $type;
        $this->verticalBounds = $verticalBounds;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVerticalBounds(): VerticalBounds
    {
        return $this->verticalBounds;
    }
}
