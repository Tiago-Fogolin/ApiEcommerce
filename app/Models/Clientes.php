<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Cliente",
 *     type="object",
 *     title="Cliente",
 *     description="Modelo de Cliente",
 *     required={"nome", "email", "cpf"},
 *     @OA\Property(
 *         property="nome",
 *         type="string",
 *         description="Nome do cliente"
 *     ),
 *     @OA\Property(
 *         property="idade",
 *         type="integer",
 *         description="Idade do cliente"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Email do cliente"
 *     ),
 *     @OA\Property(
 *         property="cpf",
 *         type="string",
 *         description="CPF do cliente (apenas números)"
 *     )
 * )
 */
class Clientes extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'idade', 'email', 'cpf'];
}
