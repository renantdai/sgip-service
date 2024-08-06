<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule as ValidationRule;

class StoreSMSRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        $rules = [
            'country_code' => 'required|min:2|max:2',
            'number' => ['required', 'min:9', 'max:9'],
            'content' => ['required', 'min:1', 'max:255'],
            'type' => ['required', 'min:1', 'max:1']
        ];

        if ($this->method() === 'PUT'  || $this->method() === 'PATCH') { //verificar o que faz
            $id = $this->support ?? $this->id;
            $rules['cEQP'] = [
                'required',
                'min:3',
                'max:255',
                ValidationRule::unique('cams')->ignore($id),
            ];
        }
        return $rules;
    }
}
