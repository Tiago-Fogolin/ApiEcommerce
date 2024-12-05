<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use Illuminate\Http\Request;

class PedidosController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pedidos",
     *     summary="Lista todos os pedidos",
     *     description="Retorna uma lista de pedidos.",
     *     operationId="getPedidos",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pedidos retornada com sucesso",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function index()
    {
        $pedidos = Pedidos::all();

        return response()->json($pedidos);
    }

    /**
     * @OA\Post(
     *     path="/pedidos",
     *     summary="Cria um novo pedido",
     *     description="Cria um pedido associado a um cliente.",
     *     operationId="createPedido",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_cliente", type="integer", example=1, description="ID do cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pedido criado com sucesso",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function create(Request $request)
    {
        
        $registroValidado = $request->validate([
            'id_cliente' => 'required|integer|exists:clientes,id'
        ]);

        $pedido = Pedidos::create($registroValidado);

        return response()->json($pedido, 201);
    }

    /**
     * @OA\Get(
     *     path="/pedidos/{pedido}",
     *     summary="Obtém os detalhes de um pedido",
     *     description="Retorna os detalhes de um pedido específico.",
     *     operationId="getPedidoById",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="pedido",
     *         in="path",
     *         description="ID do pedido a ser consultado",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do pedido retornados com sucesso",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido não encontrado"
     *     )
     * )
     */
    public function pedidoId($pedidoId) {
        $pedido = Pedidos::findOrFail($pedidoId);

        return response()->json($pedido);
    }

    /**
     * @OA\Delete(
     *     path="/pedidos/{pedido}",
     *     summary="Deleta um pedido",
     *     description="Deleta um pedido específica",
     *     operationId="deletePedido",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="pedido",
     *         in="path",
     *         description="ID do pedido a ser deletado",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro deletado com sucesso",
     *         @OA\JsonContent(type="string")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido não encontrado"
     *     )
     * )
     */
    public function destroy($pedidoId)
    {
        $pedido = Pedidos::with('pagamento')->findOrFail($pedidoId);

        if (!empty($pedido->pagamento)) {
            return response()->json([
                'message' => 'Não é possível deletar o pedido com pagamento concluído.'
            ], 403);
        }

        $pedido->delete();

        return response()->json('Registro deletado com sucesso');
    }

    /**
     * @OA\Post(
     *     path="/pedidos/{pedido}/produtos",
     *     summary="Adiciona produtos a um pedido",
     *     description="Adiciona uma lista de produtos ao pedido.",
     *     operationId="addProdutosToPedido",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="pedido",
     *         in="path",
     *         description="ID do pedido",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="produtos", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id_produto", type="integer", example=1),
     *                     @OA\Property(property="quantidade", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produtos adicionados ao pedido com sucesso",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function adicionarProdutos(Request $request, $pedidoId) {
        $registroValidado = $request->validate([
            'produtos' => 'required|array',
            'produtos.*.id_produto' => 'required|exists:produtos,id', 
            'produtos.*.quantidade' => 'required|integer|min:1'
        ]);

        $pedido = Pedidos::with('pagamento')->findOrFail($pedidoId);

        // Não posso adicionar um produto a um pedido com pagamento concluido
        if(!empty($pedido->pagamento)){
            return response()->json(['message' => 'Não é possível adicionar produtos a um pedido com pagamento concluído.'], 403);
        }

        foreach ($registroValidado['produtos'] as $dadosProduto) {
            $pedido->produtos()->attach($dadosProduto['id_produto'], ['quantidade' => $dadosProduto['quantidade']]);
        }
        return response()->json(['message' => 'Produtos adicionados ao pedido com sucesso!'], 201);
    }

    /**
     * @OA\Delete(
     *     path="/pedidos/{pedido}/produtos",
     *     summary="Remove um produto do pedido",
     *     description="Remove um produto específico de um pedido.",
     *     operationId="deleteProdutoFromPedido",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="pedido",
     *         in="path",
     *         description="ID do pedido",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_produto", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto deletado com sucesso",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado no pedido"
     *     )
     * )
     */
    public function deletarProduto(Request $request, $pedidoId) {
        $registroValidado = $request->validate([
            'id_produto' => 'required|exists:produtos,id',
        ]);
    
        $pedido = Pedidos::with('pagamento')->findOrFail($pedidoId);


        // Não posso deletar um produto de um pedido com pagamento concluido
        if(!empty($pedido->pagamento)){
            return response()->json(['message' => 'Não é possível remover produtos de um pedido com pagamento concluído.'], 403);
        }

        $produto = $pedido->produtos()->where('produtos.id', $registroValidado['id_produto'])->first();

        if ($produto) {
            $pedido->produtos()->detach($registroValidado['id_produto']);

            return response()->json(['message' => 'Produto deletado do pedido com sucesso!'], 200);
        }

    return response()->json(['message' => 'Produto não encontrado no pedido.'], 404);
    }

    /**
     * @OA\Get(
     *     path="/pedidos/{pedidoId}/produtos",
     *     summary="Lista os produtos de um pedido",
     *     description="Retorna a lista de produtos associados a um pedido específico.",
     *     operationId="getProdutosPorPedido",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="pedidoId",
     *         in="path",
     *         description="ID do pedido",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos retornada com sucesso",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Produto"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido não encontrado"
     *     )
     * )
     */
    public function produtos($pedidoId) {
        $pedido = Pedidos::findOrFail($pedidoId);

        $produtos = $pedido->produtos;
        
        $produtos->makeHidden('pivot');

        return response()->json($produtos);
    }

    
}
