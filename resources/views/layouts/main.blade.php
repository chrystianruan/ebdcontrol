<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="pt-br" dir="ltr">
  <head>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
     <link rel="stylesheet" href="/css/bar.css">
     <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
      @stack('cadastro-pessoa-css')
    <title>EBDControl</title>
   </head>
<body>
  <div class="sidebar close">
    <div class="logo-details">
      <img width=50 style="margin: 12px" src="/img/logo_ebd.png">
      <span class="logo_name">Admin</span>
    </div>
    <ul class="nav-links">
      <li>
        <a href="/admin/">
          <i class='bx bx-grid-alt' ></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="/admin/">Dashboard</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-user-plus' ></i>
            <span class="link_name">Cadastro</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Cadastro</a></li>
          <li><a href="/admin/cadastro/pessoa">Pessoa</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-filter-alt' ></i>
            <span class="link_name">Filtros</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Filtros</a></li>
          <li><a href="/admin/filtro/pessoa">Pessoas</a></li>
          <li><a href="/admin/filtro/pre-cadastros">Pré-Cadastros</a></li>
        </ul>
      </li>
{{--      <li>--}}
{{--        <div class="iocn-link">--}}
{{--          <a href="#">--}}
{{--            <i class='bx bx-dollar' ></i>--}}
{{--            <span class="link_name">Financeiro</span>--}}
{{--          </a>--}}
{{--          <i class='bx bxs-chevron-down arrow' ></i>--}}
{{--        </div>--}}
{{--        <ul class="sub-menu">--}}
{{--          <li><a class="link_name" href="#">Financeiro</a></li>--}}
{{--          <li><a href="/admin/financeiro/geral">Geral</a></li>--}}
{{--          <li><a href="/admin/financeiro/filtro">Filtro</a></li>--}}
{{--          <li><a href="/admin/financeiro/entrada">Entrada</a></li>--}}
{{--          <li><a href="/admin/financeiro/saida">Saída</a></li>--}}
{{--        </ul>--}}
{{--      </li>--}}
        <li>
            <div class="iocn-link">
                <a href="#">
                    <i class='bx bx-list-ul' ></i>
                    <span class="link_name">Chamadas</span>
                </a>
                <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul class="sub-menu">
                <li><a class="link_name" href="#">Chamadas</a></li>
                <li><a href="/admin/realizar-chamadas">Realizar Chamada</a></li>
                <li><a href="/admin/chamadas">Filtrar Chamadas</a></li>
            </ul>
        </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-trending-up' ></i>
            <span class="link_name">Relatórios</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Relatórios</a></li>
          <li><a href="/admin/relatorios/todos">De chamadas</a></li>
            <li><a href="/admin/relatorios/presenca-classe">De presenças</a></li>

        </ul>
      </li>

      <li>
        <a href="/sobre">
          <i class='bx bx-info-circle' ></i>
          <span class="link_name">Sobre</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="/sobre">Sobre</a></li>
        </ul>
      </li>
        @if (auth()->user()->permissao_id < 3)
            <li style="margin-top: 20%">
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-shield' ></i>
                        <span class="link_name">Usuário</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow' ></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Usuário</a></li>
                    @if (auth()->user()->permissao_id == 1)<li><a href="/super-master">SuperMaster</a></li>@endif
                    @if (auth()->user()->permissao_id <= 2)<li><a href="/master">Master</a></li>@endif
                    <li><a href="/comum">Comum</a></li>
                </ul>
            </li>
        @endif

      <li>
    <div class="profile-details">
      <div class="profile-content">
        <!--<img src="image/profile.jpg" alt="profileImg">-->
      </div>
      <div class="name-job">
        <div class="profile_name" style="color: rgb(9, 150, 115)">{{auth()->user()->matricula}}</div>
        <div class="job">{{auth()->user()->permissao->name}}</div>
      </div>
      <a > <form action="/logout" method="POST"> @csrf <button style="border: none; font-size: 1em; background: none;cursor:pointer" type="submit"> <i style="color: red; font-size: 1.1em"class="bx bx-exit"></i></button></form></a>
    </div>
  </li>
</ul>
  </div>
            <div>
                @if(session('msg'))
                    <p class="msg" id="msg">{{session('msg')}}</p>
                @endif
                @if(session('msg2'))
                    <p class="msg2" id="msg2">{{session('msg2')}}</p>
                @endif
                @if(session('msg3'))
                    <p class="msg3" id="msg3">{{session('msg3')}}</p>
                @endif
            </div>
  <section class="home-section" >
    <div class="home-content" >

      <i class='bx bx-menu' ></i>
      @yield('content')

  </section>
  <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous">
  </script>

  <script src="/js/layoutMain.js"></script>

  <script>
@if(session('msg') || session('msg2') || session('msg3'))
  function hideMsg() {
    let msg = document.getElementById("msg");
    msg.style = "display:none";
  }

  function hideMsg2() {
    let msg2 = document.getElementById("msg2");
    msg2.style = "display:none";
  }

  function hideMsg3() {
    let msg3 = document.getElementById("msg3");
    msg3.style = "display:none";
  }
    setTimeout(hideMsg3, 20000);

  setTimeout(hideMsg, 2000);
  setTimeout(hideMsg2, 3000);

      @endif
  </script>
  @stack('scripts-relatorio-presenca')
  @stack('scripts-cadastro')
  @stack('script-edit-pessoa')

</body>
</html>
