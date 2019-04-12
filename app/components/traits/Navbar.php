<?php

declare(strict_types=1);

namespace PP;

use PP\Controls\NavbarControl;

/**
 * @author Andrej Souček
 */
trait Navbar {

	protected function createComponentNavbar(): NavbarControl {
		return new NavbarControl();
	}
}
