<?php

declare(strict_types=1);

namespace App\UseCase\LoyaltyPoints;

use App\Enums\AccountTypeEnum;
use App\Exceptions\AccountNotActiveException;
use App\Exceptions\AccountNotFoundException;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\Repository\LoyaltyAccountRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepositLoyaltyPointsUseCase
{

    public function __construct(
        private LoyaltyAccountRepository $loyaltyAccountRepository,
    ) {
    }

    /**
     * @throws AccountNotActiveException
     * @throws AccountNotFoundException
     */
    public function handle(DepositLoyaltyPointsInput $input): LoyaltyPointsTransaction
    {
        $account = $this->getAccount($input);

        $transaction =  LoyaltyPointsTransaction::performPaymentLoyaltyPoints(
            $account->id,
            $input->getLoyaltyPointsRuleId(),
            $input->getDescription(),
            $input->getPaymentId(),
            $input->getPaymentAmount(),
            $input->getPaymentTime()
        );

        Log::info($transaction);

        if ($account->isEligibleForEmailNotification()) {
            Mail::to($account)->send(new LoyaltyPointsReceived($transaction->points_amount, $account->getBalance()));
        }

        if ($account->isEligibleForPhoneNotification()) {
            // instead SMS component
            Log::info('You received' . $transaction->points_amount . 'Your balance' . $account->getBalance());
        }

        return $transaction;
    }

    /**
     * @throws AccountNotFoundException
     * @throws AccountNotActiveException
     */
    private function getAccount(DepositLoyaltyPointsInput $input): LoyaltyAccount
    {
        switch ($input->getAccountType()) {
            case AccountTypeEnum::PHONE:
                $account = $this->loyaltyAccountRepository->findByPhone($input->getAccountId());
                break;
            case AccountTypeEnum::EMAIL:
                $account = $this->loyaltyAccountRepository->findByEmail($input->getAccountId());
                break;
            case AccountTypeEnum::CARD:
                $account = $this->loyaltyAccountRepository->findByCard($input->getAccountId());
                break;
            default:
                $account = null;
        }

        if ($account === null) {
            throw new AccountNotFoundException();
        }

        if (!$account->active) {
            throw new AccountNotActiveException();
        }

        return $account;
    }

}
