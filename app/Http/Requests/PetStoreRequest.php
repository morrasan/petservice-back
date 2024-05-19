<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PetStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'category_id' => 'integer|exists:categories,id',
            'status' => 'string|in:' . implode(',', ['available', 'pending', 'sold']),
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,tiff,bmp|max:5120',
        ];
    }
}
