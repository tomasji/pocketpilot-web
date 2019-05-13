<?php

declare(strict_types=1);

namespace PP\Presenters;

use GettextTranslator\Gettext;
use Nette\Application\UI\Form;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use PP\DirResolver;

/**
 * @author Andrej Souček
 */
class ContactPresenter extends AppPresenter {

	use Authentication;
	use Navbar;

	/**
	 * @var IMailer
	 */
	private $mailer;

	public function __construct(DirResolver $dirResolver, Gettext $translator, IMailer $mailer) {
	parent::__construct($dirResolver, $translator);
		$this->mailer = $mailer;
	}

	public function createComponentForm(): Form {
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addTextArea('message', 'Your message')
			->setRequired('Please enter the message.')
			->setHtmlAttribute('placeholder', 'Your message');
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}

	/**
	 * @internal
	 * @param Form $form
	 * @throws \Nette\Application\AbortException
	 */
	public function processForm(Form $form): void {
		$values = $form->getValues();
		$this->mailer->send($this->createMail($values->message));
		$this->flashMessage($this->translator->translate('Message has been sent.'));
		$this->redirect('this');
	}

	private function createMail(string $s): Message {
		$message = new Message();
		$message->addTo('andrejsoucek@gmail.com');
		$message->setFrom($this->getUser()->getIdentity()->email);
		$message->setSubject('Zpráva z PocketPilot.cz');
		$message->setBody($s);
		return $message;
	}
}
