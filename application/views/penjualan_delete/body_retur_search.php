            <div class="wraper container-fluid">
                    <div class="page-title"> 
                      <h3 class="title">Retur</h3> 
                    </div>
                        <div class="portlet" id="todo-container"><!-- /primary heading -->
                            <div id="portlet-5" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="row">
                                      <div class="col-md-6">
                                        <!--<form action="<?php echo base_url('penjualan/retur_penjualan_sql'); ?>" method="post">-->
                                            <table class="table table-bordered">
                                                <thead>
                                                	<tr>
                                                		<td width="5%" style="font-weight: bold;">No</td>
                                                		<td style="font-weight: bold;">Nama Item</td>
                                                		<td width="15%" style="font-weight: bold;">Qty</td>
                                                		<td width="15%" align="right" style="font-weight: bold;">Harga</td>
                                                	</tr>
                                                </thead>

                                                <tbody>

                                                    <?php 
                                                        $i = 1;
                                                        foreach($data_invoice->result() as $row){
                                                    ?>
                                                	<tr>
                                                		<td><?php echo $i; ?></td>
                                                		<td><?php echo $row->nama_produk; ?></td>
                                                		<td>
                                                            <input type="text" id="produk" placeholder="<?php echo $row->qty; ?>" data-sku="<?php echo $row->id_produk; ?>" data-harga="<?php echo $row->harga_jual; ?>" data-diskon="<?php echo $row->diskon; ?>" class="form-control"/>
                                                        </td>
                                                		<td align="right"><?php echo number_format($row->harga_jual,'0',',','.'); ?></td>
                                                	</tr>
                                                    <?php $i++; } ?>

                                                    <tr>
                                                        <td colspan="4" style="text-align: right;"><button type="submit" id='submit-retur' value="Submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <!--</form>-->
                                      </div>

                                      <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr style="font-weight: bold;">
                                                    <td width="1%">No</td>
                                                    <td>No Retur</td>
                                                    <td>Tanggal Retur</td>
                                                    <td>Keterangan</td>
                                                    <td width="5%"></td>
                                                </tr>
                                            </thead>

                                            <tbody id="invoiceRetur">
                                            </tbody>
                                        </table>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->

