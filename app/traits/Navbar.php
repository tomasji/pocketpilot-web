<?php

namespace PP;

use PP\Controls\NavbarControl;

/**
 * @author Andrej Souček
 */
trait Navbar {

	protected function createComponentNavbar() {
		return new NavbarControl();
	}
}
