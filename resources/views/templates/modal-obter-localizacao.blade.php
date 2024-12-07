<div class="dialog" id="modal-obter-localizacao" style="display: none">
    <div class="dialog-overlay" tabindex="-1"></div>
    <div class="dialog-content" role="dialog">
        <div role="document">
            <button class="dialog-close" id="dialog-close-obter-localizacao">&times;</button>
            <h1 id="dialogTitle">Cadastrar/Alterar Endereço de Congregação</h1>
            <hr>
            <div class="row" style="margin: 2%">
                <div class="col-75" style="display: flex; flex-direction: column; grid-gap: 10px;">
                    <span style="background-color: yellow; padding: 10px; border-radius: 10px; border: 1px solid #ccc; text-align: justify">
                        Para cadastrar/alterar o endereço, você deve conceder acesso à localização ao apertar o botão abaixo.
                        Quando o acesso for concedido, o <strong> endereço aproximado </strong> (varia de 0m a 100m) será mostrado na tela.
                        <span style="font-weight: bold"> Após isso, basta apertar o confirmar e a localização será salva. </span>
                    </span>

                    <button id="btn-obter-localizacao" type="button"> Obter localização </button>
                </div>
            </div>
            <hr>
            <div class="row" style="margin: 2%">
                <div class="col-75">
                    <div class="infos-endereco">
                        <ul id="result"></ul>
                    </div>
                    <div class="infos-endereco">
                        <form action="/congregacao/salvar-localizacao" id="form-localizacao" style="display: none" method="POST">
                            @csrf
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            <button type="submit" id="btn-confirmar-localizacao" class="btn-confirmar-localizacao"> Confirmar </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
