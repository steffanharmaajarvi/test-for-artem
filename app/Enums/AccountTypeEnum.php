<?php

declare(strict_types=1);

namespace App\Enums;

class AccountTypeEnum
{
    public const PHONE = 'phone';

    public const CARD = 'card';

    public const EMAIL = 'email';

    public const AVAILABLE_TYPES = [
        self::CARD,
        self::EMAIL,
        self::PHONE,
    ];
}
