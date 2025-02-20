<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowtimeRequest extends FormRequest
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
            'show_time' => ['required', 'date_format:H:i:s', 'unique:showtimes,show_time'],
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
        ];
    }
}
