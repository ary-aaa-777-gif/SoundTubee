<?php

require "auth.php";
require "config.php";


if(!$isLoggedIn){

    header("Location: login.php");
    exit;

}



if($currentUser["role"] !== "admin"){

    echo "Access Denied";
    exit;

}





$id = $_GET["id"] ?? "";



if($id==""){

    header("Location: pending.php");
    exit;

}







// خواندن pending


$pending=[];


if(file_exists($pendingFile)){


    $pending=json_decode(

        file_get_contents($pendingFile),

        true

    );


}



if(!is_array($pending)){

    $pending=[];

}






$approved=null;


$newPending=[];



foreach($pending as $video){


    if($video["id"]==$id){


        $approved=$video;


    }

    else{


        $newPending[]=$video;


    }


}






if(!$approved){


    header("Location: pending.php");

    exit;


}








// ذخیره pending جدید


file_put_contents(

    $pendingFile,

    json_encode(

        $newPending,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);










// بروزرسانی دسته ها


$categories=[];


if(file_exists($categoryFile)){


    $categories=json_decode(

        file_get_contents($categoryFile),

        true

    );


}



if(!is_array($categories)){

    $categories=[];

}






$cat=$approved["category"];

$sub=$approved["subcategory"];





if(!isset($categories[$cat])){


    $categories[$cat]=[

        "cover"=>"",

        "children"=>[]

    ];


}





if(!isset($categories[$cat]["children"][$sub])){


    $categories[$cat]["children"][$sub]=[

        "cover"=>"",

        "children"=>[]

    ];


}







file_put_contents(

    $categoryFile,

    json_encode(

        $categories,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);









// اضافه کردن به data.json


$data=[];


if(file_exists($dataFile)){


    $data=json_decode(

        file_get_contents($dataFile),

        true

    );


}



if(!is_array($data)){

    $data=[];

}








$data[]=[


    "id"=>$approved["id"],


    "file"=>$approved["file"],


    "name"=>$approved["name"],


    "thumbnail"=>$approved["thumbnail"],


    "category"=>$approved["category"],


    "subcategory"=>$approved["subcategory"],


    "duration"=>$approved["duration"],


    "likes"=>0,


    "views"=>0,


    "saves"=>0,


    "comments"=>0,


    "date"=>$approved["date"]



];









file_put_contents(

    $dataFile,

    json_encode(

        $data,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);







header("Location: pending.php");

exit;


?>