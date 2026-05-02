    <select class="form-control" name="sub_account" id="subAccount">
    	<?php
		$i = 0;
    		foreach($sub_account->result() as $row){
				$i++;$sel = $i==1?'selected':'';
    	?>
    	<option value="<?php echo $row->id_payment_account; ?>" <?php echo $sel?>><?php echo $row->account; ?></option>
    	<?php } ?>
    </select>