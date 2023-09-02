<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="pt-br" dir="ltr">
  <head>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <link rel="stylesheet" href="/css/bar.css">
     <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
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
          <li><a href="/admin/cadastro/aviso">Avisos</a></li>
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
          <li><a href="/admin/filtro/pessoa">Pessoa</a></li>
          <li><a href="/admin/filtro/aviso">Avisos</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-dollar' ></i>
            <span class="link_name">Financeiro</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Financeiro</a></li>
          <li><a href="/admin/financeiro/geral">Geral</a></li>
          <li><a href="/admin/financeiro/filtro">Filtro</a></li>
          <li><a href="/admin/financeiro/entrada">Entrada</a></li>
          <li><a href="/admin/financeiro/saida">Saída</a></li>
        </ul>
      </li>

      <li>
        <a href="/admin/chamadas">
          <i class="bx bx-list-ul"></i>
          <span class="link_name">Chamadas</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="/admin/chamadas">Chamadas</a></li>
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
          <li><a href="/admin/relatorios/cadastro">Cadastro (Relatório do dia) </a></li>
          <li><a href="/admin/relatorios/todos">Todos</a></li>

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

      <li>
    <div class="profile-details">
      <div class="profile-content">
        <!--<img src="image/profile.jpg" alt="profileImg">-->
      </div>
      <div class="name-job">
        <div class="profile_name" style="color: rgb(9, 150, 115)">{{auth()->user()->username}}</div>
        <div class="job">@if(auth()->user()->id_nivel == 2) Secretário/Admin @elseif(auth()->user()->id_nivel == 1) Master @else Classe @endif</div>
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
            </div>
  <section class="home-section" >
    <div class="home-content" >

      <i class='bx bx-menu' ></i>
      @yield('content')

  </section>
  <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>

<script>
let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e)=>{
 let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
 arrowParent.classList.toggle("showMenu");
  });
}
let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".bx-menu");
console.log(sidebarBtn);
sidebarBtn.addEventListener("click", ()=>{
  sidebar.classList.toggle("close");
});

$("#interesse").change(function() {
  if (this.value == 1 || this.value == 3) {
    $('#registerp').show();
    $('.inputprof').attr('required','required');
  } else {
    $('#registerp').hide();
    $('.inputprof').removeAttr('required');
  }
});

$("#scales").change(function() {
  if (this.checked) {
    $('#nomeResp').show();
    $('#numeroResp').show();
    $('#numero_pessoa').hide();
    $('#nome_responsavel').attr('required','required');
    $('#telefone_responsavel').attr('required','required');
  } else {
    $('#nomeResp').hide();
    $('#numeroResp').hide();
    $('#numero_pessoa').show();
    $('#nome_responsavel').removeAttr('required');
    $('#telefone_responsavel').removeAttr('required');
  }
});

$(document).ready(function() {
  $("#field").keyup(function() {
    $("#field").val(this.value.match(/[0-9]*/));
  });
  $("#telefone_responsavel").keyup(function() {
    $("#telefone_responsavel").val(this.value.match(/[0-9]*/));
  });
});

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

</body>
</html>
