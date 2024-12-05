<?php

namespace App\Http\Controllers;

use App\Models\Produtos;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    /**
     * @OA\Get(
     *     path="/produtos",
     *     summary="Exibe uma lista de produtos com filtros opcionais",
     *     description="Retorna uma lista de produtos com base nos filtros fornecidos (nome, descrição, preço, estoque)",
     *     operationId="getProdutos",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="nome",
     *         in="query",
     *         description="Filtra produtos pelo nome",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="descricao",
     *         in="query",
     *         description="Filtra produtos pela descrição",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="preco",
     *         in="query",
     *         description="Filtra produtos pelo preço exato",
     *         required=false,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="menorPreco",
     *         in="query",
     *         description="Filtra produtos com preço maior ou igual a esse valor",
     *         required=false,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="maiorPreco",
     *         in="query",
     *         description="Filtra produtos com preço menor ou igual a esse valor",
     *         required=false,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="estoque",
     *         in="query",
     *         description="Filtra produtos pelo estoque",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos filtrada",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="nome", type="string"),
     *                 @OA\Property(property="descricao", type="string"),
     *                 @OA\Property(property="preco", type="number", format="float"),
     *                 @OA\Property(property="estoque", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $request->validate([
            'nome' => 'nullable|string|max:255',
            'descricao' => 'nullable|string|max:255',
            'preco' => 'nullable|numeric|min:0',
            'menorPreco' => 'nullable|numeric|min:0',
            'maiorPreco' => 'nullable|numeric|min:0',
            'estoque' => 'nullable|integer|min:0'
        ]);

        $produtos = Produtos::query();

        if ($request->has('nome') && $request->nome != '') {
            $produtos->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->has('descricao') && $request->descricao != '') {
            $produtos->where('descricao', 'like', '%' . $request->descricao . '%');
        }

        if ($request->has('preco') && $request->preco != '') {
            $produtos->where('preco', $request->preco);
        }

        if ($request->has('menorPreco') && $request->menorPreco != '') {
            $produtos->where('preco', '>=', $request->menorPreco);
        }

        if ($request->has('maiorPreco') && $request->maiorPreco != '') {
            $produtos->where('preco', '<=', $request->maiorPreco);
        }

        if ($request->has('estoque') && $request->estoque != '') {
            $produtos->where('estoque', $request->estoque);
        }

        $produtos = $produtos->get();

        return response()->json($produtos);
    }


    /**
     * @OA\Get(
     *     path="/produtos/{produto}",
     *     summary="Obtém os detalhes de um produto",
     *     description="Retorna as informações do produto especificado pelo ID.",
     *     operationId="getProdutoById",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="produto",
     *         in="path",
     *         description="ID do produto a ser consultado",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do produto retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nome", type="string", example="Produto A"),
     *             @OA\Property(property="descricao", type="string", example="Descrição do Produto A"),
     *             @OA\Property(property="preco", type="number", format="float", example=99.99),
     *             @OA\Property(property="estoque", type="integer", example=50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produto não encontrado.")
     *         )
     *     )
     * )
     */
    public function produtoId($produtoId)
    {
        $produto = Produtos::findOrFail($produtoId);

        return response()->json($produto);
    }

    /**
     * @OA\Get(
     *     path="/produtos/{produto}/categorias",
     *     summary="Obtém as categorias associadas a um produto",
     *     description="Retorna uma lista de categorias vinculadas ao produto especificado pelo ID.",
     *     operationId="getCategoriasProduto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="produto",
     *         in="path",
     *         description="ID do produto cujas categorias serão listadas",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorias retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Categoria 1"),
     *                 @OA\Property(property="descricao", type="string", example="Descrição da categoria")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produto não encontrado.")
     *         )
     *     )
     * )
     */
    public function categorias($produtoId)
    {
        $produto = Produtos::findOrFail($produtoId);

        $categorias = $produto->categorias;

        $categorias->makeHidden('pivot');

        return response()->json($categorias);
    }


    /**
     * @OA\Post(
     *     path="/produtos/{produto}/categorias",
     *     summary="Adiciona categorias a um produto",
     *     description="Adiciona uma ou mais categorias a um produto existente, vinculado pelo ID do produto.",
     *     operationId="adicionarCategorias",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="produto",
     *         in="path",
     *         description="ID do produto ao qual as categorias serão adicionadas",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"categorias"},
     *             @OA\Property(
     *                 property="categorias",
     *                 type="array",
     *                 description="Array de categorias a serem adicionadas",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id_categoria"},
     *                     @OA\Property(
     *                         property="id_categoria",
     *                         type="integer",
     *                         description="ID de uma categoria existente",
     *                         example=1
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categorias adicionadas com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Categorias adicionadas ao produto com sucesso!"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produto não encontrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na validação dos dados enviados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Validation failed.")
     *         )
     *     )
     * )
     */
    public function adicionarCategorias(Request $request, $produtoId)
    {
        $registroValidado = $request->validate([
            'categorias' => 'required|array',
            'categorias.*.id_categoria' => 'required|exists:categorias,id'
        ]);

        $produto = Produtos::findOrFail($produtoId);

        foreach ($registroValidado['categorias'] as $dadosCategoria) {
            $produto->categorias()->attach($dadosCategoria['id_categoria']);
        }
        return response()->json(['message' => 'Categorias adicionadas ao produto com sucesso!'], 201);
    }

    /**
     * @OA\Post(
     *     path="/produtos",
     *     summary="Cria um novo produto",
     *     description="Cria um produto com os dados fornecidos.",
     *     operationId="createProduto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "descricao", "preco"},
     *             @OA\Property(property="nome", type="string", description="Nome do produto", example="Produto X"),
     *             @OA\Property(property="descricao", type="string", description="Descrição do produto", example="Descrição do produto X"),
     *             @OA\Property(property="preco", type="number", format="float", description="Preço do produto", example="19.99"),
     *             @OA\Property(property="estoque", type="integer", description="Quantidade de estoque do produto", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", description="ID do produto", example=1),
     *             @OA\Property(property="nome", type="string", description="Nome do produto", example="Produto X"),
     *             @OA\Property(property="descricao", type="string", description="Descrição do produto", example="Descrição do produto X"),
     *             @OA\Property(property="preco", type="number", format="float", description="Preço do produto", example="19.99"),
     *             @OA\Property(property="estoque", type="integer", description="Quantidade de estoque do produto", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na validação dos dados enviados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Mensagem de erro", example="Validation failed")
     *         )
     *     )
     * )
     */
    public function create(Request $request)
    {
        $registroValidado = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'preco' => [
                'required',
                'numeric',
                'regex:/^\d{1,6}(\.\d{1,2})?$/'
            ],
            'estoque' => 'integer'
        ]);

        $produto = Produtos::create($registroValidado);

        return response()->json($produto);
    }

}
