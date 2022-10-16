<?php
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/HtmlHelper.php");
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/GeneralHelper.php");

$pdo=getPDO();


if(IsLog()){
    http_response_code(302);
    header('location:index?ew=Already%20Logged');
 }
    if($_SERVER["REQUEST_METHOD"] == "POST") {
            
        $Name     = filter_input(INPUT_POST, "US");
        $PW       = filter_input(INPUT_POST, "PW");
        $PWC      = filter_input(INPUT_POST, "PWC");
    
    //username taken
    $UserName=$pdo->prepare('SELECT `pseudo` fROM `user` WHERE `pseudo` = :username');
    $UserName->execute([
        ":username" => $Name
    ]);

    $bool=$UserName->rowCount() > 0;

    if ($bool){//username is taken by other
        http_response_code(302);
        header('location:/register.php?er=Username%20already%20taken');
        die();
    } 
    if ($PW == "" || $PWC == "") {//one of the pw is null
        http_response_code(302);
        header('location:/register.php?er=Please%20make%20a%20password,%20it\'s%20not%20safe%20to%20go%20unprotected');
        die();
    }

    if($PW != $PWC){//the pws are different from each other
        http_response_code(302);
        header('location:/register.php?er=Passwords%20match%20does%20not');
        die();
    }

    
    $PW_hash = password_hash($PW , PASSWORD_DEFAULT);
    $register= $pdo->prepare("INSERT INTO `user` (`pseudo`, `password`, `rank`) VALUES (:pseudo, :PW, :rank)");
    $register->execute([
        ":pseudo"=> $Name,
        ":PW"    => $PW_hash,
        ":rank"  => 0,
    ]);

    //creation and integration of the authentication token
    $query = $pdo->prepare('SELECT LAST_INSERT_ID() id from user;');
    $query->execute();
    $Id = $query->fetch(PDO::FETCH_ASSOC)["id"];
    $token = "";
    $found = false;

    while (!$found) {
        $token = GetRandomString(32);
        $query = $pdo->prepare('SELECT `token` FROM `token` WHERE token = :tok');
        $query->execute([
            ":tok"   => $token,
        ]);
        $result = $query->fetchAll();
        if ($query->rowCount() == 0) {
            $found = true;
        }
    }
    $req= $pdo->prepare('INSERT INTO `token`(`id_user`, `token`) VALUES (:id, :tok)');
    $req->execute([
        ":id"    => $Id,
        ":tok"   => $token,
    ]);

    setcookie("Authorisation", $token);

    http_response_code(302);
    header('location:/');
    die();

} 
testIvent();
?>

<div class="container">
    <div class="from">
        <h2>Register from</h2>
        <form id="register-form" method="post" action="/register.php">
            <div class="InputBox">
                <input tabindex="1" type="text" name="US" placeholder="Username" required pattern="^[A-Za-z0-9 _\[\]-]{2,15}$">
            </div>
            <div class="InputBox">
                <input tabindex="2" type="password" name="PW" placeholder="Password" pattern="^.{8,}$">
            </div>
            <div class="InputBox">
                <input tabindex="3" type="password" name="PWC" placeholder="Confirme Password" pattern="^.{8,}$">
            </div>
            <p class="forget">you have an account ?<a href="login.php">login</a></p>
        </form>
        <div class="SubmitButton">
                <button tabindex="3" class="ui primary button" type="submit" form="register-form">Submit"</button>
        </div>
    </div>
</div>
