<?php
/**
 * Class InsufficientFundsException
 */

namespace App\Exceptions\Wallet;

use Throwable;

/**
 * Class InsufficientFundsException
 *
 * Should be thrown when fund is insufficient when trying to debit a wallet
 *
 */
class InsufficientFundsException extends WalletException
{
    public function __construct($message = "Insufficient funds", $code = 40002, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
