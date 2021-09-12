<?php


namespace App\Services;

use App\Models\Transaction;

/**
 * Class TransactionService
 * @package App\Services
 */
class TransactionService {

    /**
     * @return mixed
     */
    public function getAll() {
        return Transaction::orderBy('id', 'desc')->get();
    }

    /**
     * @param int $status
     * @return mixed
     */
    public function getByStatus(int $status) {
        return Transaction::where('status', $status)->orderBy('id', 'desc')->get();
    }

    /**
     * @return mixed
     */
    public function getTotal() {
        return $this->getAll()->count();
    }

    /**
     * @return mixed
     */
    public function getTotalTransactionsVolume() {
        return $this->getByStatus(1)->sum('amount');
    }
}
