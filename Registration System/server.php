 <?php
	
	session_start();
	
	$username = "";
	$email = "";
	$errors = array();
	
	//Connect to the database
	$db = mysqli_connect('localhost', 'root', '', 'registration');
	
	//if the register button is clicked
	if (isset($_POST['register']))
	{
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db ,$_POST['email']);	
		$password_1 = mysqli_real_escape_string($db ,$_POST['Password_1']);
		$password_2 = mysqli_real_escape_string($db ,$_POST['Password_2']);
		
		//ensure that form fields are filled properly
		
		if (empty($username))
		{
			array_push($errors, "<b>Username is required</b>");
		}
		
		if (empty($email))
		{
			array_push($errors, "<b>Email is required</b>");
		}
		
		if (empty($password_1))
		{
			array_push($errors, "<b>Password is required</b>");
		}
		
		
 
		if ($password_1 != $password_2)
		{
			array_push($errors, "The two passwords do not match");
			
		}
		
		//if there are no errrors, save users to database
		if(count($errors) == 0)
		{	
			$password = md5($password_1); //encrypt password before storing in database (sequrity)
			$sql = "INSERT INTO users (Username, email, Password) 
						VALUES ('$username','$email', '$password')";
						
			mysqli_query($db, $sql);
			$_SESSION['username'] = $username;
			$_SESSION['success'] = $username;
			header('location: index.php');//redirect to home page
		}
		
	}
	
	//Log user in from log in page
	if (isset($_POST['login']))
	{
		$username = mysqli_real_escape_string($db, $_POST['username']);
		
		$password = mysqli_real_escape_string($db ,$_POST['Password']);
		
		
		//ensure that form fields are filled properly
		
		if (empty($username))
		{
			array_push($errors, "<b>Username is required</b>");
		}
		
		if (empty($password))
		{
			array_push($errors, "<b>Password is required</b>");
		}
		if (count($errors) == 0)
		{
			$password = md5($password); //encrypt password before comparing with db 
			$query = "SELECT * FROM users WHERE Username = '$username' AND Password = '$password'";
			$result = mysqli_query($db, $query);
				if (mysqli_num_rows($result) == 1)
				{
					//Log user in 
					$_SESSION['username'] = $username;
					$_SESSION['success'] = $username;
					header('location: index.php');
				}else{
					array_push($errors, "Wrong username password combination ");
					
				}
		}
		
	}
	
	//Logout
	if (isset($_GET['logout']))
	{
		session_destroy();
		unset($_SESSION['username']);
		header('location:login.php');
	}
	
 ?>