<?php

declare(strict_types=1);

namespace PP\Presenters;

use Nette\Application\UI\Presenter;
use PP\Authentication;
use PP\Navbar;
use PP\Webpack;

/**
 * @author Andrej Souček
 */
class DashboardPresenter extends Presenter {

	use Authentication;
	use Navbar;
	use Webpack;
}
