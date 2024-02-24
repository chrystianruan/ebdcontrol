<div class="dialog" id="modal-liberar-chamada">
    <div class="dialog-overlay" tabindex="-1"></div>
    <div class="dialog-content" role="dialog">
        <div role="document">
            <button class="dialog-close" id="dialog-close-chamada">&times;</button>
            <h1 id="dialogTitle">Liberar chamada</h1>
            <hr>
            <div class="row" style="margin: 2%">
                <div class="col-75">
                    <div class="container">
                        <input type="hidden" id="url" value="{{url('/master/liberar-chamada')}}">
                        <input type="hidden" id="congregacao" value="{{auth()->user()->congregacao_id}}">
                        <label>Escolha a data para liberação</label>
                        <input type="date" id="date" required>

                        <button type="button" id="liberar">Liberar chamada</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row" style="margin: 2%">
                <div class="col-75">
                    <div class="container">
                       <h4>Datas de liberação do mês {{ date('m') }}:</h4>
                        <hr>
                        <ul>
                            @foreach($chamadasLiberadasMes as $data)
                                <li>{{ date('d/m', strtotime($data->date)) }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
