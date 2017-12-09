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
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo('App\Models\Wallet');
    }

    /**
     * Scope to get recent transactions
     *
     * @param     $query
     * @param int $limit
     * @return mixed
     */
    public function scopeRecent($query, $limit = 3)
    {
        return $query->latest()
            ->take($limit);
    }
}
