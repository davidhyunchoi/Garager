<?php session_start();
include ('config.php');
	if (isset ($_SESSION['user_id'])){
		if (isset($_POST['user_name'])){
			$follower = $_SESSION['user_id'];
			$following_Name = $_POST['user_name'];

			$con = mysqli_connect($host,$username,$password,$db_name1);
			$query = "SELECT user_id from accountinfo WHERE user_name = '". $following_Name . "' LIMIT 1;"; 
			$result = mysqli_query($con, $query);
			
			if($result == null or $result->num_rows == null){}
			else {
				$following = 0;
				while($row = $result->fetch_array()){
					$following = $row['user_id'];
				}

				$query2 = "INSERT INTO following VALUES (". intval($follower).", ".intval($following).")" ;
				if (!mysqli_query($con,$query2))
  					die('Error: ' . mysqli_error($con));
				else
					header("HTTP/1.0 200 OK"); 
			}
		}
		else{
			header("HTTP/1.0 404 Not Found");
		}

	}

	else{
		echo "Please log in to access this";
	}	
?>