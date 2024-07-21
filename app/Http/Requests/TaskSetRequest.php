<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskSetRequest extends FormRequest
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
            'name' => 'nullable|string|max:20',
            'link' => 'required|string|max:255',
            'plan_id' => 'nullable|exists:plans,id',
            'start_time' => 'nullable|date_format:'.config('app.format.date'),
            'end_time' => 'nullable|date_format:'.config('app.format.date').'|after:start_time',
            'active' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '名称',
            'link' => 'リンク',
            'plan' => 'プラン',
            'start_time' => '実行日程開始日',
            'end_time' => '実行日程終了日',
            'active' => '状態',
        ];
    }
}
