<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use App\Http\Requests\NotebookRequest;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Notebook API Documentation",
 *      description="API Documentation for managing notebooks",
 *      @OA\Contact(
 *          email="mark3wich@yandex.ru"
 *      )
 * )
 */
class NotebookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/notebook",
     *     summary="Get list of notebooks",
     *     tags={"Notebooks"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of notebooks",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Notebook")
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=5),
     *             @OA\Property(property="total", type="integer", example=50)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $notebooks = Notebook::paginate(10);
        return response()->json($notebooks, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/notebook",
     *     summary="Create a new notebook",
     *     tags={"Notebooks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"full_name", "phone", "email"},
     *                 @OA\Property(property="full_name", type="string", description="Full name of the notebook owner"),
     *                 @OA\Property(property="email", type="string", description="Email of the notebook owner"),
     *                 @OA\Property(property="phone", type="string", description="Phone number of the notebook owner"),
     *                 @OA\Property(property="date_of_birth", type="string", format="date", description="Date of birth"),
     *                 @OA\Property(property="company", type="string", description="Company name"),
     *                 @OA\Property(
     *                     property="photo",
     *                     type="string",
     *                     format="binary",
     *                     description="Profile photo"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notebook created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Notebook")
     *     )
     * )
     */
    public function store(NotebookRequest $request)
    {
        $data = $request->validated();

        // Проверка, если файл изображения загружен
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('photos', 'public'); // Сохраняем в папке "storage/app/public/photos"
            $data['photo'] = $filePath; // Сохраняем путь к файлу
        }

        $notebook = Notebook::create($data);

        return response()->json($notebook, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/notebook/{id}",
     *     summary="Get a single notebook by ID",
     *     tags={"Notebooks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the notebook",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notebook details",
     *         @OA\JsonContent(ref="#/components/schemas/Notebook")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notebook not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Notebook not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $notebook = Notebook::find($id);

        if (!$notebook) {
            return response()->json(['error' => 'Notebook not found'], 404);
        }

        return response()->json($notebook, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/notebook/{id}",
     *     summary="Update an existing notebook",
     *     tags={"Notebooks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the notebook to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"full_name", "phone", "email"},
     *                 @OA\Property(property="full_name", type="string", description="Full name of the notebook owner"),
     *                 @OA\Property(property="email", type="string", description="Email of the notebook owner"),
     *                 @OA\Property(property="phone", type="string", description="Phone number of the notebook owner"),
     *                 @OA\Property(property="date_of_birth", type="string", format="date", description="Date of birth"),
     *                 @OA\Property(property="company", type="string", description="Company name"),
     *                 @OA\Property(
     *                     property="photo",
     *                     type="string",
     *                     format="binary",
     *                     description="New profile photo (optional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notebook updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Notebook")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notebook not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Notebook not found")
     *         )
     *     )
     * )
     */
    public function update(NotebookRequest $request, $id)
    {
        $notebook = Notebook::find($id);

        if (!$notebook) {
            return response()->json(['error' => 'Notebook not found'], 404);
        }

        $data = $request->validated();

        // Проверка, если новый файл изображения загружен
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $filePath;

            // Удаляем старый файл, если он существует
            if ($notebook->photo) {
                \Storage::disk('public')->delete($notebook->photo);
            }
        }

        $notebook->update($data);

        return response()->json($notebook, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/notebook/{id}",
     *     summary="Delete a notebook",
     *     tags={"Notebooks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the notebook to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notebook deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Notebook deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notebook not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Notebook not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $notebook = Notebook::find($id);

        if (!$notebook) {
            return response()->json(['error' => 'Notebook not found'], 404);
        }

        // Удаляем изображение пользователя, если оно существует
        if ($notebook->photo) {
            \Storage::disk('public')->delete($notebook->photo);
        }

        // Удаляем запись пользователя
        $notebook->delete();

        return response()->json(['message' => 'Notebook and associated photo deleted successfully'], 200);
    }
}
