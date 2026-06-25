@extends('layouts.app', ['title' => $category->name . ' - Lisa Yuli Belti'])

@section('content')
    @include('layanan.sections.kategori-hero')
    @include('layanan.sections.kategori-packages')
@endsection
