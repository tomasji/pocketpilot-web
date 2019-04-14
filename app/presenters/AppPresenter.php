<?php
/*
 * Copyright © 2000-2019 ANTEE s.r.o., All rights reserved. Confidential.
 */

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use PP\Controls\WebpackControl;
use PP\DirResolver;

/**
 * @author Andrej Souček
 */
class AppPresenter extends Presenter {

	/**
	 * @var DirResolver
	 */
	private $dirResolver;

	public function __construct(DirResolver $dirResolver) {
		parent::__construct();
		$this->dirResolver = $dirResolver;
	}

	public function createComponentWebpack() {
		return new WebpackControl($this->dirResolver);
	}
}
