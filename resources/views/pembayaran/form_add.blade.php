@extends('layouts.template')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-title">
                <h4>Tambah Pembayaran</h4>

            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form id="form_update" action="" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <div class="form-group">
                            <label>Pelanggan</label>
                            <select id="pelanggan_id" name="pelanggan_id" class="form-control" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggans as $pelanggan)
                                <option value="{{$pelanggan->id}}">{{$pelanggan->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun</label>
                            <select id="tahun" name="tahun" class="form-control" disabled required>
                                <option value="">Pilih Tahun</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bulan</label>
                            <select id="bulan" name="bulan" class="form-control" disabled required>
                                <option value="">Pilih Bulan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tagihan</label>
                            <input id="tagihan" type="number" class="form-control" placeholder="Tagihan" disabled>
                        </div>
                        <button type="submit" class="btn btn-default">Bayar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#pelanggan_id").on('change', function(e){
        var str="<option value=''>Pilih Tahun</option>"
        if($(this).val()==""){
            $("#tahun").prop('disabled',true);
            $("#tahun").html(str);
        } else {
            $.ajax({
                url: "{{url('pembayaran/')}}/"+$(this).val(),
            }).done(function(data) {
                $("#tahun").html("");
                data = JSON.parse(data);
                data.forEach(function(item, index){
                    str+="<option value='"+item+"'>"+item+"</option>"
                });
                $("#tahun").prop('disabled',false);
                $("#tahun").html(str);
            }).fail(function(){
                $("#tahun").prop('disabled',true);
                $("#tahun").html(str);
                $("#tahun").val("");
            });
        }
        
        $("#tahun").val("");
        $("#bulan").prop('disabled',true);
        $("#bulan").html("<option value=''>Pilih Bulan</option>");
        $("#tagihan").val("");
    });

    $("#tahun").on('change', function(e){
        var str="<option value=''>Pilih Bulan</option>";
        $.ajax({
            url: "{{url('pembayaran/')}}/"+$("#pelanggan_id").val()+"/"+$(this).val()+"/months",
        }).done(function(data) {
            $("#bulan").html("");
            data = JSON.parse(data);
            data.forEach(function(item, index){
                str+="<option value='"+item+"'>"+item+"</option>"
            });
            $("#bulan").prop( "disabled", false );
            $("#bulan").html(str);
        }).fail(function(){
            $("#bulan").prop( "disabled", true );
            $("#bulan").html(str);
        });
        $("#tagihan").val("");
    });
    
    $("#bulan").on('change', function(e){
        $.ajax({
            url: "{{url('pembayaran/total')}}/"+$("#pelanggan_id").val()+"/"+$("#tahun").val()+"/"+$(this).val(),
        }).done(function(data) {
            data = JSON.parse(data);
            $("#tagihan").val(data[0].jumlah_tagihan);

            $("#form_update").attr('action',"{{url('pembayaran/update')}}"+"/"+data[0].id);
        }).fail(function(){
            $("#tagihan").val("");
            url = $("#form_update").attr("action","{{url('pembayaran/update')}}");
        });
    });
</script>
@endsection