<?php

declare(strict_types=1);

namespace PP\Airfield;

use Nette\SmartObject;

/**
 * @author Andrej Souček
 */
class AirfieldEntry {

	use SmartObject;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var string
	 */
	private $longitude;

	/**
	 * @var string
	 */
	private $latitude;

	public function __construct(string $name, string $description, string $longitude, string $latitude) {
		$this->name = $name;
		$this->description = $description;
		$this->longitude = $longitude;
		$this->latitude = $latitude;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getLongitude(): string {
		return $this->longitude;
	}

	/**
	 * @return string
	 */
	public function getLatitude(): string {
		return $this->latitude;
	}
}
