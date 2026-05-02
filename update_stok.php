<?php //die('created by Arisal Y.');
// error_reporting(E_ALL);
// ini_set('display_errors',1);
// $id = $_GET['store']+0;
// if (empty($id)) die('store?');
session_start();
$_SESSION['id_store'] = !isset($_SESSION['id_store'])?6:$_SESSION['id_store'];
if($_SESSION['id_store']==15) {$_SESSION['id_store']=6;die('selesai');}
$id =  $_SESSION['id_store'];
ini_set('memory_limit', '-1');
ini_set('max_execution_time',  8000);
ini_set('max_input_time', '-1');
ini_set('session.gc_maxlifetime', 10800);
ini_set('default_socket_timeout', 6000);
$db_username = "bmkottbg";
$db_hostname = "localhost";
$db_password = "Ud0VfE.BYogWSnj!";
$db_name = "kottydb";
 

  $con = mysqli_connect($db_hostname, $db_username, $db_password,$db_name) or die('tidak dapat terhubung ke server database');
  
  $del = "DELETE from stok_store2";
  mysqli_query($con,$del);

  $ins = "insert into stok_store2(id_produk,id_store,stok,harga,hpp) SELECT id_produk,id_store,SUM(qty),harga,hpp FROM `stok_store_kartu` where id_store='$id' and id_produk!='' group by id_produk";
  mysqli_query($con,$ins);

  $cek = "INSERT into stok_store(id_produk,id_store,stok,harga,hpp) 
          select id_produk,id_store,stok,harga,hpp from stok_store2 
          where not exists(SELECT a.id_produk from stok_store a 
                where a.id_produk=stok_store2.id_produk and a.id_store=stok_store2.id_store)
                and stok_store2.id_store>0 and stok_store2.id_produk!=''";
  mysqli_query($con,$cek);

  $upd = 'update stok_store,stok_store2 set stok_store.stok=stok_store2.stok where stok_store.id_store=stok_store2.id_store and stok_store.id_produk=stok_store2.id_produk';
  mysqli_query($con,$upd);

  $_SESSION['id_store']++;
  mysqli_close($con);
  ?>
  <html><head><title>tunggu</title></head><body>diproses : <?php echo $_SESSION['id_store'];?><script>window.location.reload(true);</script></body></html>
  