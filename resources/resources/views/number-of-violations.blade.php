@extends('layouts.tabler')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mt-5 mb-5">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
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

    <form method="GET" action="" class="mb-5">
        <div class="d-flex align-items-end flex-wrap" style="gap: 1rem;">
            <div>
                <label for="start-date" class="form-label">Start Date</label>
                <input type="date" id="start-date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div>
                <label for="end-date" class="form-label">End Date</label>
                <input type="date" id="end-date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-center align-items-center mb-2">
        @foreach($locations as $location)
            <div class="mx-3">
                <div class="building">
                    <!-- Menampilkan bar hanya jika jumlah detection_image lebih dari 0 -->
                    @if($location->detection_image_count > 0)
                        <div class="bar" style="height: {{ $location->detection_image_count * 20 }}px;">
                            {{ $location->detection_image_count }} <!-- Menampilkan jumlah detection image -->
                        </div>
                    @endif
                </div>
                <!-- Menampilkan nama lokasi -->
                <div class="label text-gray-800">{{ $location->name_location }}</div>
            </div>
        @endforeach
    </div>
@endsection