@extends('layouts.admin')

@section('title', 'Tambah Member')
@section('content-header', 'Tambah Member')

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- <div class="form-group">
                <label for="avatar">Foto</label>
                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" id="avatar"
                    placeholder="Foto member" value="{{ old('avatar') }}">
                @error('avatar')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div> --}}

            <div class="form-group">
                <label for="first_name">Nama Depan</label>
                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                    placeholder="Nama Depan" value="{{ old('first_name') }}">
                @error('first_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Nama Belakang</label>
                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                    placeholder="Nama Belakang" value="{{ old('last_name') }}">
                @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    placeholder="Email" value="{{ old('email') }}">
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">No. Telepon</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone"
                    placeholder="Nomor Telepon" value="{{ old('phone') }}">
                @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" id="address"
                    placeholder="Alamat" value="{{ old('address') }}">
                @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Create</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
</script>
@endsection
