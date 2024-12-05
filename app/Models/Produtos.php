<?php

namespace App\Models;

use App\Models\Pedidos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Produto",
 *     type="object",
 *     title="Produto",
 *     description="Representação de um produto",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do produto"
 *     ),
 *     @OA\Property(
 *         property="nome",
 *         type="string",
 *         description="Nome do produto"
 *     ),
 *     @OA\Property(
 *         property="descricao",
 *         type="string",
 *         description="Descrição do produto"
 *     ),
 *     @OA\Property(
 *         property="preco",
 *         type="number",
 *         format="float",
 *         description="Preço do produto"
 *     ),
 *     @OA\Property(
 *         property="estoque",
 *         type="integer",
 *         description="Quantidade em estoque do produto"
 *     )
 * )
 */
class Produtos extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'preco', 'estoque'];

    public function pedidos()
    {
        return $this->belongsToMany(Pedidos::class, 'produto_pedido',  'id_produto', 'id_pedido')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }

    public function categorias()
    {
        return $this->belongsToMany(Produtos::class, 'produto_categoria', 'id_produto', 'id_categoria')
                    ->withTimestamps();
    }
}
