@extends('layouts.admin')


@section('title', 'Histori Stok')
@section('content-header', 'Histori Stok')
@section('content-actions')


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
        <h3 class="card-title">Histori Stok</h3>


      </div>
      <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered yajra-datatable">
                <thead>
                    <tr>
                        <th>No</th>

                        <th>Stok</th>
                        <th>Produk</th>
                        <th>IN</th>
                        <th>Out</th>
                        <th>By</th>
                        <th>Waktu</th>
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
        ajax: "{{ route('history_stoks.list') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'stok', name: 'stok'},
            {data: 'name', name: 'name'},
            {data: 'masuk', name: 'masuk'},
            {data: 'keluar', name: 'keluar'},
            {data: 'user_id', name: 'user_id'},
            {data: 'waktu', name: 'waktu'},
        ]
    });

  });
</script>




@endsection
