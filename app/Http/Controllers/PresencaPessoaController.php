<?php

namespace App\Http\Controllers;

use App\Http\Enums\TipoPresenca;
use App\Http\Services\PresencaPessoaService;
use Illuminate\Http\Request;

class PresencaPessoaController extends Controller
{
    protected $presencaPessoaService;

    public function __construct(PresencaPessoaService $presencaPessoaService)
    {
        $this->presencaPessoaService = $presencaPessoaService;
    }

    public function show(int $id) {

    }

    public function update(int $id) {

    }

    public function delete(int $id) {

    }

    public function listPresencasPessoa(int $pessoaId) {

    }

    public function filter(Request $request) {

    }
}
