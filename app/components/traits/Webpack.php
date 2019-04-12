<?php

namespace PP;

use PP\Controls\WebpackControl;

trait Webpack {

	private $resolver;

	public function injectScriptsDir(DirResolver $resolver) {
		$this->resolver = $resolver;
	}

	protected function createComponentWebpack() {
		return new WebpackControl($this->resolver);
	}
}
