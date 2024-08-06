<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOTP;
use Illuminate\Http\Request;
use PHPGangsta_GoogleAuthenticator;

class TwoFactorController extends Controller {
    public function generateSecret(Request $request) {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();

        $userOTP = new UserOTP();
        $userOTP->google_secret = $secret;
        $userOTP->number = $request->number;
        $userOTP->save();

        $qrCodeUrl = $ga->getQRCodeGoogleUrl('SGIP-AUTH', $secret);

        $oneCode = $ga->getCode($secret);

        return [
            'secret' => $secret,
            'oneCode' => $oneCode,
            'url' => $qrCodeUrl
        ];
    }

    public function verifyCode(Request $request) {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $userOTP = UserOTP::where('number', '=', $request->number)->first();

        if ($ga->verifyCode($userOTP->google_secret, $request->code, 2)) {
            // Código válido, permitir acesso
            return redirect()->intended('dashboard');
        } else {
            // Código inválido
            return back()->withErrors(['code' => 'Código inválido']);
        }
    }
}
