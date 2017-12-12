<?php
/**
 * Class WalletController
 */

namespace App\Http\Controllers\User;

use App\Exceptions\Wallet\WalletNotFoundException;
use App\Exceptions\Wallet\WalletValidationException;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class WalletController
 *
 * Handles Wallet user related actions. Injects WalletService to handle the business logic.
 */
class WalletController extends ApiController
{
    /**
     * @var WalletService
     */
    protected $wallet;

    /**
     * WalletController constructor.
     *
     * @param WalletService $wallet
     */
    public function __construct(WalletService $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Return a specific wallet with recent transactions
     *
     * @param  int $email
     * @return \Illuminate\Http\Response
     */
    public function show($email)
    {
        try {
            $wallet = $this->wallet->findByEmail($email);

            return $this->respond([
                'data' => $wallet
            ]);
        } catch (WalletNotFoundException $e) {
            return $this->respondNotFound($e->getMessage());
        } catch (\Exception $e) {
            return $this->respondInternalError();
        }
    }

    /**
     * Credit the wallet
     *
     * @param Request $request
     * @param         $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function credit(Request $request, $email)
    {
        try {
            $params = $request->all();

            $this->validateTransact($params);

            $this->wallet->credit($email, $params);
        } catch (WalletValidationException $e) {
            return $this->respondBadRequest($e->getMessage());
        } catch (\Exception $e) {
            return $this->respondInternalError();
        }
    }

    /**
     * Debit a wallet
     *
     * @param Request $request
     * @param         $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function debit(Request $request, $email)
    {
        try {
            $params = $request->all();

            $this->validateTransact($params);

            $this->wallet->debit($email, $params);
        } catch (WalletValidationException $e) {
            return $this->respondBadRequest($e->getMessage());
        } catch (\Exception $e) {
            return $this->respondInternalError();
        }
    }

    /**
     * Validate params when transacting a wallet
     *
     * @throws WalletValidationException
     */
    protected function validateTransact($params)
    {
        $validator = Validator::make($params, [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            throw new WalletValidationException($errors->first());
        }
    }
}
