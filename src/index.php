<?php
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/HtmlHelper.php");
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/GeneralHelper.php");
$pdo=getPDO();
RequireLogin();
$post=$pdo->prepare("SELECT `title` , `content` from post ORDER BY id_post DESC");
$post->execute();
$data = $post->fetchAll(PDO::FETCH_ASSOC);
testIvent();
$id=GetID();
echo($id);
?>

    <a href="http://localhost:5784/NewPost.php">new</a>
    <a href="http://localhost:5784/ListPost.php">list</a>
    <div>
        <?php foreach($data as $blog) {?>
            <div class="blogcontainer">
                <h2>titre:<?=$blog["title"]?></h2>
                <h3>content:<?=$blog["content"]?></h3>
        </div>
        <?php } ?>
    </div>
>
