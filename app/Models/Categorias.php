<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function produtos()
    {
        return $this->belongsToMany(Produtos::class, 'Produto_Categoria');
    }
}
