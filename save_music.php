<?php

require "auth.php";


if(!$isLoggedIn){

    echo "LOGIN_REQUIRED";
    exit;

}



$userFile="users.json";

$file=$_GET["file"] ?? "";



if($file==""){

    echo "ERROR";
    exit;

}





$userIndex=-1;



foreach($users as $index=>$user){


    if($user["id"]==$currentUser["id"]){


        $userIndex=$index;

        break;


    }


}




if($userIndex==-1){

    echo "ERROR";
    exit;

}







// لیست موزیک های ذخیره شده

if(!isset($users[$userIndex]["saved_music"])){

    $users[$userIndex]["saved_music"]=[];

}





$saved=false;



foreach($users[$userIndex]["saved_music"] as $key=>$item){


    if($item==$file){


        $saved=true;


        unset(
            $users[$userIndex]["saved_music"][$key]
        );


        break;


    }


}





if(!$saved){


    $users[$userIndex]["saved_music"][]=$file;


}





$users[$userIndex]["saved_music"]=array_values(

    $users[$userIndex]["saved_music"]

);







file_put_contents(

    $userFile,

    json_encode(

        $users,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);






if($saved){

    echo "REMOVED";

}

else{

    echo "SAVED";

}



?>