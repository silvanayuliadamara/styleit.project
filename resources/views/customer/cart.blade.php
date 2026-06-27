@extends('layouts.app', ['title' => 'Keranjang Booking - Lisa Yuli Belti'])

@section('content')
    @include('customer.cart.partials.styles')

    @include('customer.cart.partials.hero')

    <section class="mb-5 pb-5">
        <div class="container">
            @if(empty($cart))
                @include('customer.cart.partials.empty-state')
            @else
                <form action="{{ route('customer.cart.select') }}" method="POST" id="cartForm">
                    @csrf
                    <div class="row g-4">
                        {{-- Kolom Kiri: Items --}}
                        <div class="col-lg-8">
                            @include('customer.cart.partials.items-list')
                        </div>

                        {{-- Kolom Kanan: Ringkasan --}}
                        <div class="col-lg-4">
                            @include('customer.cart.partials.summary-card')
                        </div>
                    </div>
                </form>

                {{-- Hidden delete forms --}}
                @include('customer.cart.partials.delete-forms')
            @endif
        </div>
    </section>

    @include('customer.cart.partials.scripts')
@endsection
