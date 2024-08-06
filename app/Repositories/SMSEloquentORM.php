<?php

namespace App\Repositories;

use App\DTO\CreateDTOSMS;
use App\DTO\UpdateSMSDTO;
use App\Models\SMS;
use stdClass;

class SMSEloquentORM implements SMSRepositoryInterface {
    public function __construct(
        protected SMS $model
    ) {
    }

    public function findOne(string $id): stdClass |null {
        $sms =  $this->model->find($id);
        if (!$sms) {
            return null;
        }

        return (object) $sms->toArray();
    }

    public function new(CreateDTOSMS $dto): stdClass {
        $sms =  $this->model->create(
            (array) $dto
        );

        return (object) $sms->toArray();
    }

    public function update(UpdateSMSDTO $dto): stdClass | null {
        if (!$sms = $this->model->find($dto->id)) {
            return null;
        }

        $sms->update(
            (array) $dto
        );

        return (object) $sms->toArray();
    }

    public function alterStatusSMS(CreateDTOSMS $dto, $status) {
        return ($this->model->where('id', $dto->id)->update(['status_send' => $status]));
    }
}
