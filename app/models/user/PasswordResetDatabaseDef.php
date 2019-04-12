<?php

declare(strict_types=1);

namespace PP\User;

/**
 * @author Andrej Souček
 */
class PasswordResetDatabaseDef {

	const
		TABLE_NAME = 'pass_recovery',
		COLUMN_TOKEN = 'token',
		COLUMN_ID_USER = 'user_id',
		COLUMN_CREATED = 'created';
}
