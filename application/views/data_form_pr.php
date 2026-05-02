<tr id="row_request<?php echo $no; ?>" height="40px">
	<td width="30%"><input type="text" class="form-control" placeholder="Harga" name="harga_request[]" width="90%" required/></td>
	<td width="35%">
		<input type="hidden" class="supplierAjax" name="supplier[]" style="width:100%;" />
	</td>
	<td><input type="text" class="form-control" width="90%" placeholder="Remark" name="remark[]"/></td>
	<td width="5%" align="center"><a class="hapus_data_form" id="<?php echo $no; ?>"><i class="fa fa-trash"></i></a></td>
</tr>

<script type="text/javascript">
	        $('.supplierAjax').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('purchase_request/supplierAjax'); ?>',
                    dataType    : 'json',
                    quietMillis : 100,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params
                        };
                    },
                    results: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({    
                                'id': item.id,
                                'text': item.text
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
                minimumInputLength: 1,
            });

	$('.hapus_data_form').on("click",function(){
		id = this.id;

		$('#row_request'+id).remove();
	});
</script>

