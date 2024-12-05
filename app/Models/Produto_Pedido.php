<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto_Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['id_pedido', 'id_produto'];
}
