<?php
/*
 * Copyright © 2000-2019 ANTEE s.r.o., All rights reserved. Confidential.
 */

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use PP\UserLoggedIn;

/**
 * @author Andrej Souček
 */
class DashboardPresenter extends Presenter {

	use UserLoggedIn;
}
