@extends('layouts.template')
@section('page')
    {{$page}}
@endsection
@section('content')
@if (session()->has('success'))
<script>
$( document ).ready(function(){
  toastr.success("{{ session('success') }}", 'Pelanggan', {
    timeOut: 5000,
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "tapToDismiss": false

  });
});
</script>
@endif
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
                                <th>Action</th>
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
                                <th>Action</th>
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
                                <td>
                                    <a href="{{url('pelanggan/'.$pelanggan->id)}}" class="btn btn-info m-b-10 m-l-5">Edit</a>
                                    
                                    <form style="display:inline-block" action="{{ url('/pelanggan', ['id' => $pelanggan->id]) }}" method="post" >
                                        <input type="hidden" name="_method" value="delete" />
                                        {!! csrf_field() !!}
                                        <button class="btn btn-danger m-b-10 m-l-5">Hapus</button>
                                    </form>
                                </td>
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