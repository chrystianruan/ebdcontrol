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
    <input type="text" class="form-control" id="paternidade-maternidade" value="@if($pessoa->paternidade-maternidade !=null) {{$pessoa->paternidade-maternidade}} @else - @endif" disabled>
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
    <label for="formation" class="form-label">Formação</label>
    <input type="text" class="form-control" id="formation" value="@if($pessoa->formation_name !=null) {{$pessoa->formation_name}} @else - @endif" disabled>
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
  <button class="btn btn-primary" id="btn-form">Alterar Senha</button>
</div>

<hr>

<form id="my_form" class="row g-3" style="display: none; margin: 15px 0">
  <div class="col-md-4">
    <label for="matricula" class="form-label">Matrícula</label>
    <input type="text" class="form-control" id="matricula" value="{{$pessoa->matricula}}" disabled>
  </div>
  <div class="col-md-4">
    <label for="nova-senha" class="form-label">Nova Senha</label>
    <div class="input-group">
      <i id="btn-lock-senha" class="bx bx-lock-alt btn btn-outline-secondary" style="cursor: pointer; display: flex; align-items: center; justify-content: center;"></i>
      <input type="password" class="form-control" id="senha" placeholder="Nova senha" >
    </div>
  </div>
  <div class="col-md-4">
    <label for="confirma-senha" class="form-label">Confirmar Senha</label>
    <div class="input-group">
      <i id="btn-lock-confirma" class="bx bx-lock-alt btn btn-outline-secondary" style="cursor: pointer; display: flex; align-items: center; justify-content: center;"></i>
      <input type="password" class="form-control" id="confirma-senha" placeholder="Nova senha" >
    </div>
    <div id="senha-error" style="color: red; display: none; margin-top: 5px">As senhas são diferentes!</div>
  </div>
  <div class="col-12">
    <button class="btn btn-danger" type="submit">Salvar</button>
  </div>
</form>

<script>
  let btn = document.getElementById('btn-form')
  let form = document.getElementById('my_form')

  btn.addEventListener('click', function() {
    if (form.style.display === 'none') {
      form.style.display = 'block'
    } else {
      form.style.display = 'none'
    }
  })
  
  form.addEventListener("submit", function (event) {
    let senha = document.getElementById("senha");
    let confirmaSenha = document.getElementById("confirma-senha");
    let senhaError = document.getElementById("senha-error");

    if (senha.value !== confirmaSenha.value) {
      event.preventDefault();
      senhaError.style.display = "block";
      confirmaSenha.classList.add("is-invalid");
    } else {
      senhaError.style.display = "none";
      confirmaSenha.classList.remove("is-invalid");
    }
  });

  function showOrHidePassword(senhaID, btnID) {
    let password = document.getElementById(senhaID)
    let btnLock = document.getElementById(btnID)

    btnLock.addEventListener("click", function() {
      if (password.type === "password") {
        password.type = "text";
        btnLock.className = "bx bx-lock-open-alt btn btn-outline-secondary";
      } else {
        password.type = "password";
        btnLock.className = "bx bx-lock-alt btn btn-outline-secondary"
      }
    } )
  }

  showOrHidePassword("senha", "btn-lock-senha");
  showOrHidePassword("confirma-senha", "btn-lock-confirma");
  
</script>

@endsection