@extends('layouts.template')

@section('page')
    {{$page}}
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-title">
                <h4>Edit Pelanggan</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form method="POST" action="{{url('/pelanggan/'.$pelanggan->id)}}">   
                    {{ csrf_field() }}     
                    {{ method_field('PATCH') }}            
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>NIK</label>
                                <input value="{{$pelanggan->nik}}" name="nik" type="number" class="form-control" placeholder="NIK">
                            </div>
                            <div class="form-group">
                                <label>No Rek</label>
                                <input value="{{$pelanggan->no_rek}}" name="no_rek" type="number" class="form-control" placeholder="No Rekening">
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input value="{{$pelanggan->nama}}" name="nama" type="text" class="form-control" placeholder="Nama">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Telp</label>
                                <input value="{{$pelanggan->telp}}" name="telp" type="number" class="form-control" placeholder="Telp">
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat">{{$pelanggan->alamat}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Chat Id</label>
                                <input value="{{$pelanggan->chat_id}}" name="chat_id" type="text" class="form-control" placeholder="Chat Id">
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection