<div class="input-group">
    <span class="input-group-addon"><i class="fa fa-cc"></i></span>
    <select class="form-control" name="sub_account" id="subAccount" onchange="javascript:viewPricePanel();">
    	<option> - Pilih -</option>
		<?php
		$i = 0;
    		foreach($sub_account->result() as $row){
				$i++;
				//$sel = $i==1?'selected':'';
    	?>
    	<option value="<?php echo $row->id_payment_account; ?>"><?php echo $row->account; ?></option>
    	<?php } ?>
    </select>
</div>
<button class="btn btn-con btn-info btn-rounded m-t-5" href="#bsiModal" data-toggle="modal" id="btnBSI" style="display:none"><i class="fa fa-qrcode"></i> Input Kartu</button>