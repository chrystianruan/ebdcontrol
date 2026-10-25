<input type="hidden" id="buscar-pessoas-url" value="{{ url('/api/pessoas_sala') }}">

@if($isDiaChamada)
    @if(count($classesFaltantes) > 0)
        {{-- Seleção de Classe --}}
        <div class="form-group" style="margin-bottom: 20px;">
            <label class="label">Selecione a Classe <span class="required">*</span></label>
            <select id="modal-select-sala" class="select">
                <option selected disabled value="">Escolha uma classe...</option>
                @foreach($classesFaltantes as $classe_faltante)
                    <option value="{{ $classe_faltante['id'] }}">{{ $classe_faltante['nome'] }}</option>
                @endforeach
            </select>
        </div>

        {{-- Loading --}}
        <div id="modal-loading" class="chamada-loading" style="display:none;">
            <div class="chamada-loading-spinner"></div>
            <span>Carregando alunos...</span>
        </div>

        {{-- Área de erros --}}
        <div id="modal-chamada-errors" style="display:none;"></div>

        {{-- Formulário oculto inicialmente --}}
        <div id="modal-chamada-content" style="display:none;">
            <form id="form-realizar-chamada" action="/realizar-chamada" method="POST">
                @csrf
                <input type="hidden" id="modal-pessoas" name="pessoas_presencas" value="">
                <input type="hidden" id="modal-sala" name="sala" value="">
                <input type="hidden" name="route" value="{{ url('/admin/chamadas') }}">

                {{-- Tabela de Pessoas --}}
                <div class="form-section padding-minor">
                    <div class="chamada-modal-table-wrapper">
                        <table class="chamada-modal-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Função</th>
                                    <th style="text-align: center;">Presente</th>
                                </tr>
                            </thead>
                            <tbody id="modal-tbody-pessoas">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Dados Extras --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <i class='bx bx-bar-chart-alt-2' style="margin-right: 6px;"></i>
                        Dados da Chamada - <span id="modal-span-nome-classe"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="label">Matriculados</label>
                            <input class="input" name="matriculados" type="number" id="modal-matriculados" required value="" readonly>
                        </div>
                        <div class="form-group">
                            <label class="label">Presentes</label>
                            <input class="input" name="presentes" type="number" id="modal-presentes" min="0" required readonly value="0">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="label">Visitantes</label>
                            <input class="input" name="visitantes" type="number" id="modal-visitantes" min="0" required value="0">
                        </div>
                        <div class="form-group">
                            <label class="label">Assist. Total</label>
                            <input class="input" type="number" min="0" id="modal-assist-total" readonly required value="0">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="label">Bíblias</label>
                            <input class="input" name="biblias" type="number" min="0" required value="0">
                        </div>
                        <div class="form-group">
                            <label class="label">Revistas</label>
                            <input class="input" name="revistas" type="number" min="0" required value="0">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 4px;">
                        <label class="label">Observações</label>
                        <textarea class="input" name="observacoes" maxlength="500" rows="3" placeholder="Observações sobre a chamada..." style="resize: vertical;"></textarea>
                    </div>
                </div>
            </form>
        </div>
    @else
        <div class="chamada-empty-state">
            <div class="chamada-empty-icon">
                <i class='bx bx-check-circle'></i>
            </div>
            <h3>Todas as chamadas já foram realizadas!</h3>
            <p>Todas as classes já enviaram a chamada de hoje.</p>
        </div>
    @endif
@else
    <div class="chamada-empty-state">
        <div class="chamada-empty-icon warning">
            <i class='bx bx-calendar-x'></i>
        </div>
        <h3>Hoje não é dia de chamada</h3>
        <p>Aguarde o próximo dia habilitado para realizar chamadas.</p>
    </div>
@endif

