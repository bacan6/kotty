<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
              Komposisi Produk
            </h3>
            
            <div class="portlet-widgets">
                <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                <span class="divider"></span>
                <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">

            	<div class="row">
            		<div class="col-md-12" align="right">
            			<form action="<?php echo base_url('komposisi_produk'); ?>" method="get">
                            <div class="input-group" style="width: 30%;">
                                <input type="text" id="example-input1-group2" name="query" class="form-control" placeholder="Search">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-effect-ripple btn-primary"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
            		</div>
            	</div>

                <div class="row" style="margin-top: 20px;">
                	<div class="col-md-12">
                		<table class="table table-bordered table-striped" style="font-size: 12px;">
                			<tr style="background: #2A303A;color:white;font-weight: bold;">
                				<td width="5%" align="center">No</td>
                                <td width="10%">SKU</td>
                				<td>Nama Produk</td>
                				<td>Kategori</td>
                				<td>Harga Jual</td>
                				<td align="right">Stok</td>
                				<td width="3%"></td>
                			</tr>

                			<?php
                                if(empty($this->uri->segment(3))){
                				    $i=1;
                                } else {
                                    $i=$this->uri->segment(3)+1;
                                }

                				foreach($produk->result() as $row){
                			?>
                			<tr>
                				<td align="center"><?php echo $i; ?></td>
                                <td><?php echo $row->id_produk; ?></td>
                				<td><?php echo $row->nama_produk; ?></td>
                				<td><?php echo $row->kategori; ?></td>
                				<td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
                				<td align="right"><?php echo $row->stok; ?></td>
                				<td style="text-align: center;"><a href="<?php echo base_url('komposisi_produk/edit_produk?id_produk='.$row->id_produk); ?>"><i class="fa fa-edit"></i></a></td>
                			</tr>
                			<?php $i++; } ?>
                		</table>
                	</div>
                </div>

                <div class="row" align="center">
                    <?php echo $paging; ?>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
