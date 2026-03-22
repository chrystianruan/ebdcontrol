<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Models\Uf;
use Illuminate\Http\JsonResponse;

class StatesRestController extends Controller
{
    /**
     * Retorna a listagem completa de estados (UFs)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $estados = Uf::all();

        return response()->json([
            'success' => true,
            'data' => $estados
        ]);
    }

    /**
     * Retorna os dados de um estado específico pelo ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $estado = Uf::find($id);

        if (!$estado) {
            return response()->json([
                'success' => false,
                'message' => 'Estado não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $estado
        ]);
    }
}
