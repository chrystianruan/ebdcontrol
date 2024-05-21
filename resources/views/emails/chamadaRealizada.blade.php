@extends('templates.email')
@section('content')
    <p> A chamada da classe {{ $classeNome }} foi realizada na congregação {{ $congregacaoNome }} </p>
@endsection
