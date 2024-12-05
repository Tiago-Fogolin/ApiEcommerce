<?php

namespace App\Http\Controllers;

use App\Models\TipoPagamento;
use Illuminate\Http\Request;

class TipoPagamentoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/tipopagamento",
     *     summary="Obtém todos os tipos de pagamento",
     *     description="Retorna uma lista com todos os tipos de pagamento cadastradas",
     *     operationId="getTipoPagamento",
     *     tags={"TipoPagamento"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tipos de pagamento",
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
        $tipoPagamentos = TipoPagamento::all();

        return response()->json($tipoPagamentos);
    }

    /**
     * @OA\Post(
     *     path="/tipopagamento",
     *     summary="Cria um novo tipo de pagamento",
     *     description="Cria um novo tipo de pagamento com o nome fornecido",
     *     operationId="createTipoPagamento",
     *     tags={"TipoPagamento"},
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
     *         description="Tipo de Pagamento criado com sucesso",
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

        $tipoPagamento = TipoPagamento::create($registroValidado);

        return response()->json($tipoPagamento, 201);
    }
}
