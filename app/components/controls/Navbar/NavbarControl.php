<?php

declare(strict_types=1);

namespace PP\Controls;

use Nette\Application\UI\Control;

/**
 * @author Andrej Souček
 */
class NavbarControl extends Control {

	public function render(): void {
		$this->template->render(__DIR__ . '/navbarControl.latte');
	}
}
