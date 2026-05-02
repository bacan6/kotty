<div class="wraper container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="page-title"> 
				<h3 class="title">Upload Foto</h3> 
			</div>

			<div class="portlet"><!-- /primary heading -->
		        <div id="portlet2" class="panel-collapse collapse in">
		            <div class="portlet-body">
		                <div class="row" style="margin-top: 20px;">
							<!-- Display status message -->


<div class="col-md-8">
<!-- Display uploaded images -->
<div class="row">
    <h3>Uploaded Files/Images</h3>
    <ul class="gallery" style='list-style-type:none'>
        <?php if(!empty($files)){ foreach($files as $file){ ?>
        <li class="item col-md-3" id="img<?php echo $file['id']?>">
            <img src="<?php echo base_url('uploads/files/'.$file['file_name']); ?>" class="img-responsive">
            <p align=center><?php echo date("j M Y H:i",strtotime($file['uploaded_on'])); ?><br>
            <a class="btn btn-sm btn-danger hapusfile" data-id="<?php echo $file['id']?>" />Hapus</a></p>
        </li>
        <?php } }else{ ?>
        <p>File(s) not found...</p>
        <?php } ?>
    </ul>
</div>
</div>
<div class="col-md-4" align='right'>
<form method="post" action="" enctype="multipart/form-data" style="padding: 10px;">

<?php echo !empty($statusMsg)?'<p class="status-msg">'.$statusMsg.'</p>':''; ?>

<!-- File upload form -->

    <div class="form-group">
        <label>Choose Files</label>
        <input type="file" class="form-control" name="files[]" multiple/>
    </div>
    <div class="form-group">
        <input class="btn btn-info" type="submit" name="fileSubmit" value="UPLOAD"/>
    </div>
</form>
</div>
		                </div>
		            </div>
		        </div>
		    </div> <!-- /Portlet -->    
		</div>
	</div>
</div>
