<?php
$showFormular = true;
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=emailsdatenbank', 'root', '');
if(isset($_REQUEST['register'])) {
    $error = false;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
  
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
        $error = true;
    }     
    if(strlen($password) == 0) {
        echo 'Bitte ein password angeben<br>';
        $error = true;
    }
    if($password != $password2) {
        echo 'Die Passwörter müssen übereinstimmen<br>';
        $error = true;
    }
    
    if(!$error) { 
        $statement = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();
        
        if($user !== false) {
            echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
            $error = true;
        }    
    }
    
    if(!$error) {    
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $statement = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (:username, :email, :password)");
        $result = $statement->execute(array('username' => $username, 'email' => $email, 'password' => $password_hash));
        
        if($result) {        
            $showFormular = false;
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    } 
}

if(isset($_REQUEST['login'])) {
    $email = $_POST['email'];
    $passwort = $_POST['password'];
    
    $statement = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
    $user = $statement->fetch();
        
    if ($user !== false && password_verify($passwort, $user['passwort'])) {
        $_SESSION['userid'] = $user['id'];
        die('Login erfolgreich. Weiter zu <a href="geheim.php">internen Bereich</a>');
    } else {
        $errorMessage = "E-Mail oder Passwort war ungültig<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="enfl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-
    scale=1.0">
    <title>All Products - Shopping</title>
    <link rel="stylesheet" href="style.css" />

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="undefined" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="./index.html"><img src="images/logo.png" width="125px"></a>
            </div>
            <nav>
                <ul id="MenuItems">
                    <li><a href="./index.html">Home</a></li>
                    <li><a href="./products.html">Products</a></li>
                    <li><a href="./about-us.html">About</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="./account.html">Account</a></li>
                </ul>
            </nav>
            <a href="./cart.html"><img src="images/cart.png" width="30px" height = "30px"></a>
            <img src="images/menu.png" class="menu-icon" 
            onclick="menutoggle()">
        </div>
    </div>
    <!--account-page-->
    <div class="account-page">
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <img src="images/buy-1.png" width="100%">
                </div>

                <div class="col-2">
                    <div class="form-container">
                        <div class="form-btn">
                            <span onclick="login()">Login</span>
                            <span onclick="register()">Register</span>
                            <hr id="Indicator">
                        </div>
                        <form id="LoginForm" method="post">
                            <input type="text" placeholder="Email" name="email">
                            <input type="password" placeholder="Password" name="password">
                            <button type="submit" class="btn" name="login">Login</button>
                        </form>
                        <form id="RegForm" method="post">
                            <input type="text" placeholder="Username" name="username">
                            <input type="email" placeholder="Email" name="email">
                            <input type="password" placeholder="Password" name="password">
                            <input type="password" placeholder="Password" name="password2">
                            <button type="submit" class="btn" name="register">Register</button>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---------footer------->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col-1">
                    <h3>Download our App</h3>
                    <p>Download App for Android and ios mobile phone.</p>
                    <div class="app-logo">
                        <img src="images/play-store.png">
                        <img src="images/app-store.png">
                    </div>
                </div>
                <div class="footer-col-2">
                    <img src="images/logo.png">
                    <p>Our purpose is to sustainably make the pleasure and Benefits of sports accessible to the many.
                    </p>
                </div>
                <div class="footer-col-3">
                    <h3>Useful Links</h3>
                    <ul>
                        <li>Coupons</li>
                        <li>Blog Post</li>
                        <li>Return Policy</li>
                        <li>Join Affiliate</li>
                    </ul>
                </div>
                <div class="footer-col-4">
                    <h3>Follow us</h3>
                    <ul>
                        <a style="color: rgb(187, 182, 175);" href="https://de-de.facebook.com/">
                            <li>Facebook</li>
                        </a>
                        <a style="color: rgb(187, 182, 175);" href="https://twitter.com/?lang=de">
                            <li>Twitter</li>
                        </a>
                        <a style="color: rgb(187, 182, 175);" href="https://www.instagram.com/">
                            <li>Instagram</li>
                        </a>
                        <a style="color: rgb(187, 182, 175);" href="https://www.youtube.com/">
                            <li>Youtube</li>
                        </a>
                        
                    </ul>
                </div>
            </div>
            <hr>
            <p class="copyright">has been written by Rsheed Alo</p>
        </div>
    </div>

    <!-------js for toggle menu------>
    <script>
        var MenuItems = document.getElementById("MenuItems");
        MenuItems.style.maxHeight = "0px";

        function menutoggle() {
            if (MenuItems.style.maxHeight == "0px") {
                MenuItems.style.maxHeight = "200px";
            }
            else {
                MenuItems.style.maxHeight = "0px";
            }
        }
    </script>

    <script>
        var loginForm = document.getElementById('LoginForm')
        var RegForm = document.getElementById('RegForm')
        var Indicator = document.getElementById('Indicator')
        function register(){
            RegForm.style.transform = "translateX(0px)";
            loginForm.style.transform = "translateX(0px)";
            Indicator.style.transform = "translateX(100px)";
        }
        function login(){
            RegForm.style.transform = "translateX(300px)";
            loginForm.style.transform = "translateX(300px)";
            Indicator.style.transform = "translateX(0px)";
        }
    </script>

</body>
</html>
<!--45:30-->