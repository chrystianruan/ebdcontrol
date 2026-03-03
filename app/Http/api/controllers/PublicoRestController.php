<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Models\Publico;
use Illuminate\Http\JsonResponse;

class PublicoRestController extends Controller
{
    /**
     * Retorna a listagem completa de públicos
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $publicos = Publico::all();

        return response()->json([
            'success' => true,
            'data' => $publicos
        ]);
    }

    /**
     * Retorna os dados de um público específico pelo ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $publico = Publico::find($id);

        if (!$publico) {
            return response()->json([
                'success' => false,
                'message' => 'Público não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $publico
        ]);
    }
}
