<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/barClasse.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
    <title>EBDControl</title>
</head>
<body>

  <div class="topnav" id="myTopnav">
    <a href="/classe" class="active"><i class="fa fa-home"></i>Classe</a>
    <a href="/classe/cadastro-pessoa"><i class="fa fa-user-plus"></i>Cadastro</a>
    <a href="/classe/pessoas"><i class="fa fa-users"></i>Pessoas</a>
    <a href="/classe/chamada-dia"><i class="fa fa-plus-circle"></i>Chamada do dia</a>
    <a href="/classe/todas-chamadas"><i class="fa fa-list"></i>Todas Chamadas</a>
    <a href="#about"><i class="fa fa-info-circle"></i>Sobre</a>
    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
      <i class="fa fa-bars"></i>
    </a>
    <div style="float: right">
      <a href="#" style="color: rgb(9, 150, 115);"><i class="bx bx-user" ></i>{{ auth()->user()->username }}</a>
      <a > <form action="/logout" method="POST"> @csrf <button style="border: none; font-size: 1em; background: none;cursor:pointer" type="submit"> <i style="color: red; font-size: 1.1em"class="bx bx-exit"></i></button></form></a>
      </div>
  </div>
  <div>
    @if(session('msg'))
        <p class="msg" id="sucessMessage">{{session('msg')}}</p>
    @endif
    @if(session('msg2'))
        <p class="msg2" id="sucessMessage">{{session('msg2')}}</p>
    @endif
</div>
  @yield('content')

  <script>
    function myFunction() {
      var x = document.getElementById("myTopnav");
      if (x.className === "topnav") {
        x.className += " responsive";
      } else {
        x.className = "topnav";
      }
    }
    </script>
</body>
</html> 
