<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Data Customer</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo base_url('customer/add_customer'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Customer</a>
                    </div>

                    <div class="col-md-6" align="right">
                        <form action="<?php echo base_url('customer'); ?>" method="get">
                            <div class="input-group" style="width: 50%;">
                                <input type="text" id="example-input1-group2" name="query" class="form-control" placeholder="Search">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-effect-ripple btn-primary"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row" style="margin-top: 30px;">
            		<div class="col-md-12">
                        <?php 
                            if($this->session->userdata("message")!=NULL){
                        ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php
                                    echo $this->session->userdata("message");
                                ?>      
                            </div>

                        <?php
                            }
                        ?>

                        <?php 
                            if($this->session->userdata("message2")!=NULL){
                        ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php
                                    echo $this->session->userdata("message2");
                                ?>      
                            </div>

                        <?php
                            }
                        ?>
            			<table class="table table-bordered" id="datatable" style="font-size: 10px;">
                            <thead>
            				<tr style="font-weight: bold;">
            					<td width="5%" align="center">No</td>
            					<td width="20%">Nama</td>
                                <td>Kontak</td>
            					<td>Tgl Lahir</td>
            					<td width="10%">Tanggal Input</td>
            					<td width="25%">Alamat</td>
            					<td>Kategori</td>
                                <td width="10%">Diskon (%)</td>
            					<td width="10%">Point</td>
                                <td width="10%">Aktif?</td>
                                <td width="12%"></td>
            				</tr>
</thead>
<tbody>
                            <?php 
                                if(empty($this->uri->segment(3))){
                                    $i=0+1;
                                } else {
                                    $i=$this->uri->segment(3)+1;
                                }

                                foreach($customer->result() as $row){
                            ?>
            				<tr <?php echo ($row->activated=='1'? "":"bgcolor=grey"); ?>>
            					<td align="center"><?php echo $i; ?></td>
            					<td><?php echo $row->nama; ?></td>
            					<td><?php echo $row->kontak; ?></td>
                                <td><?php echo $row->tanggal_lahir; ?></td>
            					<td><?php echo date_format(date_create($row->tanggal_gabung),'d M Y'); ?></td>
            					<td><?php echo $row->alamat." - ".$row->nama_provinsi." - ".$row->nama_kabupaten." - ".$row->kecamatan; ?></td>
            					<td><?php echo $row->group_customer; ?></td>
                                <td><?php echo $row->diskon; ?></td>
            					<td><?php echo $row->point; ?></td>
                                <td><?php echo ($row->activated=='1'? "Ya":"Tidak"); ?></td>
                                <td align="center"><a href="<?php echo base_url('customer/hapus_customer?id='.$row->id_customer); ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-icon btn-danger m-b-5"><i class="fa fa-trash"></i></a> <a href="<?php echo base_url('customer/edit_customer?id='.$row->id_customer); ?>" class="btn btn-icon btn-info m-b-5"><i class="fa fa-pencil"></i></a></td>
            				</tr>
                            <?php $i++; } ?>
                            </tbody>
            			</table>
            		</div>
            	</div>

                <div  class="row" style="text-align:center;">
                    <?php
                        echo $paging;
                    ?>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<script src="<?php echo base_url('assets'); ?>/js/jquery.js"></script>
<script src="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/dataTables.bootstrap.js"></script>
<script type="text/javascript">

  jQuery('#datatable').dataTable();
  
  </script>

