@extends('layouts.template')
@section('page')
    {{$page}}
@endsection
@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="card p-30">
            <div class="media">
                <div class="media-left meida media-middle">
                    <span><i class="fa fa-usd f-s-40 color-danger"></i></span>
                </div>
                <div class="media-body media-text-right">
                    <h2>{{number_format($total_tagihan)}}</h2>
                    <p class="m-b-0">Total Tagihan</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-30">
            <div class="media">
                <div class="media-left meida media-middle">
                    <span><i class="fa fa-usd f-s-40 color-primary"></i></span>
                </div>
                <div class="media-body media-text-right">
                    <h2>{{number_format($total_bayar)}}</h2>
                    <p class="m-b-0">Total Pembayaran</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-30">
            <div class="media">
                <div class="media-left meida media-middle">
                    <span><i class="fa fa-user f-s-40 color-warning"></i></span>
                </div>
                <div class="media-body media-text-right">
                    <h2>{{$total_pelanggan}}</h2>
                    <p class="m-b-0">Pelanggan</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-30">
            <div class="media">
                <div class="media-left meida media-middle">
                    <span><i class="fa fa-plug f-s-40 color-success"></i></span>
                </div>
                <div class="media-body media-text-right">
                    <h2>{{$total_bot}}</h2>
                    <p class="m-b-0">Terkoneksi Bot</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12"> 
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Pembayaran</h4>
                <h6 class="card-subtitle">Data pembayaran bulan ini</h6>
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>No Rekening</th>
                                <th>Nama Pelanggan</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Tagihan</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
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
                                @if($pembayaran->status_bayar)
                                    <span class="badge badge-success">Lunas</span>
                                @else
                                    <span class="badge badge-warning">Belum Bayar</span>
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