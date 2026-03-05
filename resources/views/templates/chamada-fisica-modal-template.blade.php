<div class="form-group">
    <label class="label" for="select-classe-fisica">Classe</label>
    <select class="select" name="classe_fisica" id="select-classe-fisica" required>
        <option selected disabled value="">Selecionar classe</option>
        @foreach($salas as $sala)
            <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
        @endforeach
    </select>
</div>

<div class="form-group" style="margin-top: 16px;">
    <label class="label" for="date-chamada-fisica">Data</label>
    <input type="date" class="input" name="date_fisica" id="date-chamada-fisica" required>
</div>

