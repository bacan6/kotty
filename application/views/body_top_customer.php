<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Top Customer</h3> 
    </div>

    <div class="portlet">
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <form method="get" action="<?php echo base_url('laporan/top_customer'); ?>" id="formTopCustomer">
                <div class="row">
                  <div class="col-md-4">
                    <div class="panel panel-default" style="margin-bottom:12px;">
                      <div class="panel-heading"><strong>Periode &amp; toko</strong></div>
                      <div class="panel-body">
                        <div class="form-group">
                          <label class="small text-muted">Tanggal mulai</label>
                          <input type="text" name="dateStart" readonly value="<?php echo htmlspecialchars($date_start,ENT_QUOTES,'UTF-8');?>" class="form-control input-sm datepicker" required>
                        </div>
                        <div class="form-group">
                          <label class="small text-muted">Tanggal akhir</label>
                          <input type="text" name="dateEnd" readonly value="<?php echo htmlspecialchars($date_end,ENT_QUOTES,'UTF-8');?>" class="form-control input-sm datepicker" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                          <label class="small text-muted">Toko</label>
                          <select class="select2" name="id_toko" style="width:100%">
                            <option value="">- Semua -</option>
                            <?php foreach($store as $st){
                              $sel = $st->id_store==$id_toko?'selected':'';
                              if ($isAdmin==1){
                                if ($idStore==$st->id_store){ ?>
                            <option value="<?php echo $st->id_store; ?>" <?php echo $sel?>><?php echo htmlspecialchars($st->store); ?></option>
                            <?php }
                              }else{ ?>
                            <option value="<?php echo $st->id_store; ?>" <?php echo $sel?>><?php echo htmlspecialchars($st->store); ?></option>
                            <?php }
                            }?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="panel panel-default" style="margin-bottom:12px;">
                      <div class="panel-heading"><strong>Total harga (basis)</strong></div>
                      <div class="panel-body">
                        <div class="radio" style="margin-top:0;">
                          <label>
                            <input type="radio" name="totals_mode" value="global" <?php echo (empty($totals_mode) || $totals_mode!=='peritem')?'checked':''; ?>>
                            Global — net dari <code>ap_invoice_number</code> (subtotal − diskon channel − diskon − poin)
                          </label>
                        </div>
                        <div class="radio" style="margin-bottom:0;">
                          <label>
                            <input type="radio" name="totals_mode" value="peritem" <?php echo (!empty($totals_mode) && $totals_mode==='peritem')?'checked':''; ?>>
                            Per item — Σ baris <code>ap_invoice_item</code> (qty×harga − diskon − tebusmurah); jika filter brand aktif, hanya baris brand terpilih
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="panel panel-default" style="margin-bottom:12px;">
                      <div class="panel-heading"><strong>Brand &amp; tipe customer</strong></div>
                      <div class="panel-body">
                        <div class="form-group">
                          <select class="select2" name="id_brand[]" multiple="multiple" data-placeholder="Brand" style="width:100%">
                            <?php foreach($brands as $b){
                              $sel = in_array((int)$b->id_brand,$id_brand_sel)?'selected':'';
                            ?>
                            <option value="<?php echo (int)$b->id_brand; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($b->brand); ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                          <select class="select2" name="id_group[]" multiple="multiple" data-placeholder="Tipe customer" style="width:100%">
                            <?php foreach($customer_groups as $g){
                              $sel = in_array((int)$g->id_group,$id_group_sel)?'selected':'';
                            ?>
                            <option value="<?php echo (int)$g->id_group; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($g->group_customer); ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-info" id="viewReport">Submit</button>
                    <div class="btn-group">
                      <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Export CSV <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li><a href="#" class="topcust-csv" data-delim=",">Delimiter: koma (,)</a></li>
                        <li><a href="#" class="topcust-csv" data-delim=";">Delimiter: titik koma (;)</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
                </form>
                <div class="row" style="margin-top: 24px;">
                  <div class="col-md-12">

                  	<h3 style="text-align: center;">Laporan Pembelian Terbanyak</h3>
                    <h4 style="text-align: center;">Berdasarkan Jumlah Belanja Customer</h4>
                    <?php if (!empty($totals_mode) && $totals_mode === 'peritem') { ?>
                    <p class="text-center text-muted" style="font-size:12px;">Kolom <strong>Total</strong>: agregat net per baris item (bukan header invoice).</p>
                    <?php } ?>
                    
                  	<table class="table table-striped" style="font-size:11px;">
                      <tr style="background: #2A303A;color:white;font-weight: bold;">
                        <td width="3%">No</td>
                        <td width="13%">Nama Customer</td>
                        <td>ID Customer</td>
                        <td>Kontak</td>
                        <td align="right">Subtotal</td>
                        <td align="right">Diskon Channel</td>
                        <td align="right">Diskon</td>
                        <td align="right">Poin Reimburs</td>
                        <td align="right">Diskon Peritem</td>
                        <td align="right">Total</td>
                        <td align="center">Kedatangan</td>
                        <td align="center">Transaksi Terakhir</td>
                       </tr>

                       <?php
                       	if(empty($this->uri->segment(3))){
                                    $i = 0+1;
                                } else {
                                    $i = $this->uri->segment(3)+1;
                                }
                       	foreach($top_customer as $row){
                       ?>
                       <tr>
                       	<td><?php echo $i; ?></td>
                       	<td><?php echo $row->nama; ?></td>
                        <td><?php echo $row->id_customer; ?></td>
                        <td><?php echo $row->kontak; ?></td>
                       	<td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
                       	<td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                       	<td align="right"><?php echo number_format($row->diskon_free,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->poin_value,'0',',','.'); ?></td>
                       	<td align="right"><?php echo number_format($row->diskon_otomatis,'0',',','.'); ?></td>
                       	<td align="right"><?php echo number_format($row->grand_total,'0',',','.'); ?></td>
                        <td align="center"><?php echo $row->presence; ?></td> 
                        <td><?php echo $row->last_transaction; ?></td>
                       </tr>
                       <?php $i++; } ?>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">window._topCustCsvBase='<?php echo base_url('laporan/export_csv_top_customer'); ?>';</script>
