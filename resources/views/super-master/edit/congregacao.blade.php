@extends('layouts.mainSuperMaster')

@section('title', 'Início')

@section('content')
    <link rel="stylesheet" href="/css/supermaster.css">
    <div class="row" style="margin: 2%">
    <div class="col-75">
        <div class="container">
            <form action="/super-master/update/congregacao/{{ $congregacao->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="col-50">
                    <h3>Informações</h3>
                    @if ($errors->any())
                        <div class="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif



                    <label for="nivel"><i class="fa fa-level-down"></i>Setor <font style="color:red;font-weight: bold">*</font></label>
                    <select name="setor" id="setor" required>
                        <option selected disabled value="">Selecionar</option>
                        @foreach ($setores as $s)
                            <option @if($s->id == $congregacao->setor_id) selected @endif value="{{ $s->id }}">{{ $s->nome }}</option>
                        @endforeach
                    </select>

                    <label for="nivel"><i class="fa fa-level-down"></i>Congregacão <font style="color:red;font-weight: bold">*</font></label>
                    <input name="congregacao" id="congregacao" type="text" required value="{{ $congregacao->nome }}">

                    <input type="submit" value="Alterar" class="btn">
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
