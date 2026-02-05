<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Models\PreCadastro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreCadastroRestController extends Controller
{
    public function getList(Request $request) : JsonResponse
    {
        $pessoas = PreCadastro::where('congregacao', '=', $request->congregacao_id);
        if ($request->sala) {
            $pessoas = $pessoas->where('classe', '=', $request->sala);
        }
        if ($request->nome) {
            $pessoas = $pessoas->where([['nome', 'like', '%' . $request->nome . '%']]);
        }
        $pessoas = $pessoas->paginate(10);

        return response()->json([
            'pessoas' => $pessoas
        ]);
    }
}
