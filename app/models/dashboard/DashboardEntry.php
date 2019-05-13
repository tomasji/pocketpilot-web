<?php

declare(strict_types=1);

namespace PP\Dashboard;

use Nette\SmartObject;
use Nette\Utils\DateTime;

/**
 * @author Andrej Souček
 */
class DashboardEntry {

	use SmartObject;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var DateTime
	 */
	private $created;

	/**
	 * @var null|string
	 */
	private $item1;

	/**
	 * @var null|string
	 */
	private $item2;

	/**
	 * @var null|string
	 */
	private $item3;

	/**
	 * @var null|string
	 */
	private $item4;

	public function __construct(
		int $id, string $title, DateTime $created, ?string $item1, ?string $item2, ?string $item3, ?string $item4
	) {
		$this->id = $id;
		$this->title = $title;
		$this->created = $created;
		$this->item1 = $item1;
		$this->item2 = $item2;
		$this->item3 = $item3;
		$this->item4 = $item4;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @return DateTime
	 */
	public function getCreated(): DateTime {
		return $this->created;
	}

	/**
	 * @return null|string
	 */
	public function getItem1(): ?string {
		return $this->item1;
	}

	/**
	 * @return null|string
	 */
	public function getItem2(): ?string {
		return $this->item2;
	}

	/**
	 * @return null|string
	 */
	public function getItem3(): ?string {
		return $this->item3;
	}

	/**
	 * @return null|string
	 */
	public function getItem4(): ?string {
		return $this->item4;
	}
}
