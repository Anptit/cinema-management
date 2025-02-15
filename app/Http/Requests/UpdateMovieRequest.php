<?php

namespace App\Http\Requests;

use App\Domain\Enums\MovieVersion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMovieRequest extends FormRequest
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
            'name' => ['string', 'max:255'],
            'description' => ['string'],
            'director' => ['string', 'max:255'],
            'genre' => ['string'],
            'cast' => ['string', 'max:255'],
            'version' => [Rule::enum(MovieVersion::class), 'string', 'max:255'],
            'running_time' => ['integer'],
            'release_date' => ['date'],
            'start_date' => ['date'],
            'end_date' => ['date', 'after:release_date'],
            'sneaky_show' => ['date', 'before:release_date'],
        ];
    }
}
