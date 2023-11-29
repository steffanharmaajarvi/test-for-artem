<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\LoyaltyAccount;

class LoyaltyAccountRepository
{

    public function findByEmail(string $email): LoyaltyAccount
    {
        return LoyaltyAccount::where('email', '=', $email)->first();
    }

    public function findByPhone(string $phone): LoyaltyAccount
    {
        return LoyaltyAccount::where('phone', '=', $phone)->first();
    }

    public function findByCard(string $card): LoyaltyAccount
    {
        return LoyaltyAccount::where('card', '=', $card)->first();
    }
}
