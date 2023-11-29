<?php

declare(strict_types=1);

namespace App\UseCase\LoyaltyPoints;

interface WithdrawLoyaltyPointsInput
{
    public function getAccountType(): string;

    public function getAccountId(): string;

    public function getDescription(): string;

    public function getPointsAmount(): float;

}
