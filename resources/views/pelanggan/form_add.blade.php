@extends('layouts.template')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-title">
                <h4>Registrasi Pelanggan</h4>

            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form>
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="checkbox">
                            <label>
                                    <input type="checkbox"> Check me out
                                </label>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection