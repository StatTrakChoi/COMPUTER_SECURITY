<?php
 session_start(); 
 if ($_SERVER['REQUEST_METHOD'] == 'POST' and (! isset($_POST['token']) || $_POST['token'] == $_SESSION['token'])) 
 { 
   echo 'request success'; 
   echo "<br />";
 } 
 else 
 { 
   echo 'csrf forbidden'; 
   echo "<br />";
  }

  ?>

  <?php
  define('SITE_KEY', '6LfAOAkaAAAAAEbFqR5eYDZRlzXPcqj-QMlWzHrc');
  define('SECRET_KEY', '6LfAOAkaAAAAAI22WnKGzF_Vs_nQLJf1v-nc2m1f');
  
  if($_POST){
	  function getCaptcha($SecretKey){
		  $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response={$SecretKey}");
		  $Return = json_decode($Response);
		  return $Return;
	  }
	  $Return = getCaptcha($_POST['g-recaptcha-response']);
	  //var_dump($Return);
	  if($Return->success == true && $Return->score > 0.5){
		  echo "recaptcha Success!";
		  echo "<br />";
	  }else{
		  echo "You are a Robot!!";
		  echo "<br />";
	  }
  }
  
  ?>





<?php
// server and db connection values
 $servername = "localhost";
 $rootUser="root";
 $db="socnet";
 $rootPassword ="";

// Create connection
$conn = new mysqli($servername, $rootUser, $rootPassword, $db);

// values come from user entry in webform
$username = htmlspecialchars($_POST['txtUsername'], ENT_NOQUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['txtPassword'], ENT_NOQUOTES, 'UTF-8');

// Check connection
if ($conn->connect_error) 
{
  die("Connection failed: " . $conn->connect_error);
}
// query
//$userQuery = "SELECT * FROM Restaurant";
//$userResult = $conn->query($userQuery);
$stmt = $conn->prepare('SELECT * FROM Restaurant WHERE Username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$userResult = $stmt->get_result();

// flag type variable 
$userFound = 0;

echo "<table border='1'>";
if ($userResult->num_rows > 0)
{
  	while($userRow = $userResult->fetch_assoc()) 
    {
		if ($userRow['Username'] == $username)
		{
			$userFound = 1; 
			$getPassword = strval($userRow['Password']);
			echo $getPassword;
			echo "<br/>";
		
			   echo "<br/>";
			   // If the password verify is either printing wrong or success all the time
			   // check the length of the password in the database. It will not be storing all 
			   // the hashes characters. Change it to something like 200 characters
			if (password_verify($password, $getPassword))	
			{
				echo "Hi" .$username. "!";
				echo "<br/> Welcome to our website";
			}
			else
			{
				echo "Wrong password";
			}
			
		}
	}
}
if ($userFound == 0)
{
	echo "This user was not found in our Database";
}
 
 ?>
