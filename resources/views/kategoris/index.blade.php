@extends('layouts.admin')


@section('title', 'Daftar Kategori')
@section('content-header', 'Daftar Kategori')
@section('content-actions')
@can('create_berita')


@endcan
<a href="{{route('kategoris.create')}}" class="btn btn-primary">Tambah Kategori</a>

@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endsection
@section('content')




@section('content-actions')


@endsection

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Berita</h3>


      </div>
      <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered yajra-datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>

                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
      </div>
    </div>






  </section>
  <!-- /.content -->
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>

    const deleteData = (id) => {
        Swal.fire({
            title: 'Apa anda yakin untuk menghapus ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            Swal.close();

            if(result.value) {
                Swal.fire({
                    title: 'Mohon tunggu',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    }
                });


                $.ajax({
                    type:"DELETE",
                    url: `kategoris/${id}`,
                    dataType: 'json',
                    //type: "delete",
                    //url: `/admin/kategoris/${id}`,
                    //dataType: "json",

                    success: function(res) {
                        Swal.close();

                        if(res.status) {
                            Swal.fire(
                                'Success!',
                                res.msg,
                                'success'
                            )

                            $('.yajra-datatable').DataTable().ajax.reload();
                        } else {
                            Swal.fire(
                                'Error!',
                                res.msg,
                                'warning'
                            )
                        }
                    }
                });
            }
        });
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  $(function () {
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('kategoris.list') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},

            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true
            },
        ]
    });

  });
</script>




@endsection
