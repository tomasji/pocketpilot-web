<?php

declare(strict_types=1);

namespace PP\POI;

/**
 * @author Andrej Souček
 */
class POIDatabaseDef
{

    public const
        TABLE_NAME = 'poi',
        COLUMN_NAME = 'name',
        COLUMN_DESCRIPTION = 'description',
        COLUMN_LOCATION = 'location',
        ALIAS_LONGITUDE = 'longitude',
        ALIAS_LATITUDE = 'latitude',
        ALIAS_DISTANCE = 'distance';
}
