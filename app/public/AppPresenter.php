<?php

declare(strict_types=1);

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use PP\Controls\WebpackControl;
use PP\Controls\WebpackControlFactory;
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
	 * @inject
	 * @var Gettext
	 */
	public $translator;

	/**
	 * @inject
	 * @var WebpackControlFactory
	 */
	public $webpackControlFactory;

	public function startup() {
		parent::startup();
		$this->translator->setLang($this->lang); // too late in beforeRender
	}

	public function beforeRender() {
		$this->template->setTranslator($this->translator);
	}

	protected function createComponentWebpack(): WebpackControl {
		return $this->webpackControlFactory->create();
	}
}
