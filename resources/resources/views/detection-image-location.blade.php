@extends('layouts.tabler')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mt-5 mb-3">
        <h1 class="h3 mb-0 text-gray-800">Select Location</h1>
    </div>

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        @foreach($location as $d)
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <a href="{{ route('detection-image', $d->id) }}" class="text-decoration-none">
                        <div class="card-image fixed-size">
                            <img src="{{ asset('storage/location_image/' . $d->location_image) }}" alt="{{ $d->name_location }}" class="img-fluid">
                        </div>
                    </a>
                </div>
                <p class="text-center mt-3 font-weight-bold text-gray-800">{{ $d->name_location }}</p>
            </div>
        @endforeach
    </div>
@endsection