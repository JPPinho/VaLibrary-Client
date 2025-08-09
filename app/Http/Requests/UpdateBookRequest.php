<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the users is authorized to make this request.
     */
    public function authorize(): bool
    {
        $book = $this->route('book');
        return $this->user()->can('update', $book);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $book = $this->route('book');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('book')->ignore($book->id)],
            'language_id' => 'required|exists:languages,id',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:author,id',
        ];
    }
}
