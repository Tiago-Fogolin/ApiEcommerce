<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/clientes",
     *     operationId="getClientes",
     *     tags={"Clientes"},
     *     summary="Lista todos os clientes",
     *     description="Retorna uma lista de todos os clientes cadastrados.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a solicitação"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $clientes = Clientes::all();

        return response()->json($clientes);
    }

    /**
     * @OA\Post(
     *     path="/clientes",
     *     summary="Cria um novo cliente",
     *     description="Cria um novo cliente com os dados fornecidos",
     *     operationId="createCliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "idade", "email", "cpf"},
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="idade", type="integer"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="cpf", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="idade", type="integer"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="cpf", type="string")
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
            'nome' => 'required|string|max:255',
            'idade' => 'required|integer',
            'email' => 'required|string|email|max:255|unique:clientes,email',
            'cpf' => 'required|string|size:11|unique:clientes,cpf'
        ]);

        $cliente = Clientes::create($registroValidado);

        return response()->json($cliente);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
