<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/login.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"
        id="bootstrap-css">
    <style>
        

    </style>
    
</head>

<body>
    <?php 
    include('Nav.php');
    ?>
    
    
        <div class="form-container d-flex flex-column justify-content-center">
            <div class="container register">
                <div class="row">
                <div class="col-md-12 register-right">
                        <div class="mb-5">
                            <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                        aria-controls="home" aria-selected="true">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                        aria-controls="profile" aria-selected="false">Registration</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content" id="myTabContent">
        
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h3 class="register-heading">Registration</h3>
                                <div class="row register-form">
                                    <div class="col-md-6">
                                        <form action="register.php" method="POST">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="FName" placeholder="First Name *"
                                                    required />
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="Lname" placeholder="Last Name *"
                                                    required />
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" id="pws" name="pws"
                                                    placeholder="Password *" required oninput="checkPasswordRequirements()" onfocus="showPasswordMessage()" onblur="hidePasswordMessage()" />
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="pwer"
                                                    placeholder="Confirm Password *" required />
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="Email" placeholder="Your Email *"
                                                required />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" minlength="14" maxlength="14" class="form-control" name="Phone"
                                                placeholder="Your Phone *" required />
                                        </div>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="dob" required />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="Add"
                                                placeholder="Enter Your Address *" />
                                        </div>
                                    </div>
                                    <div class="col-md-6" >
                                        <ul class="pass_requirement" id="password-message">
                                            <li id="length" class="invalid">Must contain at least 6 characters</li>
                                            <li id="uppercase" class="invalid">Must contain at least one uppercase letter</li>
                                            <li id="lowercase" class="invalid">Must contain at least one lowercase letter</li>
                                            <li id="number" class="invalid">Must contain at least one number</li>
                                            <li id="special" class="invalid">Must contain at least one special character (e.g.,
                                                !@#$%^&*)</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                    <input type="submit" class="btnRegister" name="registration" value="Register" />
                                    </div>
                                    
                                    </form>
                                    <!-- <h5>متطلبات كلمة السر:</h5> -->
                                    
        
                                </div>
                            </div>
        
                            <!-- LOGIN Tab -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <h3 class="register-heading">Users Login</h3>
                                <div class="row register-form">
                                    <div class="col-md-6">
                                        <form action="login.php" method="post">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="Email *"
                                                    required />
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password" placeholder="Password *"
                                                required />
                                        </div>
                                        <input type="submit" class="btnRegister" name="login" value="Log In" />
                                    </div>
                                    <!-- Message Error Here -->
                                    <?php if (isset($_SESSION['error'])): ?>
                                    <div class="error-message col-md-6"><?php echo $_SESSION['error']; ?></div>
                                    <?php unset($_SESSION['error']); ?>
                                    <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script>
    function checkPasswordRequirements() {
        const password = document.getElementById("pws").value;
        const uppercase = /[A-Z]/.test(password);
        const lowercase = /[a-z]/.test(password);
        const number = /[0-9]/.test(password);
        const specialChar = /[!@#$%^&*]/.test(password);
        const minLength = password.length >= 6;

        document.getElementById("length").className = minLength ? "valid" : "invalid";
        document.getElementById("uppercase").className = uppercase ? "valid" : "invalid";
        document.getElementById("lowercase").className = lowercase ? "valid" : "invalid";
        document.getElementById("number").className = number ? "valid" : "invalid";
        document.getElementById("special").className = specialChar ? "valid" : "invalid";
    }
    function showPasswordMessage() {
            document.getElementById("password-message").style.display = "block";
        }

        function hidePasswordMessage() {
            document.getElementById("password-message").style.display = "none";
        }
    </script>
</body>

</html>