<?php session_start(); 

include ('config.php');

$con = mysqli_connect($host,$username,$password,$db_name1); 
$con2 = mysqli_connect($host,$username,$password,$db_name2);

if (isset ($_SESSION['user_id'])){
  $id = $_SESSION['user_id']; 

  if (isset($_POST['addtime'])){
    $addtime = $_POST['addtime'];
    //echo $addtime;
  }

  if (isset($_POST['itemcategory']))
  {
    $itemcategory = $_POST['itemcategory'];
    //echo $itemcategory;
  }

  if (isset($_POST['itemtags']))
  {
    $itemtags = $_POST['itemtags'];
    //echo $itemtags;
  }

 
  if (isset($_POST['itemshippingcost']))
  {
    $itemshippingcost = $_POST['itemshippingcost'];
    //echo $itemshippingcost;
  }


  /* Queries to Customer */

  if (mysqli_connect_errno($con)){
      echo "Failed to connect to MySQL: " . mysqli_connect_error();

  }else{

    if (!isset($_POST['skipstep2'])){

      if ( isset($_POST['address']) && isset($_POST['apartment']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zipcode']))
      {
        if ( ($_POST['address'] == "") || ($_POST['apartment'] == "") || ($_POST['city'] == "") || ($_POST['state'] == "") || ($_POST['zipcode'] == ""))
            {
               $_ERROR['required_address_fields'] = true;
                echo "Error: Need detail address information!";
            }
        else{
         $raw = $_POST['address'] . " " . $_POST['apartment'];
         $street = mysql_real_escape_string($raw);
         $city = mysql_real_escape_string($_POST['city']);
         $state = mysql_real_escape_string($_POST['state']);
         $zip = mysql_real_escape_string($_POST['zipcode']);
         $country = "US";
         $query = "INSERT INTO address (street_address, city, state, zip_code, country) VALUES ('".$street."','".$city."','".$state."',".intval($zip).",'".$country."')";
         $result = mysqli_query($con, $query);
          if($result != null){
            $address_id = mysqli_insert_id($con);
            $query = "INSERT INTO addresslistings (user_id, address_id) VALUES ('".$_SESSION['user_id']."','".$address_id."')";
            $result = mysqli_query($con, $query);
           if($result == null)
              echo "Error:Insert address not success";
           // else
              //echo "Insert address list success";
          }
        }
      }
  }
  else{
    echo "Warning:no address inserted! If you only sale online, that's fine!";
  }

}

  if (mysqli_connect_errno($con2)){
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }else{

    if ( (isset($_POST['saletitle'])) && (isset($_POST['saledescription']))){
      if (($_POST['saletitle'] == "")){
        //$_ERROR['no_title'] = true;
        echo "Error: Need garagersale title!";
      }
      else{
        if ( ($_POST['startdate'] == "") && ($_POST['starttime'] == "")){
          $datetime = date('Y-m-d H:i:s');
          //echo $datetime;
        }
        else{
          $datetime = $_POST['startdate'] . " " . $_POST['starttime'] . ":00";
          //echo $datetime;
        }

      $salename = mysql_real_escape_string($_POST['saletitle']);
      $saledescription = mysql_real_escape_string($_POST['saledescription']);
      if (!isset($_POST['skipstep2'])){
        $query = "INSERT INTO garagersale (name, description, address_id, garagerseller_id, date) VALUES ('".$salename."','".$saledescription."','".$address_id."',".$_SESSION['user_id'].",'".$datetime."')";
        $result = mysqli_query($con2, $query);
        $garagersale_id = mysqli_insert_id($con2);
        }
      else{
        $query = "INSERT INTO garagersale (name, description, garagerseller_id, date) VALUES ('".$salename."','".$saledescription."','".$_SESSION['user_id']."','".$datetime."')";
        $result = mysqli_query($con2, $query);
        $garagersale_id = mysqli_insert_id($con2);
        }
      }
    }

    $image = [];
    $format = [];
    $picture_id = [];
  for ($i =1; $i<=4; $i++){
    if (isset($_POST['small'.$i])){
       $image[$i-1] = $_POST['small'.$i];
      if (preg_match("/^data:image\/jpeg\;base64/",$image[$i-1]))
      {
       $format[$i-1] = "jpeg";
       //echo $format[$i-1];
      }
      elseif(preg_match("/^data:image\/gif\;base64/",$image[$i-1])){
        $format[$i-1] = "gif";
      }
      elseif(preg_match("/^data:image\/png\;base64/",$image[$i-1])){
        $format[$i-1] = "png";
      }
      else{
        $_ERROR['syntax of uploaded file error'] = true;
      }

      $image[$i-1] = str_replace("data:image/".$format[$i-1].";base64,","", $image[$i-1]);
      $image[$i-1] = str_replace(' ', '+', $image[$i-1]);
      $data = base64_decode($image[$i-1]);
      $data2 = mysqli_real_escape_string($con2, $data);
      $query = "INSERT INTO merchandisepictures (image, format, size) VALUES ('".$data2."','".$format[$i-1]."','100')";
      $result = mysqli_query($con2, $query);
      $picture_id[$i-1] = mysqli_insert_id($con2);
      if ($i == 1){
        $main_picture_id = $picture_id[$i-1];
        //echo $main_picture_id;
      }
      /*if ($picture_id != null){
        $query = "INSERT INTO item (price, name, description,starting_date,main_picture_id) VALUES ('20','haha1','haha2','2013-11-04 00:00:00','".$picture_id."')";
        $result = mysqli_query($con2, $query);
        echo "suceess picture!";
      }*/
    }
  }//forloop

  if ( (isset($_POST['itemtitle'])) &&(isset($_POST['itemprice'])) && (isset($_POST['itemshippingtype'])) ){
    if ( ($_POST['itemtitle'] == "") || (isset($_POST['itemprice']) == "") ){
        $_ERROR['need more information about item'] = true;
        echo "Error:need more information about item";
    }
    else{
      $itemtitle = mysql_real_escape_string($_POST['itemtitle']);
      $itemdescription = mysql_real_escape_string($_POST['itemdescription']);
      $itemprice = mysql_real_escape_string($_POST['itemprice']);
      $itemdate = date('Y-m-d H:i:s');
      if ( $_POST['itemshippingtype'] == "mail")
        $itemsaletype = "online";
      elseif ($_POST['itemshippingtype'] == "garagersale")
        $itemsaletype = "onlineoffline";
      else
        $itemsaletype = "offline";

      if ($main_picture_id != null){
        $query = "INSERT INTO item (price, name, description, sale_type, starting_date,main_picture_id) VALUES ('".intval($itemprice)."','".$itemtitle."','".$itemdescription."','".$itemsaletype."','".$itemdate."','".$main_picture_id."')";
        $result = mysqli_query($con2, $query);
        $item_id = mysqli_insert_id($con2);
        if ($item_id != null){
            $queryseller = "INSERT INTO itemseller (user_id, item_id) VALUES ('".$_SESSION['user_id']."','".$item_id."')";
            $resultseller = mysqli_query($con2, $queryseller);
            $querygl = "INSERT INTO garagersalelistings (garagersale_id, item_id) VALUES ('".$garagersale_id."','".$item_id."')";
            $resultgl = mysqli_query($con2, $querygl);
            for ($i =1; $i<=4; $i++){
              if ( ($picture_id[$i-1] != "") && ($picture_id[$i-1] != null) )
                {
                   $query = "INSERT INTO itempictures (item_id, picture_id) VALUES ('".$item_id."','".$picture_id[$i-1]."')";
                   $result = mysqli_query($con2, $query);
                }
            }
        }
        //echo "Create suceess!";
      }
    }

  }//first item


  for($j = 1; $j <= $addtime; $j++){
        $imageaddition = [];
        $formataddition = [];
        $picture_id_a = [];
        $main_picture_id_a = [];
        for ($i =1; $i<=4; $i++){
          if (isset($_POST['small'.$i.$j])){
              $imageaddition[$j][$i-1] = $_POST['small'.$i.$j];
              if (preg_match("/^data:image\/jpeg\;base64/i",$imageaddition[$j][$i-1]))
              {
                 $formataddition[$j][$i-1] = "jpeg";
                 //echo $formataddition[$j][$i-1];
              }
              elseif(preg_match("/^data:image\/gif\;base64/i",$imageaddition[$j][$i-1])){
                $formataddition[$j][$i-1] = "gif";
                //echo $formataddition[$j][$i-1];
              }
              elseif(preg_match("/^data:image\/png\;base64/i",$imageaddition[$j][$i-1])){
                $formataddition[$j][$i-1] = "png";
                //echo $formataddition[$j][$i-1];
              }
              else{
                echo "Error: wrong format of file uploaded!";
              }

              $imageaddition[$j][$i-1] = str_replace("data:image/".$formataddition[$j][$i-1].";base64,","", $imageaddition[$j][$i-1]);
              $imageaddition[$j][$i-1] = str_replace(' ', '+', $imageaddition[$j][$i-1]);
              $dataaddition = base64_decode($imageaddition[$j][$i-1]);
              $data2addition = mysqli_real_escape_string($con2, $dataaddition);
              $query = "INSERT INTO merchandisepictures (image, format, size) VALUES ('".$data2addition."','".$formataddition[$j][$i-1]."','200')";
              $result = mysqli_query($con2, $query);
              $picture_id_a[$j][$i-1] = mysqli_insert_id($con2);
              if ($i == 1){
                 $main_picture_id_a[$j] = $picture_id_a[$j][$i-1];
                 //echo $main_picture_id_a[$j];
              }

        }//if
    }//secondforloop
  }//forloop


   $itemtitlea = [];
   $itempricea = [];
   $itemdescriptiona = [];
   $itemcategorya = [];
   $itemtagsa = [];
   $itemshippingtypea = [];
   $itemshippingcosta = [];
   $itemdatea = [];
   $item_id_a = [];

  for ($j = 1; $j <= $addtime; $j++){
   
    if (isset($_POST['itemtitlea'.$j]) && isset($_POST['itemdescriptiona'.$j]) && isset($_POST['itempricea'.$j]) && isset($_POST['itemshippingtypea'.$j]))
    {

      if ( ($_POST['itemtitlea'.$j] == "") || ($_POST['itempricea'.$j] == "") || ($_POST['itemshippingtypea'.$j] == "") ){
          $_ERROR['need more information about item'] = true;
          //echo "Error: need more information about item";
      }
      
         $itemtitlea[$j-1] = mysql_real_escape_string($_POST['itemtitlea'.$j]);
         $itemdescriptiona[$j-1] = mysql_real_escape_string($_POST['itemdescriptiona'.$j]);
         $itempricea[$j-1] = mysql_real_escape_string($_POST['itempricea'.$j]);
         $itemdatea[$j-1] = date('Y-m-d H:i:s');
        if ( $_POST['itemshippingtypea'.$j] == "mail")
          $itemshippingtypea[$j-1] = "online";
        elseif ($_POST['itemshippingtypea'.$j] == "garagersale")
          $itemshippingtypea[$j-1] = "onlineoffline";
        else
          $itemshippingtypea[$j-1] = "offline";

      if ($main_picture_id_a[$j] != null){
        $query = "INSERT INTO item (price, name, description, sale_type, starting_date,main_picture_id) VALUES ('".intval($itempricea[$j-1])."','".$itemtitlea[$j-1]."','".$itemdescriptiona[$j-1]."','".$itemshippingtypea[$j-1]."','".$itemdatea[$j-1]."','".$main_picture_id_a[$j]."')";
        $result = mysqli_query($con2, $query);
        $item_id_a[$j] = mysqli_insert_id($con2);
        if ($item_id_a[$j] != null){
            $queryseller = "INSERT INTO itemseller (user_id, item_id) VALUES ('".$_SESSION['user_id']."','".$item_id_a[$j]."')";
            $resultseller = mysqli_query($con2, $queryseller);
            $querygl = "INSERT INTO garagersalelistings (garagersale_id, item_id) VALUES ('".$garagersale_id."','".$item_id_a[$j]."')";
            $resultgl = mysqli_query($con2, $querygl);
            for ($i =1; $i<=4; $i++){
              if ( ($picture_id_a[$j][$i-1] != "") && ($picture_id_a[$j][$i-1] != null) )
                {
                   $query = "INSERT INTO itempictures (item_id, picture_id) VALUES ('".$item_id_a[$j]."','".$picture_id_a[$j][$i-1]."')";
                   $result = mysqli_query($con2, $query);
                }
            }
        }
        //echo "Create suceess!";
      }

     
    }//if
  }//forloop






  }//db2


}//sessionid


?>
