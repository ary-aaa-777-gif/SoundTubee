<?php

$commentsFile = "comments.json";


$videoID = $_GET["id"] ?? "";


$count = 0;



if(file_exists($commentsFile)){


    $comments = json_decode(

        file_get_contents($commentsFile),

        true

    );



    if(is_array($comments)){


        foreach($comments as $comment){



            if(

                isset($comment["video_id"])

                &&

                $comment["video_id"] == $videoID

            ){


                $count++;


            }


        }


    }


}



echo $count;


?>