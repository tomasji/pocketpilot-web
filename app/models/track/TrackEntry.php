<?php

declare(strict_types=1);

namespace PP\Track;

use Nette\SmartObject;
use Nette\Utils\DateTime;

/**
 * @author Andrej Souček
 */
class TrackEntry {

	use SmartObject;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $track;

	/**
	 * @var string
	 */
	private $length;

	/**
	 * @var int
	 */
	private $userId;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var DateTime
	 */
	private $created;

	public function __construct(int $id, string $track, string $length, int $userId, string $name, DateTime $created) {
		$this->id = $id;
		$this->track = $track;
		$this->length = $length;
		$this->userId = $userId;
		$this->name = $name;
		$this->created = $created;
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
	public function getLength(): string {
		return $this->length;
	}

	/**
	 * @return string
	 */
	public function getTrack(): string {
		return $this->track;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->userId;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return DateTime
	 */
	public function getCreated(): DateTime {
		return $this->created;
	}
}
