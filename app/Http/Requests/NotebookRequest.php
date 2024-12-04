<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="NotebookRequest",
 *     type="object",
 *     required={"full_name", "phone", "email"},
 *     properties={
 *         @OA\Property(property="full_name", type="string", example="John Doe"),
 *         @OA\Property(property="company", type="string", example="Tech Corp"),
 *         @OA\Property(property="phone", type="string", example="1234567890"),
 *         @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *         @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *         @OA\Property(property="photo", type="string", format="url", example="https://example.com/photo.jpg"),
 *     }
 * )
 */
class NotebookRequest extends FormRequest
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
            'full_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'date_of_birth' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}
