@extends('layouts.auth')

@section('login')
{{-- <div class="text-center w-50">
    <img src="{{ asset('AdminLTE-2/dist/img/lamb.png') }}" class="img-fluid" alt="..." style="width: 40px height: 40px">
</div>  --}}
<div class="login-box bg-white">
    <div class="login-box-body mt-0">
        <div class="login-logo text-primary text-bold ">
            <img src="{{ asset('AdminLTE-2/dist/img/lamb.png') }}" alt="lamb.png" width="150px">
            <h1><b> Login </b> </h1> </div>
        <div class="form-text text-center mb-2 text-dark" id="basic-addon4"><h5>Masukan Username dan Password</h5></div>
        <!-- /.login-logo -->

        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group has-feedback @error('email') has-error @enderror">
                <input type="email" name="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @error('email')
                    <span class="help-block">Email tidak terdaftar</span>
                    @enderror
                </div>
                <div class="form-group has-feedback @error('password') has-error @enderror">
                    <input type="password" name="password" class="form-control" placeholder="Password required">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @error('password')
                        <span class="help-block">Password Salah dan Harus diisi.</span>
                        @enderror
                    </div>
                            <!-- /.col -->
                            <div class="d-grid gap-2 mt-2">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                    </div>
                    <!-- /.login-box-body -->
                </div>
                <!-- /.login-box -->
@endsection