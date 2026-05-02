<div class="wraper container-fluid">
    <div class="page-title"> 
        <h3 class="title"><i class="fa fa-book"></i> Daftar Retur</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
 						<table class="table table-striped" id="myTable">
 							<thead>
 								<tr style="background: #2A303A;color:white;">
 									<td width="5%">No</td>
 									<td width="15%">No Retur</td>
 									<td>Tanggal</td>
 									<td>PIC</td>
 									<td>Retur Dari</td>
 								</tr>
 							</thead>

 							<tbody>
 								<?php
 									$i = 1;
 									foreach($dataRetur as $row){
 								?>
 								<tr>
 									<td><?php echo $i; ?></td>
 									<td><a href="<?php echo base_url('retur/invReturPerStore?noRetur='.$row->NoRetur); ?>"><?php echo $row->NoRetur; ?></a></td>
 									<td><?php echo date_format(date_create($row->tanggal),"d M Y H:i"); ?></td>
 									<td><?php echo $row->first_name; ?></td>
 									<td><?php echo $row->store; ?></td>
 								</tr>
 								<?php $i++; } ?>
 							</tbody>
 						</table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
