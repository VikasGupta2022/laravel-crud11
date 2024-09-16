@extends('layouts.app')
<style>
    .card-header {
        border-bottom: none !important; 
        background-color: transparent !important; 
        font-size:1.5rem;
        font-weight:bold;
        text-align:center;
        padding:1.3rem;
        margin-bottom:0.4rem;
    }
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/register') }}">
                        @csrf

                        <div class="form-group" style="margin-top:10px;">
                        <i class="fas fa-user"></i>
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group" style="margin-top:10px;">
                        <i class="fas fa-envelope"></i>
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group" style="margin-top:10px;">
                        <i class="fas fa-lock"></i>
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group" style="margin-top:10px;">
                        <i class="fas fa-lock"></i>
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="margin-top:10px;" >Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
