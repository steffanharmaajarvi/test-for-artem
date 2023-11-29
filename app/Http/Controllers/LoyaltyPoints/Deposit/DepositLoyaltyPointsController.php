<?php

namespace App\Http\Controllers\LoyaltyPoints\Deposit;

use App\Exceptions\AccountNotActiveException;
use App\Exceptions\AccountNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltyPoints\DepositLoyaltyPointsRequest;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\UseCase\LoyaltyPoints\DepositLoyaltyPointsUseCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepositLoyaltyPointsController extends Controller
{
    public function __construct(
        private DepositLoyaltyPointsUseCase $depositLoyaltyPointsUseCase,
    ) {
    }

    /**
     * @throws ValidationException
     * @throws \InvalidArgumentException
     */
    public function handle(DepositLoyaltyPointsRequest $request)
    {
        Log::info('Deposit transaction input: ' . $request->getContent());

        try {
            return $this->depositLoyaltyPointsUseCase->handle($request);
        } catch (AccountNotFoundException $exception) {
            Log::info(
                sprintf(
                    'Account %s not found',
                    $request->getAccountId()
                )
            );

            return response()->json(['message' => 'Account is not found'], Response::HTTP_BAD_REQUEST);
        } catch (AccountNotActiveException $exception) {
            Log::info(
                sprintf(
                    'Account %s not active',
                    $request->getAccountId()
                )
            );

            return response()->json(['message' => 'Account is not active'], Response::HTTP_BAD_REQUEST);
        }
    }
}
