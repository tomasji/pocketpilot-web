<?php

declare(strict_types=1);

namespace PP\Presenters;

use PP\Controls\NavbarControl;
use PP\Controls\NavbarControlFactory;

/**
 * @author Andrej Souček
 */
trait Navbar
{

    /**
     * @var NavbarControlFactory
     * @inject
     */
    public $navbarControlFactory;

    protected function createComponentNavbar(): NavbarControl
    {
        return $this->navbarControlFactory->create();
    }
}
