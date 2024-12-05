<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateUserToken extends Command
{
    /**
     * O nome e assinatura do comando.
     *
     * @var string
     */
    protected $signature = 'user:generate-token';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Gerar um token';

    /**
     * Execute o comando.
     *
     * @return void
     */
    public function handle()
    {
    
        // Se não houver um user_id, cria um novo usuário
        $user = \App\Models\User::firstOrCreate(
            [
                'name' => 'ApiUser', 
                'email' => 'usuario@example.com'
            ],
            [
                'password' => bcrypt('senhaaleatoria')
            ]
        );

        // Gerar o token
        $token = $user->createToken('API Token')->plainTextToken;

        $tokenTexto = explode('|', $token)[1]; 

        $this->line("Token: {$tokenTexto}");
    }
}
