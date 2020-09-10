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

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
