<?php

declare(strict_types=1);

namespace PP\Presenters;

/**
 * @author Andrej Souček
 */
class HomepagePresenter extends AppPresenter
{

    public function renderDefault(): void
    {
        $this->template->lang = $this->getLang();
    }

    public function getLang(): string
    {
        return $this->translator->getLang();
    }
}
