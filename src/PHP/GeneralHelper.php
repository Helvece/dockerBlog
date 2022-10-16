<?php
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/Sql.php");
$pdo=getPDO();

/*function GetID(){
    global $pdo;
    if(!isset($auth)) {
        return -1;

    $auth = $_COOKIE["Authorisation"];
    $RecID = $pdo->prepare("SELECT id_user FROM token WHERE token = :auth ");
    $RecID->execute([
        ":auth"=> $auth
    ]);
    $result = RecID->fetch(PDO::FETCH_ASSOC);
    if($result==null){
        return -1;
    }
    else{
        return $result["id_user"];
    }
}
}*/

function GetID() {
    global $pdo;
    if (!isset($_COOKIE["Authorisation"])) return -1;
    $RecID = $pdo->prepare("SELECT id_user FROM token WHERE token = :auth ");
    $RecID->execute([
        ":auth" => $_COOKIE["Authorisation"]
    ]);
    $t = $RecID->fetchAll(PDO::FETCH_ASSOC);
    if ($RecID->rowCount() == 0) return -1;
    return $t[0]["id_user"];
}

function IsLog(){
    return GetID()!=-1;
}

function RequireLogin(){
    if(!IsLog()){
        http_response_code(302);
        header("Location:/login.php");
        die();
    }
}

function IsAdmin(){
    $Id=GetID();
    if($id==-1){
        return false;
    }
    global $pdo;
    $rank= $pdo->prepare("SELECT rank FROM user WHERE id = :id");
    $rank->execute([
        ":id"=>$Id
    ]);
    $result=$rank->fetch(PDO::FETCH_ASSOC);
    return $result["rank"]==3;
}

function GetUserData($Id){
    global $pdo;
    $user=$pdo->prepare("SELECT * from user WHERE id = :id");
    $user->execute([
        ":id"=>$Id
    ]);
    $result=$user->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function Context(){
    $Id=GetID();
    $Data=[
        "ID"=>$Id,
        "IsLog"=>IsLog(),
        "IsAdmin"=>IsAdmin(),
        "Data"=>GetUserData($Id),
    ];
}

function GetIDFromUsername($username) {
    global $pdo;
    $RecID = $pdo->prepare("SELECT id FROM user WHERE pseudo= :username");
    $RecID->execute([
        ":username" => $username
    ]);
    $result = $RecID->fetchAll(PDO::FETCH_ASSOC);
    if ($RecID->rowCount() == 0) return -1;
    return $result[0]["id"];
}

function GetRandomString($length=8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>