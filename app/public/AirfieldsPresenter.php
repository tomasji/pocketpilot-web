<?php

declare(strict_types=1);

namespace PP\Presenters;

use PP\Controls\AirfieldsControl;
use PP\Controls\AirfieldsControlFactory;
use PP\Controls\AirfieldsImportForm;
use PP\Controls\AirfieldsImportFormFactory;

/**
 * @author Andrej Souček
 */
class AirfieldsPresenter extends AppPresenter
{
    use Authentication {
        Authentication::checkRequirements as commonAuthentication;
    }
    use Navbar;

    /**
     * @var AirfieldsImportFormFactory
     */
    private $formFactory;

    /**
     * @var AirfieldsControlFactory
     */
    private $airfieldsControlFactory;

    public function __construct(
        AirfieldsImportFormFactory $formFactory,
        AirfieldsControlFactory $airfieldsControlFactory
    ) {
        parent::__construct();
        $this->formFactory = $formFactory;
        $this->airfieldsControlFactory = $airfieldsControlFactory;
    }

    public function checkRequirements($element): void
    {
        $this->commonAuthentication($element);
        if (!$this->user->isInRole('admin')) {
            $this->flashMessage($this->translator->translate('Access denied.'));
            $this->redirect('Dashboard:default');
        }
    }

    protected function createComponentForm(): AirfieldsImportForm
    {
        $c = $this->formFactory->create();
        $c->onError[] = function ($m) {
            $this->flashMessage($m);
        };
        return $c;
    }

    protected function createComponentAirfields(): AirfieldsControl
    {
        return $this->airfieldsControlFactory->create();
    }
}
