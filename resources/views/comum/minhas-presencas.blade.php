@extends('layouts.main-comum')

@section('title', 'Início')

@section('content')

    <h4>Minhas Presenças</h4>

    <hr>

    <div class="card mb-3">
        <form action="/comum/minhas-presencas" method="POST">
            @csrf
            @method('POST')
            <div class="card-header">
                Filtro
            </div>
            <div class="card-body">
                <div class="row mx-5 align-items-center">
                    <div class="col">
                        <label> Ano</label>
                        <select class="form-control" required name="year">
                            <option selected disabled value="">-- SELECIONE --</option>
                            @for($i = 2023; $i <= date('Y'); $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <label>Mês </label>
                        <select class="form-control col-sm" required name="month">
                            <option selected disabled value="">-- SELECIONE --</option>
                            @foreach($mesesNome as $i => $mes)
                                <option value="{{$i}}">{{$mes}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
           <div class="card-footer">
                <div class="row justify-content-end">
                    <button type="submit" class="btn btn-primary p-1 col-5 col-md-2 mx-1">Filtrar</button>
                    <button type="reset" class="btn btn-danger p-1 col-5 col-md-2 mx-1">Limpar Filtros</button>
                </div>
            </div>
        </form>
    </div>


    <p><span style="font-weight: bolder"> Período Filtro </span>:
            <span style="color: #0056b3">{{$month}}/{{$year}}</span>
    </p>

    <div class="table-responsive">
        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr class="table-primary">
                    <th>Data do Registro</th>
                    <th>Classe</th>
                    <th>Função</th>
                    <th>Presença</th>
                </tr>
            </thead>
            <tbody>
                @if(count($presencas) > 0)
                    @foreach ($presencas as $presenca)
                        <tr>
                            <td>{{date('d/m/Y', strtotime($presenca->created_at))}}</td>
                            <td>{{$presenca->sala_nome}}</td>
                            <td>{{$presenca->funcao_nome}}</td>
                            <td>
                                @if($presenca->presente)
                                    <i class='bx bx-check' style="color: green; font-size: 1.2em"></i>
                                @else
                                    <i class='bx bx-x' style="color: red; font-size: 1.2em"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">Nenhuma presença registrada</td>
                    </tr>
               @endif
            </tbody>

        </table>
    </div>


@endsection
