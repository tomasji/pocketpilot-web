<?php

namespace PP;

/**
 * @author Andrej Souček
 */
class DirResolver {

	/**
	 * @var string
	 */
	private $appDir;

	/**
	 * @var string
	 */
	private $scriptsDir;

	/**
	 * @var string
	 */
	private $manifestDir;

	public function __construct(string $appDir, string $scriptsDir, string $manifestDir) {
		$this->appDir = $appDir;
		$this->scriptsDir = $scriptsDir;
		$this->manifestDir = $manifestDir;
	}

	public function getAppDir(): string {
		return $this->appDir;
	}

	/**
	 * @return string
	 */
	public function getScriptsDir(): string {
		return $this->scriptsDir;
	}

	/**
	 * @return string
	 */
	public function getManifestDir(): string {
		return $this->manifestDir;
	}
}
