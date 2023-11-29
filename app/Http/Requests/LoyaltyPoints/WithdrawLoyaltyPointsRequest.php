<?php

declare(strict_types=1);

namespace App\Http\Requests\LoyaltyPoints;

use App\Enums\AccountTypeEnum;
use App\UseCase\LoyaltyPoints\DepositLoyaltyPointsInput;
use App\UseCase\LoyaltyPoints\WithdrawLoyaltyPointsInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WithdrawLoyaltyPointsRequest extends FormRequest implements WithdrawLoyaltyPointsInput
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
            'description' => 'string',
            'points_amount' => 'numeric|gt:0',
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

    public function getDescription(): string
    {
        return $this->get('description');
    }

    public function getPointsAmount(): float
    {
        return $this->get('payment_amount');
    }
}
