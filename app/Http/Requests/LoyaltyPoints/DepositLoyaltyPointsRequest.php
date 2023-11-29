<?php

declare(strict_types=1);

namespace App\Http\Requests\LoyaltyPoints;

use App\Enums\AccountTypeEnum;
use App\UseCase\LoyaltyPoints\DepositLoyaltyPointsInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepositLoyaltyPointsRequest extends FormRequest implements DepositLoyaltyPointsInput
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_type' => [
                'required',
                Rule::in(AccountTypeEnum::AVAILABLE_TYPES)
            ],
            'account_id' => 'required|exists:loyalty_account,id',
            'loyalty_points_rule' => 'exists:loyalty_points_rule,points_rule',
            'description' => 'string',
            'payment_id' => 'integer',
            'payment_amount' => 'numeric',
            'payment_time' => 'date_format:H:i:s',
        ];
    }

    public function getAccountType(): string
    {
        return $this->get('account_type');
    }

    public function getAccountId(): string
    {
        return $this->get('account_id');
    }

    public function getLoyaltyPointsRuleId(): int
    {
        return $this->get('loyalty_points_rule');
    }

    public function getDescription(): string
    {
        return $this->get('description');
    }

    public function getPaymentId(): int
    {
        return $this->get('payment_id');
    }

    public function getPaymentAmount(): float
    {
        return $this->get('payment_amount');
    }

    public function getPaymentTime(): \DateTimeInterface
    {
        return \DateTime::createFromFormat("H:i:s", $this->get('payment_time'));
    }
}
