<?php
$_pdo=null;

function getPDO(){
    global $_pdo;#motifie la protée de la variable
    if($_pdo == null){
        $host = "db";
        $Name = "data";
        $PW   = "password";
        $user = "root";

        $_pdo = new PDO('mysql:host='.$host.';dbname='.$Name.';charset=utf8',$user ,$PW);
    }
    return $_pdo;
}
?>