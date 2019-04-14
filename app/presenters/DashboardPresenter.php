<?php

declare(strict_types=1);

namespace PP\Presenters;

use PP\Authentication;
use PP\Navbar;

/**
 * @author Andrej Souček
 */
class DashboardPresenter extends AppPresenter {

	use Authentication;
	use Navbar;
}
