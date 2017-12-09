<?php

/**
 * Class Wallet
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Wallet
 *
 * A model class for wallet
 */
class Wallet extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['email', 'balance'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }
}
