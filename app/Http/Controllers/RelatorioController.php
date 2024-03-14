<?php
namespace App\Http\Controllers;
use App\Models\Chamada;
use App\Models\Funcao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RelatorioController extends Controller {
    public function generateRelatorioPerDate(Request $request)
    {

        $presencas = $this->returnData($request->initialDate, $request->finalDate, base64_decode($request->classeId));

        return $presencas;
    }

    public function returnData($initial_date, $final_date, $classe_id)
    {
        $chamadas = $this->getChamadas($initial_date, $final_date, auth()->user()->congregacao_id, $classe_id);
        $duplicatesNamesAndPresencas = $this->getListWithNameAndPresencasDuplicates($chamadas);

        return $duplicatesNamesAndPresencas;
    }

    public function getChamadas($initial_date, $final_date, $congregacao_id, $classe_id) {
        $finalDate = $final_date." 23:59:59";
        $chamadas = Chamada::where('id_sala', '=', $classe_id)
            ->whereBetween('created_at',  [$initial_date, $finalDate])
            ->where('congregacao_id', '=', $congregacao_id)
            ->get();

        return $chamadas;
    }
    public function getListWithNameAndPresencasDuplicates($chamadas) {
        $namesAndPresencas = [];
        foreach ($chamadas as $cha) {
            $nomes = json_decode($cha->nomes, true);
            foreach ($nomes as $cn) {

                $namesAndPresencas[] = [
                    'id' => $cn['id'],
                    'nome' => $cn['nome'],
                    'data_nasc' => $cn['data_nasc'],
                    'id_funcao' => $cn['id_funcao'],
                    'presenca' => (int)$cn['presenca'],
                ];
            }
        }
        return json_encode($namesAndPresencas);
    }
    public function formatData(Request $request) {
        $dataArray = json_decode($request->data, true);
        $dataFormated = [];
        $funcoes = Funcao::all();
        foreach ($dataArray as $data) {
            foreach ($funcoes as $func) {
                if ($func->id == $data['id_funcao']) {
                    $funcao = $func->nome;
                }
            }
            $dataFormated[] = [
                'id' => $data['id'],
                'nome' => $data['nome'],
                'id_funcao' => $funcao,
                'data_nasc' => date('d/m/Y', strtotime($data['data_nasc'])),
                'presencas' => array_sum($data['presencas'])
            ];
        }
        array_multisort(array_column($dataFormated, 'presencas'), SORT_DESC, $dataFormated);
        return $dataFormated;
    }


}
