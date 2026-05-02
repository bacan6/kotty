<select class="select2" name="id_customer">
    <option value="">--Pilih Customer--</option>

    <?php
        foreach($customer as $cs){
    ?>
        <option value="<?php echo $cs->id_customer; ?>"><?php echo $cs->nama; ?></option>
    <?php } ?>
</select>

