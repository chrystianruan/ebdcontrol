@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')

    @include('templates.pessoas-filtro', ['view' => '/master/configuracoes/pessoas'])
@endsection

