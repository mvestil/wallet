<?php

/**
 * Class Transaction
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * Model class for wallet transaction
 */
class Transaction extends Model
{
    const TYPE_CREDIT = 'CREDIT';
    const TYPE_DEBIT  = 'DEBIT';

    /**
     * @var array
     */
    protected $fillable = ['wallet_id', 'amount', 'type', 'remarks'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo('App\Models\Wallet');
    }
}
