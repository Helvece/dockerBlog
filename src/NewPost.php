<?php
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/HtmlHelper.php");
require_once($_SERVER['DOCUMENT_ROOT']."/PHP/GeneralHelper.php");
$pdo=getPDO();
RequireLogin();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST['title']) && !empty($_POST['htmlcontent']) && !empty($_POST['descrip'])) {
        $title = htmlspecialchars($_POST["title"]);
        echo($title);
        $descrip= htmlspecialchars($_POST["descrip"]);
        echo($descrip);
        $content= htmlspecialchars($_POST["htmlcontent"]);
        echo($content);
        $Id=GetID();
        echo($Id);
        
        $query=$pdo->prepare('INSERT INTO post (title, content, description, id_author) VALUES (:title, :content, :descrip, :id )');
        $query->execute([
            ":title"  =>$title,
            ":content"=>$content,
            ":descrip"=>$descrip,
            ":id"     =>$Id,
        ]);   

}
}

?>
<a href="http://localhost:5784/">index</a>
<a href="http://localhost:5784/ListPost.php">list</a>
<form class="connectform" action="/NewPost.php" method="post">
    <input type="text" name="title" placeholder="title"><br/>
    <input id="descrip" name="descrip" type="text" placeholder="description" maxlength="240">
    <span id="conteur" style="position:absolute; right:0; width:20px ;height:20px; margin:20px"></span>
    <textarea id="content" name="htmlcontent" placeholder="article" columns="100" rows="20"></textarea>
    <button type="submit" class="SubmitButton">publish</button>
</form>