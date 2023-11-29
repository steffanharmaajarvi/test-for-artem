<?php

namespace App\Http\Controllers\LoyaltyPoints\Deposit;

use App\Exceptions\AccountNotActiveException;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ReasonNotSpecifiedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltyPoints\CancelLoyaltyPointsRequest;
use App\Http\Requests\LoyaltyPoints\DepositLoyaltyPointsRequest;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\UseCase\LoyaltyPoints\CancelLoyaltyPointsUseCase;
use App\UseCase\LoyaltyPoints\DepositLoyaltyPointsUseCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CancelLoyaltyPointsController extends Controller
{
    public function __construct(
        private CancelLoyaltyPointsUseCase $cancelLoyaltyPointsUseCase,
    ) {

    }

    public function handle(
        CancelLoyaltyPointsRequest $request
    )
    {
        try {
            $this->cancelLoyaltyPointsUseCase->handle($request);
        } catch (ReasonNotSpecifiedException $exception) {
            return response()->json(['message' => 'Cancellation reason is not specified'], 400);
        } catch (EntityNotFoundException $exception) {
            return response()->json(['message' => 'Transaction is not found'], 400);
        }
    }
}
