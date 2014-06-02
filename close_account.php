<?php session_start(); 

include('config.php');
$con = mysqli_connect($host,$username,$password,$db_name1); 

if(isset($_SESSION['user_id'])) {
	$query = "DELETE FROM user WHERE user.user_id = " . intval($_SESSION['user_id']) ;

	$result = mysqli_query($con, $query);
	if($result == null){
		
	}
	else{
		session_unset();
		session_destroy();
	}
}

else{
	header('location: new_stuff.php');
}

?>