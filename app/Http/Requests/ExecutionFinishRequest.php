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
            'process_id' => 'required|string',
            'finish_time' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required|in:stopped',
            'finished_as' => 'required|in:normal,schedule,error',
            'statistic' => 'required|array:success,failure,cost',
            'statistic.success' => 'required|integer',
            'statistic.failure' => 'required|integer',
            'statistic.cost' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
