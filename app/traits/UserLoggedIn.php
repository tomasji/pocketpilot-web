<?php
/*
 * Copyright © 2000-2019 ANTEE s.r.o., All rights reserved. Confidential.
 */

namespace PP;


/**
 * @author Andrej Souček
 */
trait UserLoggedIn {

	public function checkRequirements($element) : void {
		parent::checkRequirements($this->getReflection());
		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:');
		}
	}
}
