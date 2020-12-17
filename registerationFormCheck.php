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
 $servername = "localhost";
 $rootuser="root";
 $db="socnet";
 $rootpassword ="";
// Create connection
$conn = new mysqli($servername, $rootuser, $rootpassword, $db);
// Check connection
if ($conn->connect_error) 
{
  die("Connection failed: " . $conn->connect_error);
}

//Values from form
$username = htmlspecialchars($_POST['txtUsername'], ENT_NOQUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['txtEmail'], ENT_NOQUOTES, 'UTF-8');
$dateOfBirth = htmlspecialchars($_POST['txtDateOfBirth'], ENT_NOQUOTES, 'UTF-8');
$address = htmlspecialchars($_POST['txtAddress'], ENT_NOQUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['txtPassword'], ENT_NOQUOTES, 'UTF-8');


 
 
 // validate token
 

// Hash password use BCRYPT
// for Argon2i you will just need to repalce BCRYPT with Argon2i
$hash = password_hash ($password , PASSWORD_BCRYPT, array("cost" => 10));
//  INSERT query   , check hash variable in the Values statement 
//$sql = "INSERT INTO Restaurant (Username , Password, Email, DateOfBirth, Address)
//VALUES ('$username', '$hash', '$email' , '$dateOfBirth', '$address')";

//$sql = "INSERT INTO Restaurant (Username , Password, Email, DateOfBirth, Address) VALUES (?, ?, ? , ?, ?)";
//$stmt = $conn->prepare($sql);
//$stmt->bind_param("sssss", $username, $hash, $email, $dateOfBirth, $address);
//$stmt->execute();

//echo "New record created successfully";

//$conn->close();

$stmt1 = $conn->prepare('SELECT * FROM Restaurant WHERE Username = ?');
$stmt1->bind_param('s', $username);
$stmt1->execute();
$userResult = $stmt1->get_result();

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
            echo "$username has been used";
        }
    }
}
if ($userFound == 0)
{
    $sql = "INSERT INTO Restaurant (Username , Password, Email, DateOfBirth, Address) VALUES (?, ?, ? , ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $hash, $email, $dateOfBirth, $address);
    $stmt->execute();

    echo "New record created successfully";
}
$conn->close();
?>


