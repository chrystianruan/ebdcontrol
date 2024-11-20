@extends('layouts.main-comum')

@section('title', 'Início')

@section('content')

<h4>Meus Dados</h4>

<hr>

<div class="table-responsive">
  <table cellpadding="5" cellspacing="0">
    <tr>
      <th>Nome</th>
      <td>{{$pessoa->nome}}</td>
    </tr>
    <tr>
      <th>Data de Nascimento</th>
      <td>{{date('d/m/Y', strtotime($pessoa->data_nasc))}}</td>
    </tr>
    <tr>
      <th>Sexo</th>
      <td>@if($pessoa->sexo == 1) Masculino @else Feminino @endif</td>
    </tr>
    <tr>
      <th>Ocupação</th>
      <td>{{$pessoa->ocupacao}}</td>
    </tr>
    <tr>
      <th>Cidade</th>
      <td>{{$pessoa->cidade}}</td>
    </tr>
    <tr>
      <th>Telefone</th>
      <td>{{$pessoa->telefone}}</td>
    </tr>
    @if($pessoa->paternidade_maternidade != null)
    <tr>
      <th>Paternidade/Maternidade</th>
      <td>{{$pessoa->paternidade_maternidade}}</td>
    </tr>
    @endif
    @if($pessoa->responsavel != null)
    <tr>
      <th>Responsável</th>
      <td>{{$pessoa->responsavel}}</td>
    </tr>
    @endif
  </table>
</div>


@endsection