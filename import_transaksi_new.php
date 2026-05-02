<?php //die('created by Arisal Y.');
ini_set('memory_limit', '-1');
ini_set('max_execution_time',  0);
ini_set('max_input_time', '-1');
ini_set('session.gc_maxlifetime', 10800);
error_reporting(1);
ini_set('display_errors',1);

$db_username = "bmkottbg";
$db_hostname = "localhost";
$db_password = "Ud0VfE.BYogWSnj!";
$db_name = "kottydb";

$con = mysqli_connect($db_hostname, $db_username, $db_password,$db_name) or die('tidak dapat terhubung ke server database');

// 2. Update poin customer
$tgl = date('ymd',strtotime('yesterday'));
$s3 = "SELECT * from ap_invoice_number where id_customer is not null and no_invoice like 'bcINV{$tgl}%'";
$r3 = mysqli_query($con,$s3);
$ros = 0;
while ($w = mysqli_fetch_array($r3, MYSQLI_ASSOC)){
    if (!empty($w['id_customer'])){
        $ros++;
        $s = "UPDATE ap_customer set `point` = (`point`+".$w['poin'].") where `id_customer` = '".$w['id_customer']."'";
        mysqli_query($con,$s);
        //echo $s;
    }
}

// 1. Entri inv kartu stok
$s2 = "INSERT INTO `stok_store_kartu`(`id_store`, `id_produk`, `hpp`, `harga`, `tanggal`, `qty`, `tipe`, `no_transaksi`) select ap_invoice_number.`id_toko`, ap_invoice_item.`id_produk`, ap_invoice_item.`hpp`, ap_invoice_item.`harga_jual`, ap_invoice_number.`tanggal`, concat('-',ap_invoice_item.`qty`), concat('Penjualan BC'), ap_invoice_item.`no_invoice` from ap_invoice_item,ap_invoice_number where ap_invoice_item.no_invoice like 'bcINV{$tgl}%' and ap_invoice_item.no_invoice=ap_invoice_number.no_invoice";
mysqli_query($con,$s2);

  

mysqli_close($con);
echo "Number: " .$ros;