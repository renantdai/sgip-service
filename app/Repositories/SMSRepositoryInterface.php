<?php

namespace App\Repositories;

/* use App\DTO\Supports\CreateSupportDTO;
use App\DTO\Supports\UpdateSupportDTO; */

use App\DTO\CreateDTOSMS;
use App\DTO\UpdateSMSDTO;
use stdClass;

interface SMSRepositoryInterface {
    public function findOne(string $id): stdClass|null;
    public function new(CreateDTOSMS $dto): stdClass;
    public function update(UpdateSMSDTO $dto): stdClass | null;
    public function alterStatusSMS(CreateDTOSMS $id, $status);

}
