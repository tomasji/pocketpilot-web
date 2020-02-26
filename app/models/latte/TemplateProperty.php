<?php

declare(strict_types=1);

namespace PP\Latte;

use PP\Presenters\AppPresenter;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Security\User;

/**
 * @property-read User $user
 * @property-read AppPresenter $presenter
 * @property-read Control $control
 * @property-read string $baseUri
 * @property-read string $basePath
 * @property-read array $flashes
 */
final class TemplateProperty extends Template
{
}
