<?php
  include '../db_conn.php';
  include '../curl.php';
  $url_check = $node_url.'meta-search';
  $params_check = '?keyword=New%20election';
  $result_check = json_decode(sendGet($url_check, $params_check));
  $elections = [];
  // unset($result_check[0]);
  
  foreach($result_check as $meta_res){
    $url_check = $node_url.'search';
    $params_check = '?keyword='.$meta_res->id;
    $election_details = json_decode(sendGet($url_check, $params_check));
    $details = $election_details[0]->data;
    $details->id = $election_details[0]->id;
    if($details->id != 'b0454ea2338cd9e03727f40ac77fa51f201f5d5c6fce98396393087027d66e5f'){
      if(isset($details->title) && isset($details->candidates) )
        array_push($elections, $details);
      }
    
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Scheduled Elections</title>
  <link rel="stylesheet" type="text/css" href="../css/app.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
  <div class="bg-image"></div>
  <div class="home-btns">
    <a class="btn btn1" href="CreateElection.html"> Create an Election</a>
    <a class="btn btn2" href="election_schedule.php"> Election schedule </a>
    <a class="btn btn3" href="login.html"> Logout </a>
  </div>
  <div class="content-center" style="margin-top:70px">
  <h1>Scheduled Elections</h1>
  <div class="election-form-box">
    <?php if(sizeof($elections) != 0){
      ?>
    <div class="form-group" style="display: flex;">
    <table class="table table-light w-100" width="100%">
      <thead>
        <th>Sno</th>
        <th>Title</th>
        <th>Scheduled on</th>
        <th>Result on</th>
        <th>View</th>
      </thead>
      <tbody>
         <?php
         $sno =1;
         foreach($elections as $elec){?>
        <tr>
          <td><?php echo $sno++;?></td>
          <td><?php echo $elec->title?></td>
          <td><?php echo $elec->date?></td>
          <td><?php echo $elec->result?></td>
          <td><a href="ViewResult.php?id=<?php echo($elec->id)?>">Open</a></td>
        </tr>
        <?php }
        ?>
      </tbody>
    </table>
    </div>
    <?php }else{
       echo '<h2 class="mt-5"> No elections scheduled!</h2>'; 
    } ?>
    <br>
    <button class="btn btn3" onclick="goBack()">Go Back</button>
    <a class="btn btn1" href="login.html"> Logout </a>
    <br><br>
    
  </div>
</div>

  <script>
    function goBack() {
      window.history.back();
    }
  </script>
</body>
</html>