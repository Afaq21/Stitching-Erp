@extends('layouts.master')

@section('css')
@endsection

@section('content')

<div class="container-fluid mt-4">

    <!-- Header + Search -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h5 class="fw-bold mb-0">Design Catalog</h5>
        </div>
        <div class="col-md-6 text-end">
            <input type="text"
                class="form-control w-50 d-inline"
                placeholder="Search designs...">
        </div>
    </div>

    <!-- Catalog Grid -->
    <div class="row g-4">
        @forelse($designs as $design)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card ecommerce-product-widgets h-100 shadow-sm">
                <div class="card-body text-center">

                    <!-- Image -->
                    <img
                        src="{{ $design->image_path
                                    ? asset('storage/'.$design->image_path)
                                    : asset('assets/images/no-image.png') }}"
                        class="img-fluid rounded mb-3"
                        style="height:180px;object-fit:cover">

                    <!-- Title -->
                    <h6 class="fw-semibold mb-1">
                        {{ $design->title }}
                    </h6>

                    <!-- Service Name -->
                    <p class="text-muted small mb-3">
                        {{ optional($design->variant?->service)->name ?? '—' }}
                    </p>

                    <!-- Action -->
                    <button class="btn btn-sm btn-soft-info">
                        View Design
                    </button>

                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">No designs available</p>
        </div>
        @endforelse
    </div>

</div>

@endsection

@section('script')
@endsection
