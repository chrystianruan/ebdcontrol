<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="/css/barMaster.css">
<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
    <title>EBDControl</title>
</head>
<body>

<div class="topnav" id="myTopnav">
  <a href="/master" class="active"><i class="fa fa-home"></i>Master</a>
  <div class="dropdown">
    <button class="dropbtn"><i class="fa fa-plus"></i>Cadastro
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="/master/cadastro/usuario"><i class="fa fa-user-plus"></i>Usuário</a>
      <a href="/master/cadastro/classe"><i class="fa fa-graduation-cap"></i>Classe</a>
    </div>
  </div>
  <div class="dropdown">
    <button class="dropbtn"><i class="fa fa-filter"></i>Filtro
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="/master/filtro/usuario"><i class="fa fa-user-plus"></i>Usuário</a>
      <a href="/master/filtro/classe"><i class="fa fa-graduation-cap"></i>Classe</a>
    </div>
  </div>
    <div class="dropdown">
        <button class="dropbtn"><i class="fa fa-cogs"></i>Configurações
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
{{--            <a href="/master/filtro/usuario"><i class="fa fa-list"></i>Chamadas</a>--}}
            <a href="/master/configuracoes/pessoas"><i class="fa fa-user"></i>Pessoas</a>
        </div>
    </div>
  <a href="/sobre"><i class="fa fa-info-circle"></i>Sobre</a>
  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
  <div style="float: right">
    <a href="#" style="color: rgb(9, 150, 115)"><i class="fa fa-user" style="color: rgb(9, 150, 115)"></i>{{ auth()->user()->username}}</a>
    <a> <form action="/logout" method="POST"> @csrf <button style="border: none; font-size: 1em; background: none;cursor:pointer" type="submit"> <i style="color: red; font-size: 1.1em"class="bx bx-exit"></i></button></form></a>
    </div>
</div>
<div>
    @if(session('msg'))
        <p class="msg" id="msg">{{session('msg')}}</p>
    @endif
    @if(session('msg2'))
        <p class="msg2" id="msg2">{{session('msg2')}}</p>
    @endif
</div>
  @yield('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}

  @if(session('msg') || session('msg2'))
    function hideMsg() {
      let msg = document.getElementById("msg");
      msg.style = "display:none";
    }

    function hideMsg2() {
      let msg2 = document.getElementById("msg2");
      msg2.style = "display:none";
    }

  setTimeout(hideMsg, 2000);
  setTimeout(hideMsg2, 3000);
  @endif
</script>
@stack('master')
@stack('graphs')
@stack('pessoa-filtro')

</body>
</html>
