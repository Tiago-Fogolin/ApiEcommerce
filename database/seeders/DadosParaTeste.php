<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clientes;
use App\Models\Categorias;
use App\Models\Produtos;
use App\Models\Pedidos;
use App\Models\Produto_Pedido;
use App\Models\TipoPagamento;


class DadosParaTeste extends Seeder
{
    /**
     * Run the database seeds.
     *
     *
     */
    public function run()
    {
        // Criando 3 clientes
        Clientes::create([
            'nome' => 'João Silva', 
            'idade' => 30, 
            'email' => 'joao@exemplo.com',
            'cpf' => '12345678901'
        ]);

        Clientes::create([
            'nome' => 'Maria Oliveira', 
            'idade' => 28, 
            'email' => 'maria@exemplo.com',
            'cpf' => '98765432100'
        ]);

        Clientes::create([
            'nome' => 'Carlos Souza', 
            'idade' => 35, 
            'email' => 'carlos@exemplo.com',
            'cpf' => '11122334455'
        ]);
        
        // Criando 3 categorias
        $categoria1 = Categorias::create(['nome' => 'Eletrônicos']);
        $categoria2 = Categorias::create(['nome' => 'Roupas']);
        $categoria3 = Categorias::create(['nome' => 'Alimentos']);
        
        // Criando 3 produtos
        $produto1 = Produtos::create([
            'nome' => 'Smartphone X', 
            'preco' => 1500.00, 
            'descricao' => 'Smartphone com 128GB de armazenamento e câmera de 48MP'
        ]);
        $produto2 = Produtos::create([
            'nome' => 'Tablet Y', 
            'preco' => 900.00, 
            'descricao' => 'Tablet com tela de 10 polegadas e 64GB de armazenamento'
        ]);
        $produto3 = Produtos::create([
            'nome' => 'Camiseta P', 
            'preco' => 40.00, 
            'descricao' => 'Camiseta de algodão tamanho P, disponível em várias cores'
        ]);

        // Associando categorias
        $produto1->categorias()->attach($categoria1->id);
        $produto2->categorias()->attach($categoria1->id);
        $produto3->categorias()->attach($categoria2->id);

        // Criando 3 pedidos
        $pedido1 = Pedidos::create(['id_cliente' => 1]);
        $pedido2 = Pedidos::create(['id_cliente' => 2]);
        $pedido3 = Pedidos::create(['id_cliente' => 3]);

        // Associando produtos aos pedidos
        $pedido1->produtos()->attach($produto1->id, ['quantidade' => 1]);
        $pedido1->produtos()->attach($produto2->id, ['quantidade' => 2]);

        $pedido2->produtos()->attach($produto2->id, ['quantidade' => 1]);
        $pedido2->produtos()->attach($produto3->id, ['quantidade' => 3]);

        $pedido3->produtos()->attach($produto1->id, ['quantidade' => 1]);

        // Criando 3 tipos de pagamento
        TipoPagamento::create(['nome' => 'Cartão de Crédito']);
        TipoPagamento::create(['nome' => 'Boleto Bancário']);
        TipoPagamento::create(['nome' => 'Pix']);
    }
}
