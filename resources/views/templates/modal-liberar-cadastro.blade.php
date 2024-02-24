<div class="dialog" id="modal-liberar-cadastro">
    <div class="dialog-overlay" tabindex="-1"></div>
    <div class="dialog-content" role="dialog">
        <div role="document">
            <button class="dialog-close" id="dialog-close-cadastro">&times;</button>
            <h1 id="dialogTitle">Liberar link de cadastro</h1>
            <hr>
            <div class="row" style="margin: 2%">
                <div class="col-75">
                    <div class="container">
                        <input type="hidden" id="link-ativo" value="@if($linkAtivo)1 @else 0 @endif">
                        <input type="hidden" id="url-cadastro" value="{{url('/master/liberar-cadastro')}}">
                        <input type="hidden" id="congregacao-cadastro" value="{{auth()->user()->congregacao_id}}">
                        <input type="text" readonly value="{{url('/cadastro')}}/{{base64_encode(auth()->user()->congregacao_id)}}">
                        <button id="liberar-link-cadastro" class="btn-liberar" > <span id="string-liberar-bloquear">Liberar</span> link </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
