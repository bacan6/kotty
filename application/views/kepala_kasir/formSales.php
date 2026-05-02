<div class="form-group">
    <input type="text" id="nama_bahan" class="form-control" placeholder="Nama Material" />
</div>

<div class="form-group">
    <select class="form-control" id="satuan">
        <option>--Pilih Satuan--</option>
        <?php
        foreach($get_satuan->result() as $st){
        ?>
        <option value="<?php echo $st->satuan; ?>"><?php echo $st->satuan; ?></option>
        <?php } ?>
    </select>
</div>

<div class="form-group">
    <select class="form-control" id="kategori">
        <option>--Pilih Kategori--</option>
        <?php
            foreach($get_kategori->result() as $kt){
        ?>
        <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
        <?php } ?>
    </select>
</div>

<div class="form-group">
    <input type="text" class="form-control" id="harga" placeholder="Harga Satuan"/>
</div>

<div class="form-group">
    <select class="form-control" id="tipe_data">
        <option value="">--Tipe Data--</option>
        <option value="0">Material</option>
        <option value="1">Non Material</option>
        <option value="2">Unfinish Goods</option>
        <option value="3">Finish Goods</option>
    </select>
</div>

<script type="text/javascript">
    $('#submit-bahan-baku').one("click",function(){
        $('#submit-bahan-baku').prop("disabled",true);

        nama_bahan  = $('#nama_bahan').val();
        satuan      = $('#satuan').val();
        kategori    = $('#kategori').val();
        harga       = $('#harga').val();
        tipe_data   = $('#tipe_data').val();

        url = "<?php echo base_url('bahan_baku/insert_bahan_baku'); ?>";
        bahan_baku = "<?php echo base_url('bahan_baku/data_bahan_baku'); ?>";

        $.post(url,{nama_bahan : nama_bahan, satuan : satuan, kategori : kategori, harga : harga, tipe_data : tipe_data},function(){
            $('#data-bahan-baku').load(bahan_baku);
            $('#modal-bahan-baku').modal('hide');
        });          
    }); 
</script>