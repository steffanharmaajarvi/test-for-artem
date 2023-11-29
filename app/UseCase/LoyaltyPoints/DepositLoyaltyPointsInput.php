<?php

declare(strict_types=1);

namespace App\UseCase\LoyaltyPoints;

interface DepositLoyaltyPointsInput
{

    public function getAccountType(): string;

    public function getAccountId(): string;

    public function getLoyaltyPointsRuleId(): int;

    public function getDescription(): string;

    public function getPaymentId(): int;

    public function getPaymentAmount(): float;

    public function getPaymentTime(): \DateTimeInterface;
}
