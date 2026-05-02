<div class="input-group">
	<span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
	<select class="form-control" name="account">
		<?php
			$account = $this->db->get("ap_piutang_payment_account")->result();

			foreach($account as $dt){
		?>	
	    <option value="<?php echo $dt->id_account; ?>"><?php echo $dt->account; ?></option>
	    <?php } ?>
	</select>
</div>