<?php


session_start();



$userFile = "users.json";


$message = "";






if($_SERVER["REQUEST_METHOD"]=="POST"){



    $username = trim($_POST["username"] ?? "");


    $password = trim($_POST["password"] ?? "");





    if($username=="" || $password==""){



        $message="❌ Please fill all fields";



    }

    elseif(strlen($password)<6){



        $message="❌ Password must be at least 6 characters";



    }

    else{





        if(file_exists($userFile)){



            $users=json_decode(

                file_get_contents($userFile),

                true

            );



        }

        else{



            $users=[];



        }







        if(!is_array($users)){



            $users=[];



        }









        $exists=false;





        foreach($users as $user){





            if(

                strtolower($user["username"])

                ===

                strtolower($username)

            ){



                $exists=true;


                break;



            }





        }









        if($exists){



            $message="❌ Username already taken";



        }

        else{





            if(count($users)==0){



                $role="admin";



            }

            else{



                $role="user";



            }









            $token=bin2hex(random_bytes(32));









            $newUser=[





                "id"=>time(),



                "username"=>$username,



                "password"=>password_hash(

                    $password,

                    PASSWORD_DEFAULT

                ),



                "role"=>$role,



                "token"=>$token,





                // فیلم ها

                "liked_videos"=>[],

                "saved_videos"=>[],





                // موزیک

                "liked_music"=>[],

                "saved_music"=>[],





                // سازگاری با نسخه قدیمی

                "liked"=>[],

                "saved"=>[]





            ];









            $users[]=$newUser;









            file_put_contents(

                $userFile,

                json_encode(

                    $users,

                    JSON_PRETTY_PRINT |

                    JSON_UNESCAPED_UNICODE

                )

            );









            header(

                "Location: login.php"

            );


            exit;





        }







    }







}



?>







<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Register</title>


<link rel="stylesheet" href="style.css">


</head>





<body>



<div class="container">



<div class="upload-box">



<h1>

📝 Register

</h1>




<p>

<?php echo $message; ?>

</p>







<form method="post">





<input

type="text"

name="username"

placeholder="Username"

required

>







<input

type="password"

name="password"

placeholder="Password"

required

>







<button type="submit">

Create Account

</button>







</form>







<a href="login.php">

Already have account? Login

</a>







</div>



</div>

<script>

/* =========================
   MOBILE CLICK FIX
========================= */

document.addEventListener("touchend", function(event){

    let element = event.target.closest("a, button, [role='button']");

    if(element){

        setTimeout(function(){

            if(document.activeElement === element){

                element.blur();

            }

        }, 0);

    }

}, {passive:true});


document.addEventListener("click", function(event){

    let element = event.target.closest("a, button, [role='button']");

    if(element){

        setTimeout(function(){

            if(document.activeElement === element){

                element.blur();

            }

        }, 0);

    }

});

</script>

</body>


</html>