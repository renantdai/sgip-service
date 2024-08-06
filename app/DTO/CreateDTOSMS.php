<?php


namespace App\DTO;

use App\Enums\SMSStatus;

class CreateDTOSMS {
    public function __construct(
        public $id,
        public $country_code,
        public $number,
        public $content,
        public string $type,
        public string $orige,
        public SMSStatus $status_send,
    ) {
    }

    public static function makeFromRequest($request): self {
        return new self(
            $request->id ?? 0,
            $request->country_code ?? 55,
            $request->number,
            $request->content,
            $request->type ?? 0,
            $request->orige,
            SMSStatus::C
        );
    }

}
