<?php

namespace App\Http\Requests;

use App\Domain\Enums\MovieVersion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovieRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string'],
            'director' => ['required', 'string', 'max:255'],
            'genre' => ['required', 'string'],
            'cast' => ['required', 'string', 'max:255'],
            'version' => [Rule::enum(MovieVersion::class), 'required', 'string', 'max:255'],
            'running_time' => ['required', 'integer'],
            'release_date' => ['required', 'date'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:release_date'],
            'sneaky_show' => ['required', 'date', 'before_or_equal:release_date'],
        ];
    }
}
