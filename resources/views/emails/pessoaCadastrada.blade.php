@extends('templates.email')
@section('content')
    <p> {{ $pessoaNome }} foi cadastrado(a) na congregação {{ $congregacaoNome }} </p>
@endsection
