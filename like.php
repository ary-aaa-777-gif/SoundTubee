<?php


require "auth.php";



$dataFile = "data.json";

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












// ساخت لیست لایک فیلم‌ها


if(!isset($users[$userIndex]["liked_videos"])){


    $users[$userIndex]["liked_videos"]=

    $users[$userIndex]["liked"] ?? [];


}









$alreadyLiked=false;







foreach(

$users[$userIndex]["liked_videos"]

as $key=>$likedFile

){



    if($likedFile==$file){



        $alreadyLiked=true;



        unset(

            $users[$userIndex]["liked_videos"][$key]

        );



        break;



    }



}









if(!$alreadyLiked){



    $users[$userIndex]["liked_videos"][]=$file;



}









$users[$userIndex]["liked_videos"]=array_values(

    $users[$userIndex]["liked_videos"]

);












// خواندن فیلم‌ها


if(file_exists($dataFile)){



$videos=json_decode(

file_get_contents($dataFile),

true

);



}

else{


$videos=[];


}









if(!is_array($videos)){


$videos=[];


}












// تغییر تعداد لایک فیلم


foreach($videos as &$video){



    if(

        isset($video["file"])

        &&

        $video["file"]==$file

    ){



        if(!isset($video["likes"])){

            $video["likes"]=0;

        }







        if($alreadyLiked){



            $video["likes"]--;



        }

        else{



            $video["likes"]++;



        }







        if($video["likes"]<0){


            $video["likes"]=0;


        }







        break;



    }



}



unset($video);












// ذخیره کاربران


file_put_contents(

$userFile,

json_encode(

$users,

JSON_PRETTY_PRINT |

JSON_UNESCAPED_UNICODE

)

);











// ذخیره فیلم‌ها


file_put_contents(

$dataFile,

json_encode(

$videos,

JSON_PRETTY_PRINT |

JSON_UNESCAPED_UNICODE

)

);












// برگرداندن تعداد


foreach($videos as $video){



    if(

        $video["file"]

        ==

        $file

    ){



        echo $video["likes"];


        exit;



    }



}






echo "0";



?>