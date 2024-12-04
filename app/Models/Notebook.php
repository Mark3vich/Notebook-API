<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Notebook",
 *     type="object",
 *     title="Notebook",
 *     description="Notebook Model",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="full_name", type="string", example="John Doe"),
 *         @OA\Property(property="company", type="string", example="Tech Corp"),
 *         @OA\Property(property="phone", type="string", example="1234567890"),
 *         @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *         @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *         @OA\Property(property="photo", type="string", format="url", example="https://example.com/photo.jpg")
 *     }
 * )
 */

class Notebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'company',
        'phone',
        'email',
        'date_of_birth',
        'photo',
    ];
}
