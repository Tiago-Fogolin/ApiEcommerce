<?php

namespace App\Models;

use App\Models\Produtos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;

    protected $fillable = ['id_cliente', 'id_pagamento'];

    public function produtos()
    {
        return $this->belongsToMany(Produtos::class, 'produto_pedido', 'id_pedido', 'id_produto')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }

    public function pagamento()
    {
        return $this->hasOne(Pagamentos::class, 'id_pedido', 'id');
    }
}
