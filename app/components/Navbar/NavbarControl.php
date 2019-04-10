<?php

namespace PP\Controls;

use Nette\Application\UI\Control;

/**
 * @author Andrej Souček
 */
class NavbarControl extends Control {

	public function render() {
		$this->template->render(__DIR__ . '/navbarControl.latte');
	}
}
