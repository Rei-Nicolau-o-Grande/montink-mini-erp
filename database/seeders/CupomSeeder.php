<?php

namespace Database\Seeders;

use App\Models\Cupom;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CupomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $validade = Carbon::now()->addWeeks(2);

        Cupom::create([
            'codigo' => 'DEZAO10',
            'desconto_percentual' => '10.00',
            'valor_minimo' => '0',
            'validade' => $validade,
            'ativo' => true,
        ]);

        Cupom::create([
            'codigo' => 'VINTAO20',
            'desconto_percentual' => '20.00',
            'valor_minimo' => '0',
            'validade' => $validade,
            'ativo' => true,
        ]);

        Cupom::create([
            'codigo' => 'CINQUENTAO50',
            'desconto_percentual' => '50.00',
            'valor_minimo' => '50',
            'validade' => $validade,
            'ativo' => true,
        ]);
    }
}
