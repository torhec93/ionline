@extends('layouts.app')

@section('title', 'Origenes')

@section('content')

@include('warehouse.' . $nav)

@livewire('warehouse.origins.origin-index', [
    'store' => $store,
    'nav' => $nav,
])

@endsection
