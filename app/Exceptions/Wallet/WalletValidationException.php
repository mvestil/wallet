<?php
/**
 * Class WalletValidationException
 */
namespace App\Exceptions\Wallet;

use Throwable;

/**
 * Class WalletValidationException
 *
 * Generic exception for validation errors
 */
class WalletValidationException extends WalletException
{
    public function __construct($message = "Validation error", $code = 40001, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
