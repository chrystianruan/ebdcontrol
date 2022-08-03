<link rel="stylesheet" href="/css/start.css">
<p>Olá, {{auth()->user()-> name}}</p>
@if((auth()->user()->id_nivel != 1) && (auth()->user()->id_nivel != 2) && auth()->user()->status == false)<a href="/classe/">Acessar o sistema de Secretário/Classe ou professor</a>@endif
@if(auth()->user()->id_nivel == 2 && auth()->user()->status == false)<a href="/admin/">Acessar a página administrativa</a>@endif
@if(auth()->user()->id_nivel == 1 && auth()->user()->status == false)<a href="/master/">Acessar a página master</a>@endif
@if (session('danger'))
            <div class="alert">
                <font>{{session('danger')}}</font>
            </div>
@endif

@if(auth()->user()->status) Seu usuário está desativado, portanto, não poderá acessar nenhuma página. :( @endif