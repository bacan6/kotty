<?php //die('created by Arisal Y.');
//die();
error_reporting(0);
ini_set('display_errors',0);
    $db_username = "gbuser8B";
    $db_hostname = "localhost";
    $db_password = "dMRGkWZ6qzOv4wAC";
    $db_name = "glamindoF1";
 

  $con = mysqli_connect($db_hostname, $db_username, $db_password) or die('tidak dapat terhubung ke server database');
  $db  = mysqli_select_db($con,$db_name);

  $s = "SELECT no_invoice,id_produk,COUNT(id_produk) as rec,qty
            FROM ap_invoice_item
            where tanggal BETWEEN '2022-05-26' and '2022-06-10'
            GROUP BY no_invoice,id_produk
            HAVING COUNT(id_produk) >1";
  $r = mysqli_query($con,$s);$id_produk_sebelumnya='';
  $data = array();
  while ($w = mysqli_fetch_array($r)){
    $limit = $w['rec']-1;
    $add = $w['qty']*$limit;

    echo "DELETE from ap_invoice_item where no_invoice='$w[no_invoice]' and id_produk='$w[id_produk]' limit $limit; <br>";
    echo "UPDATE stok_store set stok=(stok+$add) where id_produk='$w[id_produk]' and id_store=7;<br>";
      
    //   if($id_produk_sebelumnya!='' && $id_produk_sebelumnya==$w['id_produk']){
    //      //echo $w['id_produk'];
    //      $data[$id_produk_sebelumnya]+= $w['qty'];
    //   }
    //   if (empty($id_produk_sebelumnya) || $id_produk_sebelumnya != $w['id_produk']) $id_produk_sebelumnya = $w['id_produk'];
  }

//   foreach ($data as $key => $value) {
//       echo "$key~$value<br>";
//   }
