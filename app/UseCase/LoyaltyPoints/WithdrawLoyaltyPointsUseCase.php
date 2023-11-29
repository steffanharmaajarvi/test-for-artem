<?php

declare(strict_types=1);

namespace App\UseCase\LoyaltyPoints;

use App\Enums\AccountTypeEnum;
use App\Exceptions\AccountNotActiveException;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\InsufficientFundsException;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\Repository\LoyaltyAccountRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WithdrawLoyaltyPointsUseCase
{

    public function __construct(
        private LoyaltyAccountRepository $loyaltyAccountRepository,
    ) {
    }

    /**
     * @throws AccountNotActiveException
     * @throws AccountNotFoundException
     * @throws InsufficientFundsException
     */
    public function handle(WithdrawLoyaltyPointsInput $input): LoyaltyPointsTransaction
    {
        $account = $this->getAccount($input);

        if ($account->getBalance() < $input->getPointsAmount()) {
            Log::info('Insufficient funds: ' . $input->getPointsAmount());

            throw new InsufficientFundsException();
        }

        $transaction = LoyaltyPointsTransaction::withdrawLoyaltyPoints($account->id, $input->getPointsAmount(), $input->getDescription());

        Log::info($transaction);

        return $transaction;
    }

    /**
     * @throws AccountNotFoundException
     * @throws AccountNotActiveException
     */
    private function getAccount(WithdrawLoyaltyPointsInput $input): LoyaltyAccount
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
