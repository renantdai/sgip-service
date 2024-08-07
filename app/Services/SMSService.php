<?php

namespace App\Services;

use App\DTO\CreateDTOSMS;
use App\DTO\UpdateSMSDTO;
use App\Enums\SMSStatus;
use App\Http\Controllers\TwoFactorController;
use App\Http\Requests\StoreSMSRequest;
use App\Models\SMS;
use App\Repositories\SMSRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

class SMSService {
    public function __construct(
        protected SMSRepositoryInterface $repository
    ) {
    }

    public function new(CreateDTOSMS $dto): stdClass {
        return $this->repository->new($dto);
    }

    public function send(string $id) {
        $sms = $this->repository->findOne($id);
        if (!$sms) {
            return ['error' => true, 'msg' => 'nao existe esse id'];
        }

        $validateDto = new StoreSMSRequest((array) $sms);
        $dto = CreateDTOSMS::makeFromRequest($validateDto);

        return $this->sendServiceSMS($dto);
    }

    public function update(UpdateSMSDTO $dto): stdClass|null {
        return $this->repository->update($dto);
    }

    public function sendServiceSMS(CreateDTOSMS $dto) {
/*         $mock = json_decode('{"success":true,"responseCode":"000","responseDescription":"Success queued","credit":1,"balance":"7","id":"6"}', true);
        return $this->parseResponse($dto, $mock); */

        $response = Http::withBasicAuth('renantdai', 'Renankonrath22')
            ->get('https://api.smsmarket.com.br/webservice-rest/send-single', [
                'type' => $dto->type,
                'country_code' => (int) $dto->country_code,
                'number' => (int) $dto->number,
                'content' => $dto->content,
                'campaign_id' => $dto->id
            ]);

        $responseData = '';

        if ($response->successful()) {
            // A requisição foi bem-sucedida
            $responseData = $response->json();
            // Processar a resposta da API
        } else {
            // Ocorreu um erro na requisição
            $responseData = $response->json()['message'];
        }

        return $this->parseResponse($dto, $responseData);
    }

    private function parseResponse($dto, $response) {
        if ($response['success']) {
            return ['success' => true, 'data' => $this->repository->alterStatusSMS($dto, SMSStatus::E)];
        }

        return $response; //criar validação de erro
    }

    public function storeAndSend(CreateDTOSMS $dto) {
        $sms = $this->new($dto);

        return $this->send($sms->id);
    }

    public function sendToken(Request $request) {
        $twoFA = new TwoFactorController();
        $secret = $twoFA->generateSecret($request);
        if ($secret['error']) {
            return $secret;
        }
        $request['content'] = "Seu codigo {$secret['oneCode']} de autenticacao no sistema SGIP";
        $dto = CreateDTOSMS::makeFromRequest($request);

        return $this->storeAndSend($dto);
    }
}
