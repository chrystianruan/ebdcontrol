<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use Illuminate\Http\JsonResponse;

class EducationRestController extends Controller
{
    /**
     * Retorna a listagem completa de formações
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $formacoes = Formation::all();

        return response()->json([
            'success' => true,
            'data' => $formacoes
        ]);
    }

    /**
     * Retorna os dados de uma formação específica pelo ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $formacao = Formation::find($id);

        if (!$formacao) {
            return response()->json([
                'success' => false,
                'message' => 'Formação não encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $formacao
        ]);
    }
}
