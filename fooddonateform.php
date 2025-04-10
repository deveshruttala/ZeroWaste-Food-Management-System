<?php
ob_start(); // Start output buffering

include("login.php");
if($_SESSION['name']==''){
    header("location: signin.php");
    exit(); // Ensure the script stops here after redirection
}

$emailid= $_SESSION['email'];
$connection=mysqli_connect("db","phpmyadmin","phpmyadmin");
$db=mysqli_select_db($connection,'demo');

if(isset($_POST['submit']))
{
    $foodname=mysqli_real_escape_string($connection, $_POST['foodname']);
    $meal=mysqli_real_escape_string($connection, $_POST['meal']);
    $category=$_POST['image-choice'];
    $quantity=mysqli_real_escape_string($connection, $_POST['quantity']);
    $phoneno=mysqli_real_escape_string($connection, $_POST['phoneno']);
    $district=mysqli_real_escape_string($connection, $_POST['district']);
    $address=mysqli_real_escape_string($connection, $_POST['address']);
    $name=mysqli_real_escape_string($connection, $_POST['name']);

    $query="insert into food_donations(email,food,type,category,phoneno,location,address,name,quantity) values('$emailid','$foodname','$meal','$category','$phoneno','$district','$address','$name','$quantity')";
    $query_run= mysqli_query($connection, $query);

    if($query_run)
    {
        echo '<script type="text/javascript">alert("Data saved successfully!")</script>';
        header("location:delivery.html");
        exit(); // Ensure the script stops here after redirection
    }
    else{
        echo '<script type="text/javascript">alert("Data not saved!")</script>';
    }
}

ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body style="background-color: #06C167;">
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Food <b style="color: #06C167; ">Donate</b></p>
                
                <div class="input">
                    <label for="foodname">Food Name:</label>
                    <input type="text" id="foodname" name="foodname" required/>
                </div>
              
                <div class="radio">
                    <label for="meal">Meal type :</label> 
                    <br><br>
                    <input type="radio" name="meal" id="veg" value="veg" required/>
                    <label for="veg" style="padding-right: 40px;">Veg</label>
                    <input type="radio" name="meal" id="Non-veg" value="Non-veg" />
                    <label for="Non-veg">Non-veg</label>
                </div>
                <br>
                <div class="input">
                    <label for="food">Select the Category:</label>
                    <br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                        <label for="raw-food">
                          <img src="img/raw-food.png" alt="raw-food" >
                        </label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
                        <label for="cooked-food">
                          <img src="img/cooked-food.png" alt="cooked-food" >
                        </label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food">
                          <img src="img/packed-food.png" alt="packed-food" >
                        </label>
                    </div>
                </div>
                <br>
                <div class="input">
                    <label for="quantity">Quantity (number of person / kg):</label>
                    <input type="text" id="quantity" name="quantity" required/>
                </div>
               <b><p style="text-align: center;">Contact Details</p></b>
                <div class="input">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required/>
                    </div>
                    <div>
                        <label for="phoneno">Phone No:</label>
                        <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required />
                    </div>
                </div>
                <div class="input">
                    <label for="district">District:</label>
                    <select id="district" name="district" style="padding:10px;">
                        <option value="chennai">Chennai</option>
                        <option value="kancheepuram">Kancheepuram</option>
                        <option value="madurai">Madurai</option>
                        <!-- Add more districts as required -->
                    </select>
                    <label for="address" style="padding-left: 10px;">Address:</label>
                    <input type="text" id="address" name="address" required/><br>
                </div>
                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
