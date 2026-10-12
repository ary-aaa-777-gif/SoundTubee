<?php


$categoryFile = "categories.json";



if(!isset($_POST["category"])){

    header("Location: settings.php");
    exit;

}



$name = trim($_POST["category"]);




if($name == ""){

    header("Location: settings.php");
    exit;

}





if(file_exists($categoryFile)){


    $categories = json_decode(

        file_get_contents($categoryFile),

        true

    );


}
else{


    $categories = [];


}






if(!isset($categories[$name])){


    $categories[$name] = [


        "children" => [


            "General" => []


        ]


    ];


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