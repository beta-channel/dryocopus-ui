<?php

namespace App\Http\Requests;

use App\Exceptions\InvalidParametersException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ExecutionStartRequest extends FormRequest
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
            'task_id' => 'required|integer',
            'task_name' => 'nullable|string',
            'link' => 'required|string',
            'plan' => 'required|json',
            'start_time' => 'required|date_format:'.config('app.format.datetime'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
    }
}
