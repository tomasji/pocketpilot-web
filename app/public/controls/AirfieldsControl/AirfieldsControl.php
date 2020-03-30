<?php

declare(strict_types=1);

namespace PP\Controls;

use GettextTranslator\Gettext;
use PP\Airfield\AirfieldRead;

/**
 * @author Andrej Souček
 */
class AirfieldsControl extends BaseControl {

	/**
	 * @var AirfieldRead
	 */
	private $read;

	/**
	 * @var Gettext
	 */
	private $translator;

	public function __construct(AirfieldRead $read, Gettext $translator) {
		$this->read = $read;
		$this->translator = $translator;
	}

	public function render(): void {
		$this->template->setFile(__DIR__.'/airfieldsControl.latte');
		$this->template->setTranslator($this->translator);
		$this->template->airfields = $this->getAirfields();
		$this->template->render();
	}

	public function getAirfields(): array {
		return $this->read->fetchAll();
	}
}

interface AirfieldsControlFactory {
	public function create(): AirfieldsControl;
}
