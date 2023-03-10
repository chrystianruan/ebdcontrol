@if(auth()->user()->id_nivel == 1)
    <h1>Sobre Master</h1>
    <a href="/master"><button>Voltar</button></a>
@endif

@if(auth()->user()->id_nivel == 2)
    <h1> Sobre Admin</h1>
    <a href="/admin"><button>Voltar</button></a>
@endif

@if(auth()->user()->id_nivel != 1 && auth()->user()->id_nivel != 2)
    <h1>Sobre Classe</h1>
    <a href="/classe"><button>Voltar</button></a>
@endif
