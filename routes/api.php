<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('admin.auth')->group(function () {
    Route::namespace('Admin')->group(function () {
        Route::prefix('admin/wallets')->group(function () {
            Route::get('/', ['as' => 'api.admin.wallet.index', 'uses' => 'WalletController@index']);
            Route::get('/{email}', ['as' => 'api.admin.wallet.show', 'uses' => 'WalletController@show']);
            Route::post('/', ['as' => 'api.admin.wallet.store', 'uses' => 'WalletController@store']);
            Route::delete('/{email}', ['as' => 'api.admin.wallet.destroy', 'uses' => 'WalletController@destroy']);
        });
    });
});

// Assuming there is no authentication needed for these public APIs as per Acceptance Criteria
Route::prefix('v1')->group(function() {
    Route::namespace('Api\V1\User')->group(function () {
        Route::prefix('wallets/{email}')->group(function () {
            Route::get('/', ['as' => 'api.wallet.show', 'uses' => 'WalletController@show']);
            Route::put('/transact', ['as' => 'api.wallet.credit', 'uses' => 'WalletController@credit']);
            Route::delete('/transact', ['as' => 'api.wallet.debit', 'uses' => 'WalletController@debit']);
        });
    });
});




