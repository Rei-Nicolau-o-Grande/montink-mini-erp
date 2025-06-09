<?php

namespace Database\Seeders;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdutoVariacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produto = Produto::create([
            'nome' => 'Camisa do Vasco da Gama',
            'preco' => 300,
            'ativo' => true,
        ]);

        $variacoes = ['P', 'M', 'G', 'GG', 'XGG'];

        foreach ($variacoes as $variacao) {
            Estoque::create([
                'produto_id' => $produto->id,
                'variacao' => $variacao,
                'quantidade' => 10,
                'ativo' => true,
            ]);
        }
    }
}
