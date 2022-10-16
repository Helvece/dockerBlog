<?php
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/HtmlHelper.php");
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/GeneralHelper.php");

$pdo=getPDO();
RequireLogin();
$post=$pdo->prepare("SELECT `title`, `description`, `id_author` FROM post ORDER BY id_post DESC");
$post->execute();
$data = $post->fetchAll(PDO::FETCH_ASSOC);

?>

<?php if (IsLog()) { ?>
    <a href="http://localhost:5784/NewPost.php">new</a>
    <a href="http://localhost:5784/">index</a>
    <div>
        <?php foreach($data as $blog) {?>
            <div class="blogcontainer">
                <h2>titre:<?=$blog["title"]?></h2>
                <h3>description:<?=$blog["description"]?></h3>
        </div>
        <?php } ?>
    </div>
<?php }?>
