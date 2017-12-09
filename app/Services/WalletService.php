<?php
/**
 * Class WalletService
 *
 * @author    markbonnievestil
 */

namespace App\Services;

use App\Events\WalletCredited;
use App\Events\WalletDebited;
use App\Exceptions\Wallet\InsufficientFundsException;
use App\Exceptions\Wallet\WalletNotFoundException;
use App\Repositories\WalletRepository;

/**
 * Class WalletService
 *
 * Contains all business logic for handling wallets such as retrieval/create/update/delete/debit/credit, etc.
 */
class WalletService
{

    /**
     * @var WalletRepository
     */
    protected $wallet;

    /**
     * WalletService constructor.
     *
     * @param WalletRepository $wallet
     */
    public function __construct(WalletRepository $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Fetch all wallets from the repository
     */
    public function all()
    {
        return $this->wallet->all();
    }

    /**
     * Find a wallet by email from the repository
     *
     * @param $email
     * @return mixed
     * @throws WalletNotFoundException
     */
    public function findByEmail($email)
    {
        if (!$wallet = $this->wallet->findByEmail($email)) {
            throw new WalletNotFoundException();
        }

        return $wallet;
    }

    /**
     * Create a wallet
     *
     * @param $params
     * @return mixed
     */
    public function create($params)
    {
        return $this->wallet->create($params);
    }

    /**
     * Delete a wallet by email
     *
     * @param $email
     * @return mixed
     */
    public function delete($email)
    {
        return $this->wallet->deleteByEmail($email);
    }


    /**
     * Credit amount to a wallet
     *
     * @param $email
     * @param $params
     * @return mixed
     */
    public function credit($email, $params)
    {
        $wallet      = $this->findByEmail($email);
        $transaction = $this->wallet->credit($wallet, $params);

        // Trigger Credited event - this is just to show how we can separate logic (e.g email/sms notification, etc.)
        // Check the EventServiceProvider for possible actions (listeners) when this event is triggered
        event(new WalletCredited($transaction));
    }

    /**
     * Debit amount to a wallet
     *
     * @param $email
     * @param $params
     * @throws InsufficientFundsException
     */
    public function debit($email, $params)
    {
        $wallet = $this->findByEmail($email);

        if (!$this->hasSufficientFunds($wallet, $params['amount'])) {
            throw new InsufficientFundsException();
        }

        $transaction = $this->wallet->debit($wallet, $params);

        // Trigger Dedited event - this is just to show how we can separate logic (e.g email/sms notification, etc.)
        // Check the EventServiceProvider for possible actions (listeners) when this event is triggered
        event(new WalletDebited($transaction));
    }

    /**
     * Check if wallet has sufficient balance for debiting
     *
     * @param Wallet $wallet
     * @param float  $debitAmount
     * @return bool
     */
    protected function hasSufficientFunds($wallet, $debitAmount)
    {
        return $wallet->balance - $debitAmount >= 0;
    }
}