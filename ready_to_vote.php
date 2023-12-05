<?php 
include 'db_conn.php';
include 'curl.php';
$url_check = $node_url.'meta-search';
$params_check = '?keyword=New%20election';
$result_check = json_decode(sendGet($url_check, $params_check));
$elections = [];

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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/app.css">
</head>
<body>
  <div class="bg-image"></div>
  <div class="home-btns">
    <a class="btn btn1" href="home.html"> Home </a>
    <a class="btn btn3" href="login.html"> Logout </a>
  </div>
  
  <div class="content-center" style="margin-top:70px;">  
    <h1>Ready to Vote</h1>
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
        <th>Action</th>
      </thead>
      <tbody>
         <?php
         $sno =1;
         foreach($elections as $elec){?>
        <tr>
          <td><?php echo $sno++;?></td>
          <td><?php echo $elec->title?></td>
          <td><?php echo $elec->date?></td>
          <td>
            <?php
             echo $elec->result;
             if($elec->result == date('Y-m-d'))
                echo '<a href="admin/ViewResult.php?id='.($elec->id).'">Check</a>';
            ?>
          </td>
          <td>
            <a href="vote.php?id=<?php echo($elec->id)?>">Vote</a>
          </td>
        </tr>
        <?php }
        ?>
      </tbody>
    </table>
    </div>
    <?php }else{
       echo '<h2 class="mt-5"> No elections are scheduled!</h2>'; 
    } ?>
    <br>
    <button class="btn btn3" onclick="goBack()">Go Back</button>
    <a class="btn btn1" href="login.html"> Logout </a>
    <br><br>
    
  </div>
  </div>
</body>

<script>
    function goBack() {
      window.history.back();
    }
</script>
</html>