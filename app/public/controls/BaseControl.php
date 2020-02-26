<?php

declare(strict_types=1);

namespace PP\Controls;

use Nette\Application\UI\Control;
use PP\Latte\TemplateProperty;

/**
 * @author Andrej Souček
 *
 * @property-read TemplateProperty $template
 */
abstract class BaseControl extends Control {
}
