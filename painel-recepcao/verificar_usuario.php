<?php 

if(@$_SESSION['nivel_usuario'] != 'recep' and @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

 ?>