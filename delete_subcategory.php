<?php


$categoryFile = "categories.json";



if(
    !isset($_POST["category"]) ||
    !isset($_POST["subcategory"])
){

    header("Location: settings.php");
    exit;

}





$category = $_POST["category"];

$subcategory = $_POST["subcategory"];







if(file_exists($categoryFile)){


    $categories = json_decode(

        file_get_contents($categoryFile),

        true

    );


}
else{


    $categories = [];


}







if(
    isset($categories[$category]["children"][$subcategory])
){



    unset(

        $categories[$category]["children"][$subcategory]

    );



}






file_put_contents(

    $categoryFile,

    json_encode(

        $categories,

        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE

    )

);






header("Location: settings.php");

exit;



?>