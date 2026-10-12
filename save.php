<?php


require "auth.php";



$userFile = "users.json";






if(!$isLoggedIn){


echo "LOGIN_REQUIRED";


exit;


}








$file = $_GET["file"] ?? "";





if($file==""){


echo "ERROR";


exit;


}









$userIndex=-1;






foreach($users as $index=>$user){



    if(

        $user["id"]

        ==

        $currentUser["id"]

    ){



        $userIndex=$index;


        break;



    }



}







if($userIndex==-1){


echo "ERROR";


exit;


}












// ساخت لیست ذخیره فیلم‌ها


if(!isset($users[$userIndex]["saved_videos"])){



    $users[$userIndex]["saved_videos"]=

    $users[$userIndex]["saved"] ?? [];



}









$saved=false;








foreach(

$users[$userIndex]["saved_videos"]

as $key=>$savedFile

){



    if($savedFile==$file){



        $saved=true;



        unset(

            $users[$userIndex]["saved_videos"][$key]

        );



        break;



    }



}












// اگر ذخیره نشده بود اضافه کن


if(!$saved){



    $users[$userIndex]["saved_videos"][]=$file;



}









$users[$userIndex]["saved_videos"]=array_values(

    $users[$userIndex]["saved_videos"]

);












// ذخیره کاربران


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