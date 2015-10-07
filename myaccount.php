<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Graphic Novels</title>
    <meta charset='utf-8'>
    <meta name='description' content='Read and write some of the most creative stories on the web.'>
    <meta name='keywords' content='comic, comics, graphic novel, novel, online'>
    <meta name='author' content='James McGarr'>                                                  <!--Create a favicon for this-->
    <link href='/favicon.png'   rel='shortcut icon' type='image/png'> 
    <link href='newTags.css'    rel='stylesheet'    type='text/css'>                             <!--Normalising css style attributes       -->
    <link href='main.css'       rel='stylesheet'    type='text/css'>                  
    <link href='nav.css'        rel='stylesheet'    type='text/css'>
    <link href='content.css'    rel='stylesheet'    type='text/css'>
    <link href='responsive.css' rel='stylesheet'    type='text/css'>
    <script type="text/javascript" src="cookieMonster.js"></script>
                                                                                                 <!--Creating new html tag for older brouser-->
                                                                                                 <!--Find out if new tags need to be created for old brousers-->
    <!--[if lt IE 9]> 
        <script type="text/javascript"> document.createElement("stack"); </script>               
    <![endif]-->
    
  <?php
  
  
  
  $databaseConnection = mysqli_connect("localhost", "root", "", "booksys");
  if (mysqli_connect_errno($databaseConnection)) { echo "Failed to connect to database.".mysqli_connect_error();}
  
  
  
  if (isset($_POST['submitlogin']))
  {      
      $memberEmail = $_POST['usrName']; $memberPass = $_POST['usrPass'];
      
      
      $sqlQuery    = "SELECT * 
                      FROM members
                      WHERE '$memberEmail' = eMail AND
                            '$memberPass'  = password";     /* I'd have to do it a different way to strip slashes but this works for now */
      $resultSet   = mysqli_query($databaseConnection,$sqlQuery);
      if (!$resultSet)
      {
          echo('Query failed ' . $sqlQuery . ' Error: ' . mysqli_error());
          exit();
      }
      else
      {
          if (mysqli_num_rows($resultSet) < 1)
          {
              echo "<script>alert('Invalid username password combination.');</script>";
          }
          else
          {                             
              echo "<script>setCookie('usrEmail', '$memberEmail', 1);</script>";
          }
      }
                                           
      
      
  }
  else if (isset($_POST['submitcreateaccount']))
  {
      
      $fName       = $_POST['fName'   ]; $lName    = $_POST['lName'   ];
      $email       = $_POST['email'   ]; $pass     = $_POST['pass1'   ];
      $address1    = $_POST['address1']; $address2 = $_POST['address2'];
      $city        = $_POST['city'    ]; $county   = $_POST['county'  ];
      $phonenumber = $_POST['phone'   ];
       
      $fName       = mysqli_real_escape_string($databaseConnection, $fName);
      $lName       = mysqli_real_escape_string($databaseConnection, $lName);
      $email       = mysqli_real_escape_string($databaseConnection, $email);
      $pass        = mysqli_real_escape_string($databaseConnection, $pass );
      $address1    = mysqli_real_escape_string($databaseConnection, $address1);
      $address2    = mysqli_real_escape_string($databaseConnection, $address2);
      $city        = mysqli_real_escape_string($databaseConnection, $city);
      $county      = mysqli_real_escape_string($databaseConnection, $county);
      $phonenumber = mysqli_real_escape_string($databaseConnection, $phonenumber);
      
      $sqlQuery = "INSERT INTO members(fName,lName,eMail,password,address1,address2,city,county,phoneNum,credits)
                   VALUES ('$fName','$lName','$email','$pass','$address1','$address2','$city','$county','$phonenumber',0.0)";
      $resultSet = mysqli_query($databaseConnection, $sqlQuery);
      if ($resultSet == 0)
      {
          echo "Error in sql query ". mysqli_error($databaseConnection);
      }              
      else
      {
          echo "<script type='text/javascript'> alert('Account Creation Successful!');</script>";
      }               
  }
  else if (isset($_POST['deleteAccount']))
  {
      $email = $_POST['usrEmail'];
      
      
      $MemID='';
      $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                     FROM members
                                                     WHERE Email = '$email'");
      while ($memberInfo = mysqli_fetch_array($resultSet))
      {
          $MemID = $memberInfo['MemID']; 
      }         
      $orderID='';
      $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                     FROM orders
                                                     WHERE MemID = $MemID");
      while ($orderInfo = mysqli_fetch_array($resultSet))
      {
          $orderID = $orderInfo['orderID']; 
      } 
      echo "<script>alert('$orderID');</script>";
      
                                           
      mysqli_query($databaseConnection,"DELETE FROM basket
                                        WHERE MemID = $MemID");  
      mysqli_query($databaseConnection,"DELETE FROM orderitems
                                        WHERE orderID = $orderID");
      mysqli_query($databaseConnection,"DELETE FROM orders
                                        WHERE MemID   = $MemID");  
      mysqli_query($databaseConnection,"DELETE FROM members
                                        WHERE MemID   = $MemID");   
                                        
  }
  else if (isset($_POST['submitChangeAccountDetails']))
  {
      $theEmail = $_POST['usrEmail'];
      
      $fName       = $_POST['fName'   ]; $lName    = $_POST['lName'   ];
      $email       = $_POST['email'   ]; $pass     = $_POST['pass1'   ];
      $address1    = $_POST['address1']; $address2 = $_POST['address2'];
      $city        = $_POST['city'    ]; $county   = $_POST['county'  ];
      $phonenumber = $_POST['phone'   ];
       
      $fName       = mysqli_real_escape_string($databaseConnection, $fName);
      $lName       = mysqli_real_escape_string($databaseConnection, $lName);
      $email       = mysqli_real_escape_string($databaseConnection, $email);
      $pass        = mysqli_real_escape_string($databaseConnection, $pass );
      $address1    = mysqli_real_escape_string($databaseConnection, $address1);
      $address2    = mysqli_real_escape_string($databaseConnection, $address2);
      $city        = mysqli_real_escape_string($databaseConnection, $city);
      $county      = mysqli_real_escape_string($databaseConnection, $county);
      $phonenumber = mysqli_real_escape_string($databaseConnection, $phonenumber);
      
      $sqlQuery = "UPDATE members 
                   SET fName    = '$fName',
                       lName    = '$lName',
                       eMail    = '$email',
                       password = '$pass',
                       address1 = '$address1',
                       address2 = '$address2',
                       city     = '$city',
                       county   = '$county',
                       phoneNum = '$phonenumber'
                   WHERE eMail  = '$theEmail'";
                   
      $resultSet = mysqli_query($databaseConnection, $sqlQuery);
      if ($resultSet == 0)
      {
          echo "Error in sql query ". mysqli_error($databaseConnection);
      }              
      else
      {
          echo "<script type='text/javascript'> 
                    alert('Account Details Changed');
                    setCookie('usrEmail', '$email', 1);
                    document.getElementById('welcomeMessage').innerHTML = 'Welcome ' + getCookie('usrEmail');
                </script>";
      }    
  }
  
  
  mysqli_close($databaseConnection);
  
  ?>
  
  
  
  
  
  <style type="text/css">
  form
  {
  margin:20px;
  }
  div.input
  {
  width:150px;
  display:inline-block;
  }
  p#welcomeMessage
  {
  margin:20px;
  }
  form#changeMemberDetails
  {
  display:none;
  }
  </style>
  <script type="text/javascript">
  function checkLogin()
  {
      if (checkCookie() == 1)
      {
          document.getElementById('loginForm').style.display  = 'none';
          document.getElementById('welcomeMessage').innerHTML = 'Welcome ' + getCookie('usrEmail');
          document.getElementById('logoutForm').style.display = 'block';
          document.getElementById('theUsersEmail').value      = getCookie('usrEmail');
          document.getElementById('theUsersEmail2').value      = getCookie('usrEmail');
          document.getElementById('createAccountForm').style.display  = 'none';
          document.getElementById('changeMemberDetails').style.display  = 'block';
          
      }
  }
  
  </script>
  
  </head>
  <body onload="checkLogin()">
      <nav>
          <ul class="headerMenu">
              <a class="headerMenu" href="index.php"><li class="headerMenu">Home - All Books</li></a>
              <a class="headerMenu" href="basket.php"><li class="headerMenu">Basket</li></a>
              <a class="headerMenu" href="checkout.php"><li class="headerMenu">Checkout</li></a>
              <a class="headerMenu" href="myaccount.php"><li class="headerMenu">My Account</li></a>  
          </ul>
      </nav>   
      <contentArea>   
        <div class="spacer">
          <div style="border:3px solid #666666; border-radius:20px; background-color:#CFCFCF;">
          <p id="welcomeMessage"></p>
          <form style="display:none;" id="logoutForm" action="myaccount.php" method="post">
              <input type="button" value="LogOut" onclick="setCookie('usrEmail', '', 1); window.location.href = 'myaccount.php';">
              <input type="submit" name="deleteAccount" value="Delete My Account" onclick="setCookie('usrEmail', '', 1);">
              <input id="theUsersEmail" type="hidden" name="usrEmail" value="">
          </form>
          <form id="loginForm" name="login" action="myaccount.php" method="post">
              <div class="input">Name:     </div><input name="usrName" type="text"     placeholder="JohnDoh@example.com"><br>
              <div class="input">Password: </div><input name="usrPass" type="password" placeholder="******"><br>
              <input name="submitlogin" type="submit" value="Login"> 
          </form><hr>   
          <form id="createAccountForm" action="myaccount.php" method="post">
              <div class="input">First Name:       </div>   <input name="fName"    type="text"     placeholder="John">               <br>
              <div class="input">Last Name:        </div>   <input name="lName"    type="text"     placeholder="Doh">                <br>
              <div class="input">e-mail:           </div>   <input name="email"    type="text"     placeholder="JohnDoh@example.com"><br>
              <div class="input">Password:         </div>   <input name="pass1"    type="password" placeholder="TgD45d">             <br> 
              <div class="input">Confirm Password: </div>   <input name="pass2"    type="password" placeholder="TgD45d">             <br>
              <div class="input">Address 1:        </div>   <input name="address1" type="text"     placeholder="example house">      <br>
              <div class="input">Address 2:        </div>   <input name="address2" type="text"     placeholder="example road">       <br>
              <div class="input">City:             </div>   <input name="city"     type="text"     placeholder="example city">       <br>
              <div class="input">County:           </div>   <input name="county"   type="text"     placeholder="Dublin">             <br>
              <div class="input">Phone Number:     </div>   <input name="phone"    type="text"     placeholder="0973848832">         <br>
              <input name="submitcreateaccount" type="submit" value="Create New Account">
          </form>
          
          
          <form id="changeMemberDetails" action="myaccount.php" method="post">
              <div class="input">First Name:       </div>   <input name="fName"    type="text"     placeholder="John">               <br>
              <div class="input">Last Name:        </div>   <input name="lName"    type="text"     placeholder="Doh">                <br>
              <div class="input">e-mail:           </div>   <input name="email"    type="text"     placeholder="JohnDoh@example.com"><br>
              <div class="input">Password:         </div>   <input name="pass1"    type="password" placeholder="TgD45d">             <br> 
              <div class="input">Confirm Password: </div>   <input name="pass2"    type="password" placeholder="TgD45d">             <br>
              <div class="input">Address 1:        </div>   <input name="address1" type="text"     placeholder="example house">      <br>
              <div class="input">Address 2:        </div>   <input name="address2" type="text"     placeholder="example road">       <br>
              <div class="input">City:             </div>   <input name="city"     type="text"     placeholder="example city">       <br>
              <div class="input">County:           </div>   <input name="county"   type="text"     placeholder="Dublin">             <br>
              <div class="input">Phone Number:     </div>   <input name="phone"    type="text"     placeholder="0973848832">         <br> 
              <input id="theUsersEmail2" type="hidden" name="usrEmail" value="">
              <input name="submitChangeAccountDetails" type="submit" value="Change Account Details">
          </form> 
          </div>  
        </div>  
          
                                                             
      </contentArea>
      <footer>
          <div class="foot">  
              <form name="searchBar" class="foo" action="javascript:void(0);">  <!-- javascript code for action from    http://stackoverflow.com/questions/1818249/form-with-no-action-and-where-enter-does-not-reload-page -->                
                  <input name="searchText" class="text" type="text">                             
                  <input class="search" type="image" onclick="find(document.searchBar.searchText.value);" src="search.png" alt="search_icon">  
              </form>     
              <a class="f" href="checkout.php"><img class="cart" src="cart.png" alt="shopping_cart"><p class="check">Checkout</p> </a>
              <div class="ba"><a class="f" href="basket.php"><p class="bas">Basket</p><img class="basket" src="basket.png" alt="basket_icon"></a></div>
          </div>
      </footer>
  
  </body>
</html>