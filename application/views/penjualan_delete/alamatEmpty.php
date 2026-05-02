<div class="col-md-6">
    <form class="form-horizontal" role="form">
        <div class="form-group">
            <label class="col-md-2 control-label">Nama Penerima</label>
            <div class="col-md-10">
                <input type="text" class="form-control" id="namaPenerima">
                <label id="labelNamaCust" style="color:red;"></label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">No HP</label>
            <div class="col-md-10">
                <input type="text" class="form-control" id="kontakPenerima">
                <label id="labelKontak" style="color:red;"></label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">Ekspedisi</label>
            <div class="col-md-10">
                <select class="form-control" id="ekspedisi">
                    <option value="">--Pilih Ekspedisi--</option>
                    <?php
                        foreach($ekspedisi as $eks){
                    ?>
                    <option value="<?php echo $eks->id_ekspedisi; ?>"><?php echo $eks->ekspedisi; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">Ongkir</label>
            <div class="col-md-10">
                <input type="text" id="ongkir" name="ongkir" class="form-control" placeholder="Ongkir" value="<?php if($ongkir > 0){echo $ongkir;} ?>">
            </div>
        </div>
    </form>
</div>

<div class="col-md-6">
    <form class="form-horizontal" role="form">
        <div class="form-group">
            <label class="col-md-2 control-label">Alamat</label>
            <div class="col-md-10">
                <textarea class="form-control" id="alamatPenerima"></textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">Provinsi</label>
            <div class="col-md-10">
                <select class="select2" id="provinsiPenerima">
                    <option value="">--Pilih Provinsi--</option>
                    <?php
                        foreach($provinsi->result() as $prp){
                    ?>
                        <option value="<?php echo $prp->id_provinsi; ?>"><?php echo $prp->nama_provinsi; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">Kabupaten</label>
            <div class="col-md-10">
                <select class="select2" id="kabupatenPenerima">
                    <option value="">--Pilih Kabupaten--</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">Kecamatan</label>
            <div class="col-md-10">
                <select class="select2" id="kecamatanPenerima">
                    <option value="">--Pilih Kecamatan--</option>
                </select>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
            $('#ongkir').on("keyup",function(){
                var ongkir = $(this).val();
                var urlOngkir = "<?php echo base_url('penjualan/insertOngkir'); ?>";

                $.post(urlOngkir,{ongkir : ongkir},function(){
                    var urlViewOngkir = "<?php echo base_url('penjualan/viewOngkir'); ?>";
                    $('#ongkirText').load(urlViewOngkir);
                    viewPricePanel();
                });
            });

            $('#provinsiPenerima').change(function(){
                url = "<?php echo base_url('penjualan/list_kabupaten'); ?>";

                id = $('#provinsiPenerima').val();

                $('#kabupatenPenerima').load(url,{id : id});
            });

            $('#kabupatenPenerima').change(function(){
                url= "<?php echo base_url('penjualan/list_kecamatan'); ?>";
                
                id = $('#kabupatenPenerima').val();

                $('#kecamatanPenerima').load(url,{id : id});
            });
</script>