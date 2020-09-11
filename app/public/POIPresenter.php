<?php

declare(strict_types=1);

namespace PP\Presenters;

use PP\Controls\POIControl;
use PP\Controls\POIControlFactory;
use PP\Controls\POIImportForm;
use PP\Controls\POIImportFormFactory;

/**
 * @author Andrej Souček
 */
class POIPresenter extends AppPresenter
{
    use Authentication {
        Authentication::checkRequirements as commonAuthentication;
    }
    use Navbar;

    /**
     * @var POIImportFormFactory
     */
    private $formFactory;

    /**
     * @var POIControlFactory
     */
    private $POIControlFactory;

    public function __construct(
        POIImportFormFactory $formFactory,
        POIControlFactory $POIControlFactory
    ) {
        parent::__construct();
        $this->formFactory = $formFactory;
        $this->POIControlFactory = $POIControlFactory;
    }

    public function checkRequirements($element): void
    {
        $this->commonAuthentication($element);
        if (!$this->user->isInRole('admin')) {
            $this->flashMessage($this->translator->translate('Access denied.'));
            $this->redirect('Dashboard:default');
        }
    }

    protected function createComponentForm(): POIImportForm
    {
        $c = $this->formFactory->create();
        $c->onError[] = function ($m) {
            $this->flashMessage($m);
        };
        return $c;
    }

    protected function createComponentPoi(): POIControl
    {
        return $this->POIControlFactory->create();
    }
}
