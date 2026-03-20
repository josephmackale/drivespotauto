@extends('layouts.store')

@section('content')
    <!--@include('store.partials.home.hero')-->

    @include('store.partials.shared.vehicle-selector')
    @include('store.partials.home.categories')

    @include('store.partials.home.featured-products', ['featuredProducts' => $featuredProducts])
@endsection