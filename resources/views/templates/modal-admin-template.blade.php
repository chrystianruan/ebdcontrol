@push('modal.admin.css')
    <link rel="stylesheet" href="{{ cacheBust('css/modalAdmin.css') }}">
@endpush

<div class="modal-overlay" id="{{ $modalId }}">
    <div class="modal {{ $modalClass ?? '' }}">
        <div class="modal-header">
            <h2 id="modalTitle">{{ $modalTitle }}</h2>
            <button class="modal-close" onclick="{{ $closeModal }}">
                <i class="bx bx-x"></i>
            </button>
        </div>

        <div class="modal-body" id="modalBody">
            @include($modalBody, [
                        'route' => $routeModal ?? null
                       ])
        </div>

        <div class="modal-footer" id="modalFooter">
            <button class="btn btn-secondary" onclick="{{ $closeModal }}">Cancelar</button>
            @if(!empty($actionButton))
                <button class="btn btn-primary" onclick="{{ $actionButton }}" id="btnSaveEdit">Salvar</button>
            @endif
        </div>
    </div>
</div>
