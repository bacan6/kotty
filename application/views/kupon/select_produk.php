<select class="form-control" name="produk">
      <option value="">-Pilih-</option>
      <?php foreach($produk as $p){ ?>
       <option value="<?php echo $p['id_produk']; ?>"><?php echo $p['nama_produk']; ?></option>
       <?php } ?>
</select>
