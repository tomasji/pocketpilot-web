<?php

declare(strict_types=1);

namespace PP\Track;

/**
 * @author Andrej Souček
 */
class TrackDatabaseDef
{

    public const
        TABLE_NAME = 'tracks',
        COLUMN_ID = 'id',
        COLUMN_TRACK = 'track',
        COLUMN_USER_ID = 'user_id',
        COLUMN_NAME = 'name',
        COLUMN_CREATION_DATE = 'cre_date',
        COLUMN_HASH = 'hash';
}
