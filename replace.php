<?php error_reporting(E_ALL);ini_set('display_errors',1);
foreach(glob('/usr/share/nginx/kotty/application/controllers/*.php') as $path_to_file) {
    $file_contents = file_get_contents($path_to_file);
    $file_contents = str_replace("Solusinformatika.com",
    				'SOLUSI POS',$file_contents);
    file_put_contents($path_to_file,$file_contents);
    echo $path_to_file."<br>";
}


?>