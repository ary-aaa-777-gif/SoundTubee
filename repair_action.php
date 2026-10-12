<?php

$dataFile = "data.json";

$videoFolder = "videos";
$thumbFolder = "thumbnails";
$backupFolder = "backup";



if(!is_dir($backupFolder)){

    mkdir($backupFolder);

}




// backup

if(file_exists($dataFile)){

    copy(
        $dataFile,
        $backupFolder."/data_backup_".date("Ymd_His").".json"
    );

}




$database = [];

if(file_exists($dataFile)){

    $database = json_decode(
        file_get_contents($dataFile),
        true
    );

}




$fixed = 0;




foreach($database as &$item){



    if(isset($item["file"])){


        $videoName = pathinfo(
            $item["file"],
            PATHINFO_FILENAME
        );



        $possibleThumb = $videoName.".jpg";



        $thumbPath = $thumbFolder."/".$possibleThumb;



        if(file_exists($thumbPath)){



            if(!isset($item["thumbnail"])
            || $item["thumbnail"] != $possibleThumb){


                $item["thumbnail"] = $possibleThumb;


                $fixed++;


            }



        }



    }



}





file_put_contents(

    $dataFile,

    json_encode(
        $database,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    )

);



?>

<!DOCTYPE html>

<html>

<head>

<title>Repair Complete</title>


<style>

body{

background:#111;
color:white;
font-family:Arial;
text-align:center;
padding:50px;

}


.box{

background:#222;
padding:30px;
border-radius:20px;
max-width:600px;
margin:auto;

}


.green{

color:#22c55e;

}

</style>


</head>


<body>


<div class="box">


<h1>🔧 Repair v2 Complete</h1>


<h2 class="green">

✔ Database updated

</h2>


<p>

Fixed thumbnails:

<?php echo $fixed; ?>

</p>


<br>


<a href="index.php" style="color:#e50914">

Back to Library

</a>



</div>


</body>

</html>