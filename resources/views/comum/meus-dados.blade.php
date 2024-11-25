@extends('layouts.main-comum')

@section('title', 'Início')

@section('content')

<h4>Meus Dados</h4>

<hr>

<form style="margin: 10px 0;" class="row g-3">
  <div class="col-md-6">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" class="form-control" id="nome" value="{{$pessoa->nome}}" disabled>
  </div>
  <div class="col-md-3">
    <label for="data-de-nascimento" class="form-label">Data de Nascimento</label>
    <input type="text" class="form-control" id="data-de-nascimento" value="{{date('d/m/Y', strtotime($pessoa->data_nasc))}}" disabled>
  </div>
  @if($pessoa->paternidade_maternidade != null)
  <div class="col-12">
    <label for="paternidade-maternidade" class="form-label">Paternidade/Maternidade</label>
    <input type="text" class="form-control" id="paternidade-maternidade" value="{{$pessoa->paternidade_maternidade}}" disabled>
  </div>
  @endif
  @if($pessoa->responsavel != null)
  <div class="col-12">
    <label for="responsavel" class="form-label">Responsavel</label>
    <input type="text" class="form-control" id="responsavel" value="{{$pessoa->responsavel}}" disabled>
  </div>
  @endif
  <div class="col-md-3">
    <label for="phoneNumber" class="form-label">Telefone</label>
    <input type="text" class="form-control" id="phoneNumber" value="{{$pessoa->telefone}}" disabled>
  </div>
  <div class="col-md-4">
    <label for="ocupacao" class="form-label">Ocupação</label>
    <input type="text" class="form-control" id="ocupacao" value="{{$pessoa->ocupacao}}" disabled>
  </div>
  <div class="col-md-4">
    <label for="inputCity" class="form-label">Cidade</label>
    <input type="text" class="form-control" id="inputCity" value="{{$pessoa->cidade}}" disabled>
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">Estado</label>
    <input type="text" class="form-control" id="inputState" value="{{$pessoa->uf_nome}}" disabled>
  </div>
  <div class="col-12">
    <label for="formation" class="form-label">Formação</label>
    <input type="text" class="form-control" id="formation" value="{{$pessoa->formation_name}}" disabled>
  </div>
  <div class="col-md-4">
    <label for="gridCheck" class="form-label">Sexo</label>
    <div class="d-flex align-items-center">
      <div class="form-check me-3">
        <input class="form-check-input" type="checkbox" id="gridCheckMasculino" disabled @if($pessoa->sexo == 1) checked @endif>
        <label class="form-check-label" for="gridCheckMasculino">
          Masculino
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="gridCheckFeminino" disabled @if($pessoa->sexo == 2) checked @endif>
        <label class="form-check-label" for="gridCheckFeminino">
          Feminino
        </label>
      </div>
    </div>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Alterar</button>
  </div>
</form>

@endsection