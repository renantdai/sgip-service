<?php

namespace App\DTO;

use App\Enums\SMSStatus;
use App\Http\Requests\StoreSMSRequest;

class UpdateSMSDTO {

    public function __construct(
        public string $id,
        public SMSStatus $status,
    ) {
    }

    public static function makeFromRequest(StoreSMSRequest $request, $id = null): self {

        return new self(
            $id ?? $request->id,
            SMSStatus::E
        );
    }
}
