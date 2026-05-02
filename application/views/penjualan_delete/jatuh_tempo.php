<div class="input-group">
    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    <input type='text' class='form-control datepicker' name="jatuh_tempo" placeholder='Jatuh Tempo' id='jatuhTempo'/>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
        jQuery('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            autoclose : true
        });
    });
</script>