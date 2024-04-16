<!doctype html>
<?php
include 'includes/session.php'; 
if(isset($_SESSION['vm_user']))
        header('location: MyHome');
?>
<html lang="en" oncontextmenu="return false">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=0.70">
<meta charset="utf-8">
    <title>  Sign in </title>
    <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <style>
    body {
       background:
    <?php if (isset($_COOKIE["theme"])) 
        echo "linear-gradient( to right, #c6eaff 50%, #38b6ff 50%, #c6eaff 0%, #38b6ff 0%)";
     else 
     echo "linear-gradient(to right, rgba(235, 224, 232, 1) 52%, rgba(254, 191, 1, 1) 53%, rgba(254, 191, 1, 1) 100%)";
     ?>;
    font-family: 'Roboto', sans-serif;
}
.nav__link--active {
        color:
            <?php if (isset($_COOKIE["theme"]))
                echo "#38b6ff";
            else
                echo "rgba(254, 191, 1, 1)";
            ?>;
    }
.area{
       width: 100%;
       height:100vh;     
   }
   
   .circles{
       position: absolute;
       top: 0;
       left: 0;
       width: 95%;
       height: 95%;
       overflow: hidden;
   }
   
   .circles li{
       position: absolute;
       display: block;
       list-style: none;
       width: 20px;
       height: 20px;
       background: rgba(255, 255, 255, 0.900);
       animation: animate 25s linear infinite;
       bottom: -50px;
       
   }
   
   .circles li:nth-child(1){
       left: 25%;
       width: 80px;
       height: 80px;
       animation-delay: 0s;
   }
   
   
   .circles li:nth-child(2){
       left: 10%;
       width: 20px;
       height: 20px;
       animation-delay: 0s;
       animation-duration: 12s;
   }
   
   .circles li:nth-child(3){
       left: 70%;
       width: 20px;
       height: 20px;
       animation-delay: 4s;
   }
   
   .circles li:nth-child(4){
       left: 40%;
       width: 60px;
       height: 60px;
       animation-delay: 0s;
       animation-duration: 18s;
   }
   
   .circles li:nth-child(5){
       left: 65%;
       width: 20px;
       height: 20px;
       animation-delay: 0s;
   }
   
   .circles li:nth-child(6){
       left: 75%;
       width: 110px;
       height: 110px;
       animation-delay: 0s;
   }
   
   .circles li:nth-child(7){
       left: 35%;
       width: 150px;
       height: 150px;
       animation-delay: 0s;
   }
   
   .circles li:nth-child(8){
       left: 50%;
       width: 25px;
       height: 25px;
       animation-delay: 0s;
       animation-duration: 45s;
   }
   .circles li:nth-child(9){
       left: 20%;
       width: 15px;
       height: 15px;
       animation-delay: 0s;
       animation-duration: 115s;
   }
   
   .circles li:nth-child(10){
       left: 85%;
       width: 150px;
       height: 150px;
       animation-delay: 0s;
       animation-duration: 100s;
   }
   
   
   .circles li:nth-child(11){
       left: 20%;
       width: 15px;
       height: 15px;
       animation-delay: 0s;
       animation-duration: 115s;
   }
   
   .circles li:nth-child(12){
       left: 85%;
       width: 150px;
       height: 150px;
       animation-delay: 0s;
       animation-duration: 100s;
   }
   
   
   
   @keyframes animate {
   
       0%{
           transform: translateY(0) rotate(0deg);
           opacity: 1;
           border-radius: 0;
       }
   
       100%{
           transform: translateY(-1000px) rotate(720deg);
           opacity: 0;
           border-radius: 50%;
       }
   
   }
    </style>
    <body>
    <div class="area" >
            <ul class="circles">
                    
                    <li></li><li></li>
                   <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                   <li></li>
                   <li></li>
                   <li></li>
                  <ol></ol>
            </ul>
    </div >
         <div class="login-box">
             <a  href="MyHome" style=" color: #ff1c1c;float:right;text-decoration: none;font-size:x-large;">X</a>
 
           <h2>Sign in</h2>
           <form action="verify.php" method="post">
             <div class="user-box">
               <input type="text" name="email" required="" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']; ?>">
               <label>Email</label>
             </div>
             <div class="user-box">
               <input type="password" name="password" required="" value="<?php if(isset($_SESSION['password'])) echo $_SESSION['password']; ?>">
               <label>Password</label>
               </div>
             <div class="user-box">
                 <a href="ForgotMe" style="font-size:10px;">Forgot Password..?</a>
             </div>
                               <center>  <?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='text-center' style='color:red;'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }
        if(isset($_SESSION['success'])){
        echo "
          <div class='text-center' style='color:green;'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?></center>
             <a  style="float:right;">
               <span></span>
               <span></span>
               <span></span>
               <span></span>
               <input type="submit" name="login" value="SIGN IN"
                      style="border:none;
                      background:none;
                      outline: none;
                      transition: .5s;
                      letter-spacing: 4px;
                      display: inline-block;
                      color: #03e9f4;
                      text-transform: uppercase;
                      font-weight: 600;
                      text-decoration: none;
                      overflow: hidden;
                      font-size: 18px;
" >
             </a>
           </form><br><br><br><br><br>
           <center>
           <a  href="JoinUs" 
                     style="color:aquamarine;text-decoration: none;">
                      Don't have an account?<b> Sign Up.</b>
             </a>
           </center>
         </div>
    </body>
    </html>