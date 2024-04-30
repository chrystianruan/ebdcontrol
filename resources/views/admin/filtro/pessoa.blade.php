@extends('layouts.main')

@section('title', 'Início')

@section('content')

    @include('templates.pessoas-filtro', ['view' => '/admin/filtro/pessoa'])
@endsection

