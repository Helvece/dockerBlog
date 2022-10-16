<?php
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/HtmlHelper.php");
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/GeneralHelper.php");
$pdo=getPDO();

if(IsLog()){
   http_response_code(302);
   header('location:index.php');
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $Name =filter_input(INPUT_POST,"US");
    $PW   =filter_input(INPUT_POST,"PW");

    $Id = GetIDFromUsername($Name);
        if ($Id == -1) {
            http_response_code(302);
            header('location:/login.php?er=Username%20or%20password%20doesn%27t%20match');
            die();
        }
    
    $DbPw=GetUserData($Id)["password"];
    $Pass = password_verify($PW, $DbPw);

    if (!$Pass) {
        http_response_code(302);
        header('location:/login.php?er=Username%20or%20password%20doesn%27t%20match');
        die();
    }

    $token = "";
    $found = false;

    while (!$found) {
        $token = GetRandomString(32);
        $query = $pdo->prepare('SELECT token FROM token WHERE token = :tok');
        $query->execute([
            ":tok"   => $token,
        ]);
        $a = $query->fetchAll();
        if ($query->rowCount() == 0) {
            $found = true;
        }
    }
    $query = $pdo->prepare('INSERT INTO token (`id_user`, `token`) VALUES (:id, :tok);');
        $query->execute([
            ":id"    => $Id,
            ":tok"   => $token,
            
        ]);
        setcookie("Authorisation", $token);

        http_response_code(302);
        header('location:/?es=Connected');
        die();
            
}
testIvent();
?>
<div class="container">
    <div class="from">
        <h2>login from</h2>
        <form id="login-form" method="post" action="/login.php"> 
            <div class="InputBox">
            <input tabindex="1" type="text" name="US" placeholder="Username" required pattern="^[A-Za-z0-9 _\[\]-]{2,15}$">
            </div>
            <div class="InputBox">
            <input tabindex="2" type="password" name="PW" placeholder="Password" required pattern="^.{8,}$">
            </div>
            <p class="forget">Forgot Password ? <a href="register.php">click Here</a></p>
        </form>
        <div class="SubmitButton">
                <button tabindex="3" class="ui primary button" type="submit" form="login-form">Submit"</button>
        </div>
    </div>
</div>
