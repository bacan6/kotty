                   <html>
                   <head>
                   	<title>Cetak Retur</title>
                   	<script type="text/javascript">
                   		window.print();
                   	</script>
                   </head>
                   <body>
                    <!--<img src="<?php echo base_url('assets/Batik-Salma-Cirebon.png'); ?>" style="margin-left:auto;margin-right:auto;display:block;height:80px;"/>-->
                    <?php
                        foreach($receipt->result() as $cf){
                    ?>
                    <h5 align="center"><?php echo $cf->store; ?></h5>
                    <h5 align="center"><?php echo $cf->alamat; ?></h5>
                    <h5 align="center"><?php echo $cf->kontak; ?></h5>
                	<?php } ?>
                     
                    <center>

                    <table>
                    	<tr>
                    		<td colspan="4">
                    			<b>No Retur : <?php echo $_GET['noRetur']; ?></b>
                    		</td>
                    	</tr>

                        <tr style="border-top:dashed 1px #000;">
                            <td width="160px"></td>
                            <td width="20px"></td>
                            <td width="80px" align="right"></td>
                            <td align="right" width="80px"></td>
                        </tr>

                        <?php
                        	$total = 0;
                        	$diskon = 0;
                            foreach($invoiceItem as $dt){
                        ?>

                        <?php
                        	if($dt->qty > 0){
                        ?>
                        
                        <tr>
                            <td colspan="4"><?php echo $dt->nama_produk; ?></td> 
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2"><?php echo number_format($dt->harga,'0',',','.'); ?></td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->qty; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->harga*$dt->qty),'0',',','.')?></td>    
                        </tr>

                        

                        <?php
                            if($dt->diskon > 0){
                        ?>
                        <tr>
                            <td colspan="3" align="right">Diskon</td>
                            <td align="right">(<?php echo number_format($dt->diskon,'0',',','.'); ?>)</td>
                        </tr>
                    	<?php } } $diskon = $diskon+$dt->diskon; $total = $total+(($dt->harga*$dt->qty)); } ?>

                    	<tr>
                    		<td colspan="3" style="border-top: solid 1px black;">TOTAL</td>
                    		<td align="right"  style="border-top: solid 1px black;"><?php echo number_format($total-$diskon,'0',',','.'); ?></td>
                    	</tr>

                    </table>
                </center>
            </body>
                </html>