@extends('layouts.template')
@section('page')
    {{$page}}
@endsection
@section('content')
@if (session()->has('success'))
<script>
$( document ).ready(function(){
  toastr.success("{{ session('success') }}", 'Pembayaran', {
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
                                <th>No Rekening</th>
                                <th>Nama Pelanggan</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Tagihan</th>
                                <th>Bukti Trf</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach($pembayarans as $no => $pembayaran)
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{$pembayaran->pelanggan->no_rek}}</td>
                                <td>{{$pembayaran->pelanggan->nama}}</td>
                                <td>{{DateTime::createFromFormat('!m', $pembayaran->bulan)->format('F')}}</td>
                                <td>{{$pembayaran->tahun}}</td>
                                <td>Rp{{number_format($pembayaran->jumlah_tagihan)}}</td>
                                <td>
                                <img src="https://res.cloudinary.com/wahyupermadie/image/upload/{{$pembayaran->bukti_trf}}.jpg" style="width:150px !important; height: 150px !important">
                                </td>
                                <td>
                                @if($pembayaran->flag == 'just_arrived')
                                    <span class="badge badge-danger">Belum Bayar</span>
                                @elseif($pembayaran->flag == 'processed')
                                    <span class="badge badge-warning">Sedang Di Proses</span>
                                @elseif($pembayaran->flag == 'gagal')
                                    <span class="badge badge-primary">Gagal</span>
                                @else 
                                    <span class="badge badge-success">Lunas</span>
                                @endif
                                </td>
                                <td>
                                    @if($pembayaran->flag == 'completed')
                                        <button class="btnAccept btn btn-primary">LUNAS</button>
                                    @else
                                        <button id="btnAccept" dataId={{$pembayaran->id}} dataType="success" class="btnAccept btn btn-success">Accept</button>
                                        <button id="btnDecline" dataId={{$pembayaran->id}} dataType="cancel" class="btnDecline btn btn-danger">Decline</button>
                                    @endif
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
<script>
    $(".btnAccept").on('click',function(e){
        var id = $(this).attr("dataId")
        var url = "{{url('pembayaran/update')}}"+"/"+id
        var dataType = $(this).attr("dataType")
        // alert(dataType)
        $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'PATCH',
            url:"{{url('pembayaran/update')}}"+"/"+id,
            data:{jenis:dataType},
            success: function(data){
                console.log(data)
            },
            error: function(xhr, status, error) {
                
            }
        })
    })
    $(".btnDecline").on('click',function(e){
        var id = $(this).attr("dataId")
        var url = "{{url('pembayaran/update')}}"+"/"+id
        var dataType = $(this).attr("dataType")
        // alert(dataType)
        $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'PATCH',
            url:"{{url('pembayaran/update')}}"+"/"+id,
            data:{jenis:dataType},
            success: function(data){
                console.log(data)
            },
            error: function(xhr, status, error) {
                
            }
        })
    })

    function refreshTable(){
        setTimeout(function(){
            $.ajax({
                url: "{{url('get/pembayaran/all')}}",
            }).done(function(data) {
                $("#tableBody").html(data);
                refreshTable();
            });
        },
        1000);
    }

    $(document).ready(function(){
        refreshTable();
    });
</script>
@endsection