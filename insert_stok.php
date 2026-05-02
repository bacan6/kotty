<?php //die('created by Arisal Y.');
// error_reporting(E_ALL);
// ini_set('display_errors',1);
// $id = $_GET['store']+0;
// if (empty($id)) die('store?');
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
 
  $ins = "insert into stok_store(id_produk,id_store,stok) 
  SELECT p.id_produk,p.id_store,p.stok FROM `stok_store2` p
  where not exists(SELECT s.id_produk from stok_store s 
        where s.id_produk=p.id_produk and s.id_store=p.id_store)";
  mysqli_query($con,$ins);
  $num = mysqli_affected_rows($con);

  echo $num;


  mysqli_close($con);
  echo "SELESAI";
  