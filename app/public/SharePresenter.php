<?php

declare(strict_types=1);

namespace PP\Presenters;

use GettextTranslator\Gettext;
use PP\DirResolver;
use PP\Track\TrackRead;

/**
 * @author Andrej Souček
 */
class SharePresenter extends AppPresenter
{

    /**
     * @var TrackRead
     */
    private $read;

    public function __construct(TrackRead $read)
    {
        parent::__construct();
        $this->read = $read;
    }

    public function renderDefault(string $t): void
    {
        $this->template->trackJson = $this->getTrackJson($t);
    }

    public function getTrackJson(string $hash): string
    {
        $track = $this->read->fetchByHash($hash);
        return $track ? $track->getTrack() : '';
    }
}
