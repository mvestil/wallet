<?php
/**
 *  Class WalletNotFoundException
 */
namespace App\Exceptions\Wallet;

use Throwable;

/**
 * Class WalletNotFoundException
 *
 * Should be thrown when could not find wallet in the database
 */
class WalletNotFoundException extends WalletException
{
    public function __construct($message = "Wallet not found", $code = 40401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
