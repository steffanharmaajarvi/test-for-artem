<?php

declare(strict_types=1);

namespace App\Http\Requests\LoyaltyPoints;

use App\Enums\AccountTypeEnum;
use App\UseCase\LoyaltyPoints\CancelLoyaltyPointsInput;
use App\UseCase\LoyaltyPoints\DepositLoyaltyPointsInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CancelLoyaltyPointsRequest extends FormRequest implements CancelLoyaltyPointsInput
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_id' => 'required|integer|exists:loyalty_points_transaction,id',
            'cancellation_reason' => 'string',
        ];
    }

    public function getCancellationReason(): string
    {
        return $this->get('cancellation_reason');
    }

    public function getTransactionId(): int
    {
        return $this->get('transaction_id');
    }
}
