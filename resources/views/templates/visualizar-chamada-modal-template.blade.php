<input type="hidden" id="url-visualizar-chamada" value="{{ url('/api/chamada') }}">

{{-- Loading --}}
<div id="view-chamada-loading" class="chamada-loading" style="display:none;">
    <div class="chamada-loading-spinner"></div>
    <span>Carregando dados da chamada...</span>
</div>

{{-- Erro --}}
<div id="view-chamada-error" style="display:none;"></div>

{{-- Conteúdo --}}
<div id="view-chamada-content" style="display:none;">

    {{-- Tabela de Presenças --}}
    <div class="form-section">
        <div class="form-section-title">
            <i class='bx bx-group' style="margin-right: 6px;"></i>
            <span id="view-chamada-classe"></span> — <span id="view-chamada-data"></span>
        </div>

        <div class="chamada-modal-table-wrapper">
            <table class="chamada-modal-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class="container-hideable">Anivers.</th>
                        <th>Função</th>
                        <th style="text-align: center;">Presença</th>
                    </tr>
                </thead>
                <tbody id="view-chamada-tbody">
                </tbody>
            </table>
        </div>
    </div>

    {{-- Dados da Chamada --}}
    <div class="form-section">
        <div class="form-section-title">
            <i class='bx bx-bar-chart-alt-2' style="margin-right: 6px;"></i>
            Dados da Chamada
        </div>

        <div class="view-chamada-stats">
            <div class="view-chamada-stat">
                <span class="view-chamada-stat-label">Matriculados</span>
                <span class="view-chamada-stat-value" id="view-chamada-matriculados">-</span>
            </div>
            <div class="view-chamada-stat">
                <span class="view-chamada-stat-label">Presentes</span>
                <span class="view-chamada-stat-value highlight-green" id="view-chamada-presentes">-</span>
            </div>
            <div class="view-chamada-stat">
                <span class="view-chamada-stat-label">Visitantes</span>
                <span class="view-chamada-stat-value" id="view-chamada-visitantes">-</span>
            </div>
            <div class="view-chamada-stat">
                <span class="view-chamada-stat-label">Assist. Total</span>
                <span class="view-chamada-stat-value highlight-purple" id="view-chamada-assist-total">-</span>
            </div>
            <div class="view-chamada-stat">
                <span class="view-chamada-stat-label">Bíblias</span>
                <span class="view-chamada-stat-value" id="view-chamada-biblias">-</span>
            </div>
            <div class="view-chamada-stat">
                <span class="view-chamada-stat-label">Revistas</span>
                <span class="view-chamada-stat-value" id="view-chamada-revistas">-</span>
            </div>
        </div>

        <div id="view-chamada-obs-section" style="display:none; margin-top: 12px;">
            <label class="label" style="margin-bottom: 4px;">
                <i class='bx bx-message-detail' style="margin-right: 4px;"></i>Observações
            </label>
            <div class="view-chamada-obs" id="view-chamada-obs"></div>
        </div>
    </div>
</div>


