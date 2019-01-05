<?php

namespace PP\User;

use Nette\Application\LinkGenerator;
use Nette\Database\Context;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\Security\Passwords;
use Nette\SmartObject;
use Nette\Utils\DateTime;
use PP\TokenProcessor;

/**
 * @author Andrej Souček
 */
class PasswordReset {

	use SmartObject;

	/**
	 * @var Context
	 */
	private $database;

	/**
	 * @var LinkGenerator
	 */
	private $linkGenerator;

	/**
	 * @var IMailer
	 */
	private $mailer;

	public function __construct(Context $database, LinkGenerator $linkGenerator, IMailer $mailer) {
		$this->database = $database;
		$this->linkGenerator = $linkGenerator;
		$this->mailer = $mailer;
	}

	/**
	 * @param $token
	 * @return bool true if token is valid
	 */
	public function isTokenValid($token) : bool {
		if (!TokenProcessor::isTokenValid($token)) {
			return false;
		} else {
			try {
				$tokenHash = TokenProcessor::calculateTokenHash($token);
				$record = $this->database->table(PasswordResetDatabaseDef::TABLE_NAME)
					->where(PasswordResetDatabaseDef::COLUMN_TOKEN, $tokenHash)
					->fetch();
				if (!$record || TokenProcessor::isTokenExpired($record->offsetGet('created'))) {
					return false;
				}
			} catch (\Exception $e) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param $token
	 * @param $pw
	 * @throws \Exception
	 */
	public function changePassword($token, $pw) : void {
		$tokenHash = TokenProcessor::calculateTokenHash($token);
		$userId = $this->database->table(PasswordResetDatabaseDef::TABLE_NAME)
			->where(PasswordResetDatabaseDef::COLUMN_TOKEN, $tokenHash)
			->fetchField(PasswordResetDatabaseDef::COLUMN_ID_USER);
		$this->database->table(UserDatabaseDef::TABLE_NAME)
			->update([UserDatabaseDef::COLUMN_PASSWORD_HASH => Passwords::hash($pw)]);
		$this->database->table(PasswordResetDatabaseDef::TABLE_NAME)
			->where(PasswordResetDatabaseDef::COLUMN_ID_USER, $userId)
			->delete();
	}

	/**
	 * @param UserEntry $user
	 * @throws \Exception
	 */
	public function sendLinkTo(UserEntry $user) : void {
		$token = $this->createAndSaveToken($user->getId());
		$link = $this->linkGenerator->link('PasswordRecovery:reset', ['token' => $token]);
		$this->mailer->send($this->createMail($user->getEmail(), $link));
	}

	/**
	 * @param $userId
	 * @return string
	 * @throws \Exception
	 */
	private function createAndSaveToken($userId) : string {
		TokenProcessor::generateToken($tokenForLink, $tokenHashForDatabase);
		$this->database->table(PasswordResetDatabaseDef::TABLE_NAME)->insert([
			'token' => $tokenHashForDatabase,
			'user_id' => $userId,
			'created' => new DateTime()
		]);
		return $tokenForLink;
	}

	private function createMail($to, $link) : Message {
		$mail = new Message();
		$mail
			->setFrom('Pocket Pilot <recovery@pocketpilot.cz>')
			->addTo($to)
			->setSubject('Password reset')
			->setBody(
				"Hello,\nyou have requested to reset your password.\n"
				. "To reset your password please click on this link:\n"
				. $link . ".\n\n"
				."This e-mail has been sent automatically.\nDo not reply to this e-mail.");
		return $mail;
	}
}
