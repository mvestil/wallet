<?php
/**
 * Class WalletRepository
 *
 * @author    markbonnievestil
 */

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Wallet;

/**
 * Class WalletRepository
 *
 * This class interacts with Models directly. Queries to database related to Wallet
 */
class WalletRepository
{
    /**
     * Fetches all wallets
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        $wallets = Wallet::latest()->get();

        // Due to limitation of eager loading with limit clause, we lazy load recent transactions for each wallet
        // https://github.com/laravel/framework/issues/4835
        foreach ($wallets as $wallet) {
            $wallet->transactions = $this->getRecentTransactions($wallet);
        }

        return $wallets;
    }

    /**
     * Find wallet by email
     *
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return Wallet::where('email', $email)
            ->withRecentTransactions()
            ->first();
    }

    /**
     * Get transactions of a specific wallet
     *
     * @param integer|Wallet $wallet
     * @param integer|null $limit
     * @return mixed
     */
    public function getTransactions($wallet, $limit = null)
    {
        if (is_numeric($wallet)) {
            $wallet = Wallet::findOrFail($wallet);
        }

        $transactions = $wallet->transactions();

        if ($limit) {
            return $transactions->recent($limit)->get();
        }

        return $transactions->get();
    }

    /**
     * Get the most recent transactions of a specific wallet
     *
     * @param $walletId
     * @return mixed
     */
    public function getRecentTransactions($walletId)
    {
        return $this->getTransactions($walletId, 3);
    }

    /**
     * Create a wallet in database
     *
     * @param array $params
     * @return mixed
     */
    public function create($params)
    {
        return Wallet::create($params);
    }

    /**
     * Delete a wallet by email
     *
     * @param $email
     * @return mixed
     */
    public function deleteByEmail($email)
    {
        return Wallet::where('email', $email)->delete();
    }

    /**
     * Credit a wallet, create a transaction record and updates the balance of a wallet
     *
     * @param $wallet
     * @param $params
     * @return Transaction
     */
    public function credit($wallet, $params)
    {
        $transaction = Transaction::create([
            'type'      => Transaction::TYPE_CREDIT,
            'wallet_id' => $wallet->id,
            'amount'    => $params['amount'],
            'remarks'   => !empty($params['remarks']) ? $params['remarks'] : null
        ]);

        $wallet->balance = $wallet->balance + $params['amount'];
        $wallet->save();

        return $transaction;
    }

    /**
     * Debit a wallet, create a transaction record and updates the balance of a wallet
     *
     * @param $wallet
     * @param $params
     * @return Transaction
     */
    public function debit($wallet, $params)
    {
        $transaction = Transaction::create([
            'type'      => Transaction::TYPE_DEBIT,
            'wallet_id' => $wallet->id,
            'amount'    => -$params['amount'],
            'remarks'   => !empty($params['remarks']) ? $params['remarks'] : null
        ]);

        $wallet->balance = $wallet->balance - $params['amount'];
        $wallet->save();

        return $transaction;
    }
}