<div class="row">
	<div class="col-md-12">
		<input type="hidden" id="ajax-produk-store" style="width: 100%;" />
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-md-12">
		<form action="<?php echo base_url('retur/returPerStoreSQL'); ?>" method="POST">
			<table class="table table-striped" style="font-size:12px;">
			    <thead>
				   	<tr style="background: #2A303A;color:white;font-weight: bold;">
				        <td>SKU</td>
				        <td>Nama Produk</td>
				        <td>Jumlah Retur</td>
				        <td></td>
				    </tr>
			    </thead>

			    <input type="hidden" id="sdf" value=0>
			    <tbody id="data-input">

			    </tbody>

			    <tfoot>
			        <tr>
			            <td colspan="4" style="text-align: right;">
			            	<input type="hidden" name="idStore" value="<?php echo $idStore; ?>"/>
			            	<input type="submit" class="btn btn-primary" value="Submit">
			            </td>
			        </tr>
			    </tfoot>
			</table>
		</form>
	</div>
</div>

<script type="text/javascript">
	var idStore = "<?php echo $idStore; ?>";

	$('#ajax-produk-store').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('retur/ajaxProdukStore'); ?>',
                    dataType    : 'json',
                    quietMillis : 500,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params,
                            idStore : idStore
                        };
                    },
                    results: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({    
                                'id': item.id,
                                'text': item.text,
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
                minimumInputLength: 3,
     });


	$("#ajax-produk-store").on("click",function(){
        data_form = "<?php echo base_url('retur/data_form'); ?>";
        sku = $(this).val();

        no     = parseInt($('#sdf').val());
        urutan = no+1; 

       $.get(data_form,{no : urutan, sku : sku, idStore : idStore},function(data){
            $('#data-input').append(data);
            $('#sdf').val(urutan);
       });     
	});
</script>