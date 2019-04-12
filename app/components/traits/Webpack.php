<?php

declare(strict_types=1);

namespace PP;

use PP\Controls\WebpackControl;

trait Webpack {

	private $resolver;

	public function injectScriptsDir(DirResolver $resolver) {
		$this->resolver = $resolver;
	}

	protected function createComponentWebpack(): WebpackControl {
		return new WebpackControl($this->resolver);
	}
}
