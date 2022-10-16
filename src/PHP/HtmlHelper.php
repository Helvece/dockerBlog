<?php
function testIvent(){
    if (isset($_GET["er"])) {
        echo "<div class=\"ErrorMessage\"><i class=\"message\"></i>".$_GET["er"]."</div>";
    }

    else if (isset($_GET["ew"])) {
        echo "<div class=\"ui warning message\"><i class=\"message\"></i>".$_GET["ew"]."</div>";
    }

    else if (isset($_GET["es"])) {
        echo "<div class=\"ui success message\"><i class=\"message\"></i>".$_GET["es"]."</div>";
    }
}        
?>