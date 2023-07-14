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
@if(auth()->user()->super_master)<p><a href="/super-master/">Acessar a área SuperMaster</a></p>@endif
@if (session('danger'))
            <div class="alert">
                <span>{{session('danger')}}</span>
            </div>
@endif

@if(auth()->user()->status) Seu usuário está desativado, portanto, não poderá acessar nenhuma página. :( @endif

</body>
</html>
