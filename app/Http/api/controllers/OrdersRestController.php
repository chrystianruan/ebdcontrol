<?php

namespace App\Http\api\controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrdersRestController extends Controller
{
    // 🔹 Listar todos
    public function index()
    {
        return response()->json(
            Order::all()->pluck('data')
        );
    }

    // 🔹 Buscar por ID
    public function show($id)
    {
        $order = Order::findOrFail($id);

        return response()->json($order->data);
    }

    // 🔹 Criar
    public function store(Request $request)
    {
        $data = $request->all();

        $id = $data['id'] ?? Str::uuid()->toString();

        $order = Order::create([
            'id' => $id,
            'data' => $data
        ]);

        return response()->json($order->data, 201);
    }

    // 🔹 Atualizar
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'data' => $request->all()
        ]);

        return response()->json($order->data);
    }

    // 🔹 Deletar
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Order deletada com sucesso'
        ]);
    }
}
