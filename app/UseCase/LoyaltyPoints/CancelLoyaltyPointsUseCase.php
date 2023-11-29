<?php

declare(strict_types=1);

namespace App\UseCase\LoyaltyPoints;

use App\Enums\AccountTypeEnum;
use App\Exceptions\AccountNotActiveException;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ReasonNotSpecifiedException;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\Repository\LoyaltyAccountRepository;
use App\Repository\LoyaltyPointsTransactionRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CancelLoyaltyPointsUseCase
{
    public function __construct(
        private LoyaltyPointsTransactionRepository $loyaltyPointsTransactionRepository,
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws ReasonNotSpecifiedException
     */
    public function handle(CancelLoyaltyPointsInput $input): void
    {
        if ($input->getCancellationReason() === '') {
            throw new ReasonNotSpecifiedException();
        }

        $transaction = $this->loyaltyPointsTransactionRepository->findCancelledById($input->getTransactionId());
        if ($transaction === null) {
            throw new EntityNotFoundException();
        }

        $transaction->canceled = time();
        $transaction->cancellation_reason = $input->getCancellationReason();
        $transaction->save();
    }
}
