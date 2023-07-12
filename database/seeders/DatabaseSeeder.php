<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $setores = ["Sede", "Setor 1", 'Setor 2', 'Setor 3', 'Setor 4'];
        $congregacoes = ["Templo Sede", "Boa Vista 1", "Boa Esperança", "Boa Vista 2", "Jardim América 1", "Jardim América 2"];
        $salas = ["Master", "Admin", "Maranata"];

        for ($i = 0; $i < count($setores); $i++) {
            \App\Models\Setor::factory()->create([
                'nome' => ''.$setores[$i],
            ]);
        }

        for ($i = 0; $i < count($congregacoes); $i++) {
            \App\Models\Congregacao::factory()->create([
                'nome' => ''.$congregacoes[$i],
                'setor_id' => rand(1, 5)

            ]);
        }

        for ($i = 0; $i < count($salas); $i++) {
            \App\Models\Sala::factory()->create([
                'nome' => ''.$salas[$i],
                'tipo' => 'Teste',
                'congregacao_id' => 1

            ]);
        }

        \App\Models\User::factory()->create([
            'name' => 'Chrystian Ruan',
            'username' => 'chrys.master',
            'password' => bcrypt('ebd@2003'),
            'remember_token' => Str::random(10),
            'id_nivel' => 1,
            'congregacao_id' => 1,
            'super_master' => true,
            'status' => 0,
        ]);

        //Funcoes

        \App\Models\Funcao::factory()->create([
            'nome' => 'Aluno',

        ]);

        \App\Models\Funcao::factory()->create([
            'nome' => 'Professor',

        ]);

        \App\Models\Funcao::factory()->create([
            'nome' => 'Secretário/Classe',

        ]);

        \App\Models\Funcao::factory()->create([
            'nome' => 'Secretário/Adm',

        ]);

        \App\Models\Funcao::factory()->create([
            'nome' => 'Superintendente',

        ]);


        //formations

        \App\Models\Formation::factory()->create([
            'nome' => 'Apenas o ensino infantil/nunca estudou',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Ensino Fundamental incompleto',

        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Ensino Fundamental',

        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Ensino Médio incompleto',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Ensino Médio',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Ensino Superior incompleto',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Ensino Superior',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Pós-Graduação incompleta',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Pós-Graduação',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Mestrado incompleto',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Mestrado',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Doutorado incompleto',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Doutorado',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Doutorado incompleto',
        ]);

        \App\Models\Formation::factory()->create([
            'nome' => 'Pós-Doutorado incompleto',
        ]);

        //publicos

        \App\Models\Publico::factory()->create([
            'nome' => 'Crianças - Maternal (até 4 anos de idade)',
        ]);

        \App\Models\Publico::factory()->create([
            'nome' => 'Crianças - Infantil (Entre 5 e 10 anos de idade)',
        ]);

        \App\Models\Publico::factory()->create([
            'nome' => 'Pré-Adolescentes',
        ]);

        \App\Models\Publico::factory()->create([
            'nome' => 'Adolescentes',
        ]);

        \App\Models\Publico::factory()->create([
            'nome' => 'Jovens',
        ]);

        \App\Models\Publico::factory()->create([
            'nome' => 'Adultos-Senhores',
        ]);

        \App\Models\Publico::factory()->create([
            'nome' => 'Adultos-Senhoras',
        ]);

        //ufs

        \App\Models\Uf::factory()->create([
            'nome' => 'AC'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'AL'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'AP'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'AM'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'BA'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'CE'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'DF'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'ES'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'GO'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'MA'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'MT'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'MS'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'MG'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'PA'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'PB'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'PR'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'PE'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'PI'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'RJ'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'RN'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'RS'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'RO'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'RR'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'SC'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'SP'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'SE'
        ]);
        \App\Models\Uf::factory()->create([
            'nome' => 'TO'
        ]);

        //cats

        \App\Models\Financeiro_cat::factory()->create([
            'nome' => 'Doação'
        ]);
        \App\Models\Financeiro_cat::factory()->create([
            'nome' => 'Revistas'
        ]);

        \App\Models\Financeiro_cat::factory()->create([
            'nome' => 'Outros'
        ]);
        \App\Models\Financeiro_cat::factory()->create([
            'nome' => 'Combustível'
        ]);

        \App\Models\Financeiro_cat::factory()->create([
            'nome' => 'Material-Escolar/EBD'
        ]);
        \App\Models\Financeiro_cat::factory()->create([
            'nome' => 'Material-Tecnológico/EBD'
        ]);

        \App\Models\Financeiro_cat::factory()->create([
            'nome' => 'Material-Escolar/Admin'
        ]);

        \App\Models\Financeiro_cat::factory()->create([
            'nome' => 'Material-Tecnológico/Admin'
        ]);

        //tipos

        \App\Models\Financeiro_tipo::factory()->create([
            'nome' => 'PIX'
        ]);

        \App\Models\Financeiro_tipo::factory()->create([
            'nome' => 'Espécie'
        ]);
        \App\Models\Financeiro_tipo::factory()->create([
            'nome' => 'Cartão de crédito'
        ]);

        \App\Models\Financeiro_tipo::factory()->create([
            'nome' => 'Cartão de débito'
        ]);

        //Financeiro

        \App\Models\Financeiro::factory()->create([
            'nome' => 'Entrada'
        ]);

        \App\Models\Financeiro::factory()->create([
            'nome' => 'Saída'
        ]);


    }
}
