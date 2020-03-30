<?php

declare(strict_types=1);

namespace PP\Controls;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use PP\Airfield\AirfieldsImporter;
use RuntimeException;
use UnexpectedValueException;

/**
 * @author Andrej Souček
 */
class AirfieldsImportForm extends BaseControl
{

    /**
     * @var array
     */
    public $onError = [];

    /**
     * @var AirfieldsImporter
     */
    private $airfieldsImporter;

    /**
     * @var Gettext
     */
    private $translator;

    public function __construct(AirfieldsImporter $airfieldsImporter, Gettext $translator)
    {
        $this->airfieldsImporter = $airfieldsImporter;
        $this->translator = $translator;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/airfieldsImportForm.latte');
        $this->template->setTranslator($this->translator);
        $this->template->render();
    }

    protected function createComponentForm(): Form
    {
        $form = new Form();
        $form->setTranslator($this->translator);
        $form->addUpload('file', 'File')->setRequired();
        $form->addSubmit('submit', 'Upload');
        $form->onSuccess[] = [$this, 'processForm'];
        return $form;
    }

    public function processForm(Form $form): void
    {
        try {
            $this->airfieldsImporter->process($form->values->file);
        } catch (UnexpectedValueException $e) {
            $this->onError($this->translator->translate('Unsupported file type.'));
        } catch (RuntimeException $e) {
            $this->onError($e->getMessage());
        }
    }
}

interface AirfieldsImportFormFactory
{
    public function create(): AirfieldsImportForm;
}
