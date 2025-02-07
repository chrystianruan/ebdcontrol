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
    <input type="text" class="form-control" id="paternidade-maternidade" value="@if($pessoa->paternidade_maternidade !=null) {{$pessoa->paternidade_maternidade}} @else - @endif" disabled>
  </div>
  @endif
  @if($pessoa->responsavel != null)
  <div class="col-12">
    <label for="responsavel" class="form-label">Responsavel</label>
    <input type="text" class="form-control" id="responsavel" value="@if($pessoa->responsavel !=null) {{$pessoa->responsavel}} @else - @endif" disabled>
  </div>
  @endif
  <div class="col-md-3">
    <label for="phoneNumber" class="form-label">Telefone</label>
    <input type="text" class="form-control" id="phoneNumber" value="@if($pessoa->telefone !=null) {{$pessoa->telefone}} @else - @endif" disabled>
  </div>
  <div class="col-md-4">
    <label for="ocupacao" class="form-label">Ocupação</label>
    <input type="text" class="form-control" id="ocupacao" value="@if($pessoa->ocupacao !=null) {{$pessoa->ocupacao}} @else - @endif" disabled>
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
    <label for="formation" class="form-label">Formação Acadêmica</label>
    <input type="text" class="form-control" id="formation" value="{{$pessoa->formation_nome}}" disabled>
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
</form>
<div class="col-12">
  <button class="btn btn-primary" id="btn-alterar">Alterar Senha</button>
</div>

<hr>

<form id="form-alterar-senha" class="row g-3" style="display: none; margin: 15px 0" action="/user/change-password" method="POST">
    @method('POST')
    @csrf
  <div class="col-md-4">
    <label for="matricula" class="form-label">Matrícula</label>
    <input type="text" class="form-control" id="matricula" value="{{$pessoa->matricula}}" disabled>
  </div>
  <div class="col-md-4">
    <label for="nova-senha" class="form-label">Nova Senha</label>
    <div class="input-group">
      <input type="password" class="form-control" id="senha" placeholder="Digite sua senha" name="password">
       <i id="btn-lock-senha" class="bx bx-lock-alt btn btn-outline-secondary" style="cursor: pointer; display: flex; align-items: center; justify-content: center;"></i>
    </div>
  </div>
  <div class="col-md-4">
    <label for="confirma-senha" class="form-label">Confirmar Senha</label>
    <div class="input-group">
      <input type="password" class="form-control" id="confirma-senha" placeholder="Confirme sua senha" >
      <i id="btn-lock-confirma" class="bx bx-lock-alt btn btn-outline-secondary" style="cursor: pointer; display: flex; align-items: center; justify-content: center;"></i>
    </div>
    <div id="senha-error" style="color: red; display: none; margin-top: 5px">As senhas são diferentes!</div>
  </div>
  <div class="col-12">
    <button class="btn btn-danger" type="button" id="btn-save">Salvar</button>
  </div>
</form>

@push('scripts-meus-dados')
<script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous">
</script>
<script src="/js/changePassword.js"></script>
@endpush

@endsection
