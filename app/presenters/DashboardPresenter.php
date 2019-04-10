<?php

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use PP\Authentication;
use PP\Navbar;

/**
 * @author Andrej Souček
 */
class DashboardPresenter extends Presenter {

	use Authentication;
	use Navbar;
}
