<?php
/*
 * Copyright © 2000-2019 ANTEE s.r.o., All rights reserved. Confidential.
 */

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use PP\Controls\WebpackControl;
use PP\DirResolver;
use GettextTranslator\Gettext;

/**
 * @author Andrej Souček
 */
class AppPresenter extends Presenter {

	/**
	 * @var string
	 * @persistent
	 */
	public $lang;

	/**
	 * @var DirResolver
	 */
	private $dirResolver;

	/**
	 * @var Gettext
	 */
	protected $translator;

	public function __construct(DirResolver $dirResolver, Gettext $translator) {
		parent::__construct();
		$this->dirResolver = $dirResolver;
		$this->translator = $translator;
	}

	public function startup() {
		parent::startup();
		$this->translator->setLang($this->lang); // too late in beforeRender
	}

	public function beforeRender() {
		$this->template->setTranslator($this->translator);
	}

	protected function createComponentWebpack() {
		return new WebpackControl($this->dirResolver);
	}
}
