<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\PagamentosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\TipoPagamentoController;

// Endpoints de categorias
Route::middleware('auth:sanctum')->get('/categorias', [CategoriasController::class, 'index']);
Route::middleware('auth:sanctum')->post('/categorias', [CategoriasController::class, 'create']);

// Endpoints de tipos de pagamento
Route::middleware('auth:sanctum')->get('/tipopagamento', [TipoPagamentoController::class, 'index']);
Route::middleware('auth:sanctum')->post('/tipopagamento', [TipoPagamentoController::class, 'create']);

// Endpoints de pagamentos
Route::middleware('auth:sanctum')->get('/pagamentos', [PagamentosController::class, 'index']);
Route::middleware('auth:sanctum')->post('/pagamentos', [PagamentosController::class, 'create']);

// Endpoints de clientes
Route::middleware('auth:sanctum')->get('/clientes', [ClientesController::class, 'index']);
Route::middleware('auth:sanctum')->post('/clientes', [ClientesController::class, 'create']);

// Endpoints de produtos
Route::middleware('auth:sanctum')->get('/produtos', [ProdutosController::class, 'index']);
Route::middleware('auth:sanctum')->post('/produtos', [ProdutosController::class, 'create']);

Route::middleware('auth:sanctum')->get('/produtos/{produto}', [ProdutosController::class, 'produtoId']);
Route::middleware('auth:sanctum')->get('/produtos/{produto}/categorias', [ProdutosController::class, 'categorias']);
Route::middleware('auth:sanctum')->post('/produtos/{produto}/categorias', [ProdutosController::class, 'adicionarCategorias']);

// Endpoints de pedidos
Route::middleware('auth:sanctum')->get('/pedidos', [PedidosController::class, 'index']);
Route::middleware('auth:sanctum')->post('/pedidos', [PedidosController::class, 'create']);

Route::middleware('auth:sanctum')->get('/pedidos/{pedido}', [PedidosController::class, 'pedidoId']);
Route::middleware('auth:sanctum')->delete('/pedidos/{pedido}', [PedidosController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/pedidos/{pedido}/produtos', [PedidosController::class, 'produtos']);
Route::middleware('auth:sanctum')->post('/pedidos/{pedido}/produtos', [PedidosController::class, 'adicionarProdutos']);
Route::middleware('auth:sanctum')->delete('/pedidos/{pedido}/produtos', [PedidosController::class, 'deletarProduto']);
