<?php

declare(strict_types=1);

namespace PP\Controls;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use Nette\Security\User;
use PP\Track\TrackCreate;
use PP\Track\TrackEntry;
use PP\Track\TrackRead;
use PP\Track\TrackUpdate;

/**
 * @author Andrej Souček
 */
class SaveTrackForm extends BaseControl {

	public $onSuccess = [];

	public $onError = [];

	/**
	 * @var TrackRead
	 */
	private $read;

	/**
	 * @var TrackCreate
	 */
	private $create;

	/**
	 * @var TrackUpdate
	 */
	private $update;

	/**
	 * @var Gettext
	 */
	private $translator;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var TrackEntry|null
	 */
	private $track;

	public function __construct(TrackRead $read, TrackCreate $create, TrackUpdate $update, Gettext $translator, User $user, ?TrackEntry $track) {
		$this->read = $read;
		$this->create = $create;
		$this->update = $update;
		$this->translator = $translator;
		$this->user = $user;
		$this->track = $track;
	}

	public function render() {
		$this->template->setFile(__DIR__.'/saveTrackForm.latte');
		$this->template->setTranslator($this->translator);
		$this->template->render();
	}

	protected function createComponentForm(): Form {
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addText('name', 'Track name')
			->addRule(Form::REQUIRED, 'Please fill in the name.')
			->addRule(Form::MAX_LENGTH, 'The name cannot be longer than %d letters.', 50);
		$form->addHidden('waypoints');
		$form->addHidden('trackId');
		$form->addSubmit('save', 'Save');
		$form->getElementPrototype()->addClass('ajax');
		$form->setDefaults($this->getDefaults());
		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Utils\AssertionException
	 */
	public function processForm(Form $form): void {
		$values = $form->getValues();
		$wpts = json_decode($values->waypoints, true);
		if (empty($wpts) || count($wpts) <= 1) {
			$this->onError($this->translator->translate('Track must have more than 1 point'));
		} else {
			try {
				if ($values->trackId) {
					$this->update->process((int)$values->trackId, $values->name, $wpts);
				} else {
					$this->create->process($values->name, $this->user->getId(), $wpts);
				}
				$this->onSuccess($values->name);
			} catch (\RuntimeException $e) {
				$this->onError($this->translator->translate('An error occurred while saving the track.'));
			}
		}
	}

	private function getDefaults(): array {
		if ($this->track) {
			return [
				'trackId' => $this->track->getId(),
				'name' => $this->track->getName()
			];
		}
		return [];
	}
}

interface SaveTrackFormFactory {
	public function create(?TrackEntry $track): SaveTrackForm;
}
