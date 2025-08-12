@extends('layouts.tabler')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mt-5 mb-3">
        <h1 class="h3 mb-0 text-gray-800">Camera</h1>
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

    <div class="row mb-5">
        <div class="col-md-3">
            <a href="#" class="btn btn-primary" id="btnTambahCamera">
                <i class="fas fa-plus me-1"></i> Add Camera
            </a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>URL RTSP</th>
                        <th>Name Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($Camera as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->ip_camera }}</td>
                            <td>{{ $d->location->name_location ?? '-' }}</td>
                            <td class="action-icons">
                                <a href="#" class="btn btn-sm me-1 btnEditCamera" data-id="{{ $d->id }}" data-ip_camera="{{ $d->ip_camera }}" data-location_id="{{ $d->location_id }}"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('camera.destroy', $d->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm delete-confirm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Location name is missing.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="footer mt-3">
                <div>Total camera: 2</div>
                <div class="pagination-controls d-flex align-items-center gap-2">
                    <div>1 of Items</div>
                    <div class="page-number">1</div>
                    <div class="page-arrow" aria-label="Previous">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="page-arrow" aria-label="Next">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-inputcamera" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Camera</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('camera.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="ip_camera">URL RTSP</label>
                                    <input type="text" class="form-control" id="ip_camera" name="ip_camera"
                                        placeholder="Enter URL RTSP">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="location">Location</label>
                                    <select class="form-control" id="location_id" name="location_id">
                                        <option value="" disabled selected>Select Location</option>
                                        @foreach ($Location as $d)
                                            <option value="{{ $d->id }}">{{ $d->name_location }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary flex-grow-1">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-edit-camera" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Camera</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditCamera" action="" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_ip_camera" class="form-label">IP Camera</label>
                            <input type="text" class="form-control" id="edit_ip_camera" name="ip_camera">
                        </div>
                        <div class="mb-3">
                            <label for="edit_location_id" class="form-label">Name Location</label>
                            <select class="form-control" id="edit_location_id" name="location_id">
                                @foreach ($Location as $d)
                                    <option value="{{ $d->id }}">{{ $d->name_location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success flex-grow-1">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Menampilkan modal untuk menambah tipe pekerjaan
        $("#btnTambahCamera").click(function() {
            $('#modal-inputcamera').modal('show');
        });

        $(document).on("click", ".btnEditCamera", function () {
            let id = $(this).data("id");
            let ip_camera = $(this).data("ip_camera");
            let location_id = $(this).data("location_id");

            $("#edit_id").val(id);
            $("#edit_ip_camera").val(ip_camera);
            $("#edit_location_id").val(location_id);

            // Set action URL pada form edit
            $("#formEditCamera").attr("action", "/camera/update/" + id);

            $("#modal-edit-camera").modal("show");
        });

        // Konfirmasi penghapusan data
        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();

            Swal.fire({
                title: '<span style="color:#f00">Apakah Anda Yakin?</span>',
                html: "<strong>Data ini akan dihapus secara permanen!</strong><br>Anda tidak akan bisa mengembalikan data setelah penghapusan.",
                icon: 'warning',
                iconColor: '#ff6b6b',
                showCancelButton: true,
                background: '#f7f7f7',
                backdrop: `
                rgba(0, 0, 0, 0.4)
                url("https://cdn.pixabay.com/photo/2016/11/18/15/07/red-alert-1837455_960_720.png")
                left top
                no-repeat
            `,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batalkan',
                customClass: {
                    popup: 'animated zoomIn faster',
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire({
                        title: 'Info!',
                        text: 'Data berhasil dihapus.',
                        icon: 'success',
                        background: '#f7f7f7',
                        customClass: {
                            popup: 'animated bounceIn faster',
                        },
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            });
        });

        // Menghilangkan alert setelah beberapa detik
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 2000);
    });
</script>
@endpush