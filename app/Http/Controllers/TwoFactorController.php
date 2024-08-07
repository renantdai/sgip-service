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

        $userOTP = UserOTP::where('number', '=', $request->number)->orderBy('id', 'DESC')->first();
        if (!$userOTP) {
            $userOTP = new UserOTP();
        }
        $userOTP->google_secret = $secret;
        $userOTP->number = $request->number;
        $userOTP->save();

        $qrCodeUrl = $ga->getQRCodeGoogleUrl('SGIP-AUTH', $secret);

        $oneCode = $ga->getCode($secret);

        return [
            'error' => false,
            'secret' => $secret,
            'oneCode' => $oneCode,
            'url' => $qrCodeUrl
        ];
    }

    public function verifyCode(Request $request) {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $userOTP = UserOTP::where('number', '=', $request->number)->orderBy('id', 'DESC')->first();

        if (!$userOTP) {
            return [
                'error' => true,
                'msg' => 'Numero não encontrado'
            ];
        }

        if ($ga->verifyCode($userOTP->google_secret, $request->code, 40)) { // 10x30s = 5minutos
            return [
                'error' => false,
                'verifyCode' => true
            ];
        } else {
            return [
                'error' => true,
                'verifyCode' => false,
                'msg' => 'O código informado ou é inválido ou expirou'
            ];
        }
    }
}
