<select name="brand" class="form-control">
        <option value="">Brand</option>
        <?php foreach($brand as $b){ ?>
        <option value="<?php echo $b->id_brand; ?>"><?php echo $b->nm_brand; ?></option>
        <?php } ?>
</select>
