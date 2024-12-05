<?php

namespace App\Http\Controllers;

use App\Models\Pagamentos;
use Illuminate\Http\Request;

class PagamentosController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pagamentos",
     *     summary="Obtém todos os pagamentos",
     *     description="Retorna uma lista com todos os pagamentos cadastrados",
     *     operationId="getPagamentos",
     *     tags={"Pagamentos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pagamentos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="nome", type="string"),
     *                 @OA\Property(property="id_pedido", type="integer"),
     *                 @OA\Property(property="id_tipopagamento", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $pagamentos = Pagamentos::all();

        return response()->json($pagamentos);
    }
   
    /**
     * @OA\Post(
     *     path="/pagamentos",
     *     summary="Cria um novo pagamento",
     *     description="Cria um novo pagamento com os dados fornecidos",
     *     operationId="createPagamento",
     *     tags={"Pagamentos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_pedido", "id_tipopagamento"},
     *             @OA\Property(property="id_pedido", type="integer"),
     *             @OA\Property(property="id_tipopagamento", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pagamento criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="id_pedido", type="integer"),
     *             @OA\Property(property="id_tipopagamento", type="integer")
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
            'id_pedido' => 'required|integer|exists:pedidos,id',
            'id_tipopagamento' => 'required|integer|exists:tipo_pagamentos,id'
        ]);

        $pagamento = Pagamentos::create($registroValidado);

        return response()->json($pagamento);
    }
}
