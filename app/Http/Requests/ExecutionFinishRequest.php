<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ExecutionFinishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'task_id' => 'required|integer',
            'finish_time' => 'required|date_format:'.config('app.format.datetime'),
            'succeed' => 'nullable',
            'failed' => 'nullable',
            'reason' => 'nullable',
            'log_path' => 'nullable',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
