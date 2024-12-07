<?php

namespace App\Console\Commands;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use Illuminate\Console\Command;

class DeleteHashToSala extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete-hash-to-sala';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Job usado para deletar hash de cada sala. Com esse Hash, os usuários do ambiente Comum poderão marcar presença na EBD';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $salas = \App\Models\Sala::all();
        //verificar se o dia é domingo ou dia de chamada acessando a tabela chamada_dia_congregacoes
        //se for, criar hash para cada sala
        if (date('w') == 0) {
            foreach ($salas as $sala) {
                $sala->hash = null;
                $sala->save();
            }
        } else {
            foreach ($salas as $sala) {
                $chamadaDiaCongregacaoRepository = new ChamadaDiaCongregacaoRepository();
                if ($chamadaDiaCongregacaoRepository->findChamadaDiaToday($sala->congregacao_id, date('Y-m-d'))) {
                    $sala->hash = null;
                    $sala->save();
                }
            }
        }
        return 0;
    }
}
