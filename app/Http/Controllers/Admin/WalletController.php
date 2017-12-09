<?php
/**
 * Class WalletController
 */

namespace App\Http\Controllers\Admin;

use App\Exceptions\Wallet\WalletNotFoundException;
use App\Exceptions\Wallet\WalletValidationException;
use App\Services\WalletService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class WalletController
 *
 * Handles Wallet admin related actions. Injects WalletService to handle the business logic.
 */
class WalletController extends Controller
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
     * Returns all wallets
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $wallets = $this->wallet->all();

            return response()->json([
                'data' => $wallets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Creates a new walet
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $params = $request->all();

            $this->validateStore($params);

            $wallet = $this->wallet->create($params);

            return response()->json([
                'data' => $wallet
            ]);
        } catch (WalletValidationException $e) {
            return response()->json([
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate params when storing/creating wallet
     *
     * @param $params
     * @throws WalletValidationException
     */
    protected function validateStore($params)
    {
        $validator = Validator::make($params, [
            'email' => 'required|email|unique:wallets',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            throw new WalletValidationException($errors->first());
        }
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

            return response()->json([
                'data' => $wallet
            ]);
        } catch (WalletNotFoundException $e) {
            return response()->json([
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete (soft) a wallet
     *
     * @param  int $email
     * @return \Illuminate\Http\Response
     */
    public function destroy($email)
    {
        try {
            $this->wallet->delete($email);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
