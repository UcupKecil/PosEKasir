@extends('layouts.admin')

@section('title', 'Pembelians List')
@section('content-header', 'Pembelian List')
@section('content-actions')
    <a href="{{route('cart.index')}}" class="btn btn-primary">Open POS</a>
@endsection
{{-- @section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endsection --}}

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <form action="{{route('pembelians.index')}}">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}" />
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" type="submit">Submit</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Suplier</th>
                    <th>Total</th>
                    <th>Pengeluaran</th>
                    <th>Status</th>
                    <th>Sisa</th>
                    <th>Tanggal</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembelians as $pembelian)
                <tr>
                    <td>{{$pembelian->id}}</td>
                    <td>{{$pembelian->getSuplierName()}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$pembelian->formattedTotal()}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$pembelian->formattedReceivedAmount()}}</td>
                    <td>
                        @if($pembelian->receivedAmount() == 0)
                            <span class="badge badge-danger">Belum Bayar</span>
                        @elseif($pembelian->receivedAmount() < $pembelian->total())
                            <span class="badge badge-warning">Dicicil</span>
                        @elseif($pembelian->receivedAmount() == $pembelian->total())
                            <span class="badge badge-success">Lunas</span>
                        @elseif($pembelian->receivedAmount() > $pembelian->total())
                            <span class="badge badge-info">Pengembalian</span>
                        @endif
                    </td>
                    <td>{{config('settings.currency_symbol')}} {{number_format($pembelian->total() - $pembelian->receivedAmount(), 2)}}</td>
                    <td>{{$pembelian->created_at}}</td>
                    <td>
                        <a class="btn btn-default" href="{{route('cetaknotasuplier' , $pembelian->id)}}" target="_blank"><i class="fa fa-print"></i> Struk</a>


                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($total, 2) }}</th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($receivedAmount, 2) }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        {{ $pembelians->render() }}
    </div>
</div>
@endsection
<!-- Main content -->
{{-- <section class="content">

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
                        <th>Nama Konsumen</th>
                        {{-- <th>Total</th> --}}
                        {{-- <th>Dibayar</th> --}}



                        {{-- <th>Tindakan</th> --}}
                    {{-- </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
      </div>
    </div>
</section> --}}

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
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
        ajax: "{{ route('pembelians.list') }}",
        columns: [
            {data: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'first_name', name: 'first_name'},




            {
                data: 'total',
                name: 'total',
                orderable: true,
                searchable: true
            },

            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true
            },
        ]
    });

  });
</script> --}}

