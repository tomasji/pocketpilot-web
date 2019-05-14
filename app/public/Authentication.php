<?php

declare(strict_types=1);

namespace PP\Presenters;

/**
 * @author Andrej Souček
 */
trait Authentication {

	public function checkRequirements($element): void {
		parent::checkRequirements($this->getReflection());
		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:');
		}
	}
}
