<?php

declare(strict_types=1);

namespace PP\Controls;

use GettextTranslator\Gettext;
use Nette\Application\UI\Control;

/**
 * @author Andrej Souček
 */
class NavbarControl extends Control {

	/**
	 * @var Gettext
	 */
	private $translator;

	public function __construct(Gettext $translator) {
		$this->translator = $translator;
	}

	public function render(): void {
		$this->template->setTranslator($this->translator);
		$this->template->render(__DIR__ . '/navbarControl.latte');
	}
}

interface NavbarControlFactory {
	public function create(): NavbarControl;
}
