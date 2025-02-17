<?php

namespace App\Console\Commands;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use Illuminate\Console\Command;

class CreateHashToSala extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:create-hash-to-sala';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Job usado para criar hash de cada sala. Com esse Hash, os usuários do ambiente Comum poderão marcar presença na EBD';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $salas = \App\Models\Sala::all();

        foreach ($salas as $sala) {
            $sala->hash = bin2hex(random_bytes(2));
            $sala->save();
        }

        return 0;
    }
}
