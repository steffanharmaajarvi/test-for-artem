<?php

declare(strict_types=1);

namespace App\UseCase\LoyaltyPoints;

interface CancelLoyaltyPointsInput
{
    public function getCancellationReason(): string;

    public function getTransactionId(): int;
}
