<?php

namespace App\Http\Controllers\V1;

use App\Services\TransactionService;

/**
 * Class TransactionController
 * @package App\Http\Controllers\V1
 */
class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    public $transactionService;

    /**
     * TransactionController constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService) {
        $this->transactionService = $transactionService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $wallets = $this->transactionService->getAll();

        return successResponse('Wallets successfully retrieved', $wallets);
    }
}
