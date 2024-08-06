<?php

namespace App\Enums;

enum SMSStatus: string {
    case C = "Cadastramento";
    case E = "Enviado";
    case F = "Falha";
    case R = "Reativação";

    public static function fromValue(string $name): string {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status->value;
            }
        }

        throw new \ValueError("$status is not a valid");
    }

    public static function sendFromValue($name): string {
        $values = [
            'C' => 1,
            'E' => 2,
            'F' => 3
        ];
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $values[$status->name];
            }
        }

        throw new \ValueError("$status is not a valid from send");
    }
}
