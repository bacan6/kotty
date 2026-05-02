    <select class="form-control" name="sub_account" id="subAccount">
    	<?php
    		foreach($sub_account->result() as $row){
    	?>
    	<option value="<?php echo $row->id_payment_account; ?>"><?php echo $row->account; ?></option>
    	<?php } ?>
    </select>