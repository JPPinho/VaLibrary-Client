<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the users is authorized to make this request.
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
        if ($this->filled('name')) {
            return [
                'name' => 'required|string|max:255',
                'language_id' => 'required|exists:languages,id',
                'authors' => 'required|array|min:1',
                'authors.*' => 'exists:author,id',
            ];
        } elseif ($this->hasFile('images')) {
            return [
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        }

        return [];
    }
}
