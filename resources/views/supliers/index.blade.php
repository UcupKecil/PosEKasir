@extends('layouts.admin')

@section('title', 'Daftar Suplier')
@section('content-header', 'Daftar Suplier')
@section('content-actions')

<a href="{{route('supliers.create')}}" class="btn btn-primary">Tambah Suplier</a>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')



{{-- Konten utama --}}
<section class="content">

    {{-- Box default --}}

    <div class="card">
        <div class="card-header">
          <h3 class="card-title">Suplier</h3>


        </div>
        <div class="card-body">
          <div class="table-responsive">

              <table class="table table-bordered yajra-datatable">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Nama</th>
                          <th>Alamat</th>
                          <th>No. Telp</th>
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
        $(document).ready(function () {
            $(document).on('click', '.btn-delete', function () {
                $this = $(this);
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to delete this suplier?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        $.post($this.data('url'), {_method: 'DELETE', _token: '{{csrf_token()}}'}, function (res) {
                            $this.closest('tr').fadeOut(500, function () {
                                $(this).remove();
                            })
                        })
                    }
                })
            })
        });
    </script>
    <script>

        const deleteData = (id) => {
            Swal.fire({
                title: 'Apa anda yakin untuk menghapus suplier ini?',
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
                        url: `supliers/${id}`,
                        dataType: 'json',

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
              ajax: "{{ route('supliers.list') }}",
              columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'name', name: 'name'},
                  {data: 'address', name:'address'},
                  {data: 'phone', name: 'phone'},
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
    </section>
@endsection
