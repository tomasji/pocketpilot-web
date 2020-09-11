<?php

declare(strict_types=1);

namespace PP\Controls;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use PP\POI\POIImporter;
use RuntimeException;
use UnexpectedValueException;

/**
 * @author Andrej Souček
 */
class POIImportForm extends BaseControl
{

    /**
     * @var array
     */
    public $onError = [];

    /**
     * @var POIImporter
     */
    private $POIImporter;

    /**
     * @var Gettext
     */
    private $translator;

    public function __construct(POIImporter $POIImporter, Gettext $translator)
    {
        $this->POIImporter = $POIImporter;
        $this->translator = $translator;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/poiImportForm.latte');
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
            $this->POIImporter->process($form->values->file);
        } catch (UnexpectedValueException $e) {
            $this->onError($this->translator->translate('Unsupported file type.'));
        } catch (RuntimeException $e) {
            $this->onError($e->getMessage());
        }
    }
}

interface POIImportFormFactory
{
    public function create(): POIImportForm;
}
