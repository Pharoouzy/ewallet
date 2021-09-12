<?php


namespace App\Services;

/**
 * Class ReportService
 * @package App\Services
 */
class ReportService {


    /**
     * @return array
     */
    public function getReport(){

        $usersCount = (new UserService())->getTotal();
        $walletsCount = (new WalletService())->getTotal();
        $walletsBalance = (new WalletService())->getTotalWalletsBalance();
        $transactionsCount = (new TransactionService())->getTotal();
        $transactionsVolume = (new TransactionService())->getTotalTransactionsVolume();

        $data = [
            'users_count' => $usersCount,
            'wallets_count' => $walletsCount,
            'total_wallets_balance' => $walletsBalance,
            'transactions_count' => $transactionsCount,
            'transactions_volume' => $transactionsVolume,
        ];

        return $data;
    }

}
