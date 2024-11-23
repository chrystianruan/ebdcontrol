@extends('layouts.main-comum')

@section('title', 'Início')

@section('content')

    <h4>Minhas Presenças</h4>

    <p>{{$pessoa->frequencia_ebd}}</p>
@endsection
