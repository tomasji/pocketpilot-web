<?php

declare(strict_types=1);

namespace PP\Airspace;

/**
 * @author Andrej Souček
 */
class AirspaceDatabaseDef
{

    public const
        TABLE_NAME = 'airspace',
        COLUMN_NAME = 'name',
        COLUMN_TYPE = 'type',
        COLUMN_LOWER_BOUND = 'bound_lower',
        COLUMN_LOWER_BOUND_DATUM = 'bound_lower_datum',
        COLUMN_UPPER_BOUND = 'bound_upper',
        COLUMN_UPPER_BOUND_DATUM = 'bound_upper_datum',
        COLUMN_LOCATION = 'location',
        ALIAS_START = 'start',
        ALIAS_END  = 'end';
}
