<?php

namespace App\Http\Controllers\LoyaltyPoints\Deposit;

use App\Exceptions\AccountNotActiveException;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\InsufficientFundsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltyPoints\DepositLoyaltyPointsRequest;
use App\Http\Requests\LoyaltyPoints\WithdrawLoyaltyPointsRequest;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\UseCase\LoyaltyPoints\DepositLoyaltyPointsUseCase;
use App\UseCase\LoyaltyPoints\WithdrawLoyaltyPointsUseCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WithdrawLoyaltyPointsController extends Controller
{

    public function __construct(
        private WithdrawLoyaltyPointsUseCase $withdrawLoyaltyPointsUseCase,
    ) {

    }

    public function handle(
        WithdrawLoyaltyPointsRequest $request
    )
    {
        Log::info('Withdraw loyalty points transaction input: ' . $request->getContent());

        try {
            return $this->withdrawLoyaltyPointsUseCase->handle($request);
        } catch (AccountNotActiveException $e) {
            Log::info(
                sprintf(
                    'Account is not active: %type %id',
                    $request->getAccountType(),
                    $request->getAccountId(),
                ));

            return response()->json(['message' => 'Account is not active'], Response::HTTP_BAD_REQUEST);
        } catch (AccountNotFoundException $e) {
            Log::info(
                sprintf(
                    'Account is not found: %s %s',
                    $request->getAccountType(),
                    $request->getAccountId(),
                )
            );

            return response()->json(['message' => 'Account is not found'], Response::HTTP_BAD_REQUEST);
        } catch (InsufficientFundsException $e) {
            return response()->json(['message' => 'Insufficient funds'], Response::HTTP_BAD_REQUEST);
        }
    }
}
