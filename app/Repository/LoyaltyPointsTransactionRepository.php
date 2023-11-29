<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;

class LoyaltyPointsTransactionRepository
{

    public function findCancelledById(int $id): ?LoyaltyPointsTransaction
    {
        return LoyaltyPointsTransaction::where('id', '=', $id)->where('canceled', '=', 0)->first();
    }
}
