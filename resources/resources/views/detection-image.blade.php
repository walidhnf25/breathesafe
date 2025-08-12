@extends('layouts.tabler')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mt-5 mb-5">
        <h1 class="h3 mb-0 text-gray-800">Location of {{ $location->name_location }}</h1>
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

    <form method="GET" action="{{ route('detection-image', $location->id) }}" class="mb-5">
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

    <div class="row mb-3">
        @foreach($images as $image)
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="{{ asset('storage/detection_image/' . $image->detection_image) }}"
                        class="card-img-top object-fit-cover"
                        style="width: 100%; height: 200px; object-fit: cover;"
                        alt="Detection Image">
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('myscript')
<script>
    flatpickr("#startDate", { dateFormat: "d/m/Y" });
    flatpickr("#endDate", { dateFormat: "d/m/Y" });
</script>
@endpush