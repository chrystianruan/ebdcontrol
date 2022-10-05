<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="/css/start.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
  <title>Início</title>
</head>
<body>

<p>Olá, {{auth()->user()-> name}}</p>
@if((auth()->user()->id_nivel != 1) && (auth()->user()->id_nivel != 2) && auth()->user()->status == false)<a href="/classe/">Acessar a área de Secretário/Classe ou professor</a>@endif
@if(auth()->user()->id_nivel == 2 && auth()->user()->status == false)<a href="/admin/">Acessar a área Admin</a>@endif
@if(auth()->user()->id_nivel == 1 && auth()->user()->status == false)<a href="/master/">Acessar a área Master</a>@endif
@if (session('danger'))
            <div class="alert">
                <font>{{session('danger')}}</font>
            </div>
@endif

@if(auth()->user()->status) Seu usuário está desativado, portanto, não poderá acessar nenhuma página. :( @endif
<div style="position: absolute;bottom: 0px;">
<hr style="margin: 10px"> 
<h4> - Histórico de manutenções: </h4>
<ul>
  <li> <span style="font-weight: bolder"> << Manutenção dia 05/10/2022 (00h31) >> </span> Finalização do "grosso" da geração de documentos do tipo Chamada e Relatório na área Admin, além de uma correção no código
  que enviava as chamadas ao Relatório, com o objetivo de adquirir uma melhor perfomance.
  </li>
  <li> <span style="font-weight: bolder"> << Manutenção dia 28/09/2022 (00h12) >> </span> Inicialização da geração de documentos, onde fora utilizada a biblioteca domPDF, sendo logrado êxito no uso da mesma. Logo, o sistema passa a permitir que o usuário Admin possa baixar arquivos PDF
  para Chamada e Relatório.
  </li>
</ul>

<h4> - Próximo objetivos: </h4>
<ul>
  <li>Tentar colocar arquivo png (para logos) no PDF.</li>
  <li>Permitir que o usuário Classe também possua a mesma capacidade de gerar arquivos PDF do tipo Chamada e Relatório.
  </li>
</ul>
</div>
</body>
</html>