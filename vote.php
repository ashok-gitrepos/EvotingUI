<?php
include 'db_conn.php';
include 'curl.php';

session_start();

if(!isset($_SESSION["user"]) ||  !$_SESSION["user"]){
    echo "<script>
    alert('User expired!');
    window.location='login.html';
    </script>";
}

 $url_check = $node_url.'search';
 $params_check = '?keyword='.$_GET['id'];
 $result_check = json_decode(sendGet($url_check, $params_check));

    if(!$result_check) {
        echo '<script>alert("invalid!");
         window.location.href = "ready_to_vote.php";
        </script>';
    }
    $result_check = $result_check[0]->data;
    $today = date('Y-m-d');
?>
<!DOCTYPE html>  
<html>
<head> 
    <title>Electronic voting system</title>
    <link rel="stylesheet" type="text/css" href="css/app.css">
    
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
            align-items: center;
            justify-items: center;
            margin-top: 20px;
        }
        
        .grid-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
   
    </style>
</head>
<body>
    <div class="bg-image"></div>
   
    <div class="home-btns">
        <a class="btn btn1" href="home.html"> Home </a>
        <a class="btn btn3" href="login.html"> Logout </a>
    </div>
    
  <div class="content-center" style="margin-top:70px;">  
    <h1>VOTING PAGE</h1>
    <?php 
    if($today != $result_check->date){
    ?>
    <div class="election-form-box">
        <h3 class="text-dark"><?=$result_check->title?></h3>
        <div class="grid-container">
            <?php foreach($result_check->candidates as $candidate){ ?>
            <div class="grid-item">
                <form action="cast_vote.php" method="POST">
                    <img src="p2.jpg" alt="click" width="150" height="150">
                    <p><b>Person Name: <?=$candidate?></b></p>
                    <input type="hidden" name="person" id="person" value="<?=$candidate?>" readonly>
                    <input type="hidden" name="election_id" id="election_id" value="<?=$_GET['id']?>" readonly>
                    <button class="btn btn-success" type="submit">vote me!</button>
                </form>
            </div>
            <?php } ?>
        </div>
        <a href="ready_to_vote.php" class="btn btn3 mt-5">Go back</a>

    </div>
    <?php 
        }else{
            echo '<h2 style="color:white">You\'re able to vote on '.$result_check->date.'</h2><br>';
            echo '<a href="ready_to_vote.php" class="btn btn3">Go back</a>';
        }
    ?>
</div>
</body>
<script>
    function vote(){
        alert('Your vote is polled!');
        window.location = 'home.html';
    }
</script>
</html>