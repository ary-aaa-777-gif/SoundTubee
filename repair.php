<?php

$dataFile = "data.json";


$videosFolder = "videos";
$thumbFolder = "thumbnails";



$videoCount = 0;
$thumbCount = 0;
$dataCount = 0;



if(is_dir($videosFolder)){

    $videoCount = count(array_diff(scandir($videosFolder), [".",".."]));

}



if(is_dir($thumbFolder)){

    $thumbCount = count(array_diff(scandir($thumbFolder), [".",".."]));

}



if(file_exists($dataFile)){


    $data = json_decode(
        file_get_contents($dataFile),
        true
    );


    $dataCount = count($data);


}



?>


<!DOCTYPE html>

<html>

<head>

<title>Library Repair</title>


<style>


body{

background:#111;
color:white;
font-family:Arial;
text-align:center;
padding:40px;

}



.box{

background:#1c1c1c;
max-width:700px;
margin:auto;
padding:35px;
border-radius:20px;

}



h1{

color:#e50914;

}



.info{

background:#222;
padding:15px;
border-radius:10px;
margin:15px;

}



.green{

color:#22c55e;

}




button{

background:#e50914;
color:white;
border:none;
padding:15px 35px;
font-size:18px;
border-radius:12px;
cursor:pointer;

}



button:hover{

background:#ff3030;

}




a{

color:#e50914;
text-decoration:none;

}



</style>


</head>


<body>



<div class="box">



<h1>🛠 Library Repair</h1>



<h2>Current Library Status</h2>




<div class="info">


<p>

🎬 Videos:

<span class="green">

<?php echo $videoCount; ?>

</span>

</p>



<p>

🖼 Thumbnails:

<span class="green">

<?php echo $thumbCount; ?>

</span>

</p>



<p>

📄 Database Records:

<span class="green">

<?php echo $dataCount; ?>

</span>

</p>



</div>





<h2>

Ready to repair?

</h2>



<p>

A backup of your database will be created automatically.

</p>




<form action="repair_action.php" method="post">


<button type="submit">

🔧 Start Repair

</button>


</form>



<br><br>



<a href="index.php">

← Back to Library

</a>




</div>



</body>

</html>