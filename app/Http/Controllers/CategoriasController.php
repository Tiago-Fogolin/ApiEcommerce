<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categorias",
     *     summary="Obtém todas as categorias",
     *     description="Retorna uma lista com todas as categorias cadastradas",
     *     operationId="getCategorias",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorias",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="nome", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categorias = Categorias::all();

        return response()->json($categorias);
    }

    /**
     * @OA\Post(
     *     path="/categorias",
     *     summary="Cria uma nova categoria",
     *     description="Cria uma nova categoria com o nome fornecido",
     *     operationId="createCategoria",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome"},
     *             @OA\Property(property="nome", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoria criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="nome", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na validação dos dados fornecidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function create(Request $request)
    {
        $registroValidado = $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        $categoria = Categorias::create($registroValidado);

        return response()->json($categoria, 201);
    }
}
