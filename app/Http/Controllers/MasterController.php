<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sala;




use Carbon\Carbon;
use DB;

class MasterController extends Controller
{
    public function dashboardMaster() {
        $qtdUsersAtivos = User::select(DB::raw('count(users.id) as qtd, id_nivel, salas.nome as niveis'))
        ->leftJoin('salas', 'salas.id', '=', 'users.id_nivel')
        ->where('status', false)
        ->groupBy('id_nivel')
        ->get();
        $qtdUsersInativos = User::select(DB::raw('count(users.id) as qtd, id_nivel, salas.nome as niveis'))
        ->leftJoin('salas', 'salas.id', '=', 'users.id_nivel')
        ->where('status', true)
        ->groupBy('id_nivel')
        ->get();

        return view('/master/dashboard', ['qtdUsersAtivos' => $qtdUsersAtivos,
         'qtdUsersInativos' => $qtdUsersInativos]);
    }
    
    public function indexSalaMaster() {
        $dataAtual = date('d/m/Y');
        return view('/master/cadastro/classe', ['dataAtual' => $dataAtual]);
    }

    public function storeSalaMaster(Request $request) {
        $this->validate($request, [
            'nome' => ['required'],
            'tipo' => ['required'],
        ], [
            'nome.required' =>  'Nome é obrigatório.',
            'tipo.required' =>  'Tipo é obrigatório.',
        ]);

        $dataAtual = date('d/m/Y');
        $sala = new Sala;
        $sala -> nome = $request->nome;
        $sala -> tipo = $request->tipo;
        $sala -> save();
        return redirect('/master/cadastro/classe')->with('msg', 'Sala cadastrada com sucesso');
    }

    public function searchSalaMaster(Request $request) {
        $salap = request('sala');
        
        if(isset($request->sala)) {
            $salas = Sala::where('id', '=', $request->sala)
            ->get();
        } else {
            $salas = Sala::orderBy('nome')
            ->get();
        }
        $pessoas = DB::select('select id_sala as ids, count(id) as quantidade from pessoas group by (ids);');
        $classes = Sala::orderBy("nome")->get();
        return view('/master/filtro/classe',['salas' => $salas, 'pessoas' => $pessoas, 'salap' => $salap, 'classes' => $classes]);
    }

    public function editSalaMaster($id) {
        
        $dataAtual = date('Y-m-d');
        $sala = Sala::findOrFail($id);
        if($id == 1 || $id == 2) {
            return redirect('/admin/filtro/sala')->with('msg2', 'Essa sala não pode ser editada ou excluída');
        }

        return view('/master/edit/classe', ['sala' => $sala, 'dataAtual' => $dataAtual]);

    }

    public function updateSalaMaster(Request $request){
        $this->validate($request, [
            'nome' => ['required'],
            'tipo' => ['required'],
        ], [
            'nome.required' =>  'Nome é obrigatório.',
            'tipo.required' =>  'Tipo é obrigatório.',
        ]);
        Sala::findOrFail($request -> id)->update($request->all());
        return redirect('/master/filtro/classe')->with('msg', 'Sala foi atualizada com sucesso');
    }

    public function destroySalaMaster($id) {
        Sala::findOrFail($id)->delete();
        return redirect('/master/filtro/classe')->with('msg', 'Sala deletada com sucesso');
        
    }


    
}
