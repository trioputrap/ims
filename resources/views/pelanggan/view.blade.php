@extends('layouts.template')
@section('page')
    {{$page}}
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12"> 
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Pelanggan</h4>
                <h6 class="card-subtitle">Data semua pelanggan</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIK</th>
                                <th>No Rekening</th>
                                <th>Nama Pelanggan</th>
                                <th>Telp</th>
                                <th>Alamat</th>
                                <th>Chat Id</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>NIK</th>
                                <th>No Rekening</th>
                                <th>Nama Pelanggan</th>
                                <th>Telp</th>
                                <th>Alamat</th>
                                <th>Chat Id</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($pelanggans as $no => $pelanggan)
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{$pelanggan->nik}}</td>
                                <td>{{$pelanggan->no_rek}}</td>
                                <td>{{$pelanggan->nama}}</td>
                                <td>{{$pelanggan->telp}}</td>
                                <td>{{$pelanggan->alamat}}</td>
                                <td>{{$pelanggan->chat_id}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection