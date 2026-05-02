<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Tipe Bayar Piutang</h3> 
	</div>

	<div class="row">
		<div class="col-md-6">
		    <div class="portlet"><!-- /primary heading -->
		        <div id="portlet2" class="panel-collapse collapse in">
		        	<div class="portlet-heading">
		                <h3 class="portlet-title text-dark text-uppercase">
		                    Payment Type
		                </h3>
		            </div>

		            <div class="portlet-body">
		            	<div class="row" style="margin-top:15px;">
		            		<div class="col-md-12">
		            			<?php
		            				if($this->session->userdata("message")!=NULL){
		            					echo $this->session->userdata("message");
		            				}
		            			?>
		            			<table width="100%" class="table">
		            				<tr>
			            				<td width="5%">No</td>
			            				<td>Tipe Bayar</td>
		            				</tr>

		            				<?php
		            					$i = 1;
		            					foreach($payment_type as $row){
		            				?>
		            				<tr>
		            					<td><?php echo $i; ?></td>
		            					<td><?php echo $row->payment_type; ?></td>
		            				</tr>
		            				<?php
		            					$account = $this->db->get_where("ap_piutang_payment_account",array("id_payment" => $row->id))->result();

		            					foreach($account as $ac){
		            				?>
		            				<tr>
		            					<td></td>
		            					<td style="padding-left: 30px;"><li><?php echo $ac->account; ?></li></td>
		            				</tr>
		            				<?php } ?>
		            				<?php $i++; } ?>
		            			</table>
		            		</div>
		            	</div>
		            </div>
		        </div>
		    </div> <!-- /Portlet -->	
		</div>

		<div class="col-md-6">
		    <div class="portlet"><!-- /primary heading -->
		        <div id="portlet2" class="panel-collapse collapse in">
		        	<div class="portlet-heading">
		                <h3 class="portlet-title text-dark text-uppercase">
		                    ACCOUNT
		                </h3>
		            </div>

		            <div class="portlet-body">
		            	<div class="row" style="margin-top:15px;">
		            		<form action="<?php echo base_url('parameter/add_account_sql'); ?>" method="post">
			            		<div class="col-md-12">
			            			<div class="form-inline">
			            				<div class="form-group">
			            					<select class="form-control" name="payment_type">
			            						<option value="2">Transfer</option>
			            					</select>
			            				</div>

			            				<div class="form-group">
			            					<input type="text" class="form-control" placeholder="Account" name="account" required/>
			            				</div>

			            				<div class="form-group">
			            					<input type="submit" class="btn btn-primary" value="Submit">
			            				</div>
			            			</div>
			            		</div>
		            		</form>
		            	</div>
		            </div>
		        </div>
		    </div> <!-- /Portlet -->
		</div>
	</div>
</div>
