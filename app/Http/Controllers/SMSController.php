<?php

namespace App\Http\Controllers;

use App\DTO\CreateDTOSMS;
use App\Http\Requests\SendSMSRequest;
use App\Services\SMSService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SMSController extends Controller {
    public function __construct(protected SMSService $service) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            $dto = CreateDTOSMS::makeFromRequest($request);

            return $this->service->new($dto);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => 'Houve um erro na criação do DTO',
                'warning' => $e->getMessage()
            ], Response::HTTP_OK);
        }
    }

    public function send(SendSMSRequest $request) {
        try {
            $return = $this->service->send($request->id);

            if (!$return['success']) {
                return response()->json([
                    'error' => 'true',
                    'id' => $request->id,
                    'msg' => $return['responseDescription '],
                    'cStat' => isset($return['responseCode']) ? $return['responseCode'] : '0'
                ], Response::HTTP_BAD_REQUEST);
            }

            return $return;
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => 'Houve um erro no envio',
                'warning' => $e->getMessage()
            ], Response::HTTP_OK);
        }
    }

    public function storeAndSend(Request $request) {
        try {
            $dto = CreateDTOSMS::makeFromRequest($request);

            return $this->service->storeAndSend($dto);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => 'Houve um erro na criação do DTO',
                'warning' => $e->getMessage()
            ], Response::HTTP_OK);
        }
    }

    /**
     * Display the specified resource.
     */
    public function sendToken(Request $request) {
        if (!$request->orige || !$request->number) {
            return response()->json([
                'error' => true,
                'msg' => 'Parametros vazio'
            ], Response::HTTP_OK);
        }

        try {

            return $this->service->sendToken($request);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => 'Houve um erro ao gerar e enviar o token',
                'warning' => $e->getMessage()
            ], Response::HTTP_OK);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
