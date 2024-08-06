<?php

use App\Http\Controllers\SMSController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::namespace('API')->name('api.')->group(function () {
    Route::middleware('api')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });

    Route::post('/sms', [SMSController::class, 'store']);
    Route::post('/sms/id', [SMSController::class, 'send']);
    Route::post('/sms/envia', [SMSController::class, 'storeAndSend']);

    Route::get('/2fa/setup', [TwoFactorController::class, 'generateSecret'])->name('2fa.setup');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verifyCode'])->name('2fa.verify');

    Route::post('/sms/token/enviar', [SMSController::class, 'sendToken']);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->only(['email', 'password']);

    if (!$token = auth()->attempt($credentials)) {
        abort(401, 'Não autorizado');
    }

    return response()->json([
        'data' => [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]
    ]);
});
