<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scentify</title>
    <link rel="stylesheet" href="./assets/css/login.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"
        id="bootstrap-css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/gallery/title_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/gallery/title_logo.png">
    <meta name="msapplication-TileImage" content="assets/img/gallery/title_logo.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="./assets/css/style.css">
    
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
                                        <form action="register.php" method="POST" id="register">
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
                                                <input type="password" class="form-control" name="pwer" id="pwsConfirm"
                                                    placeholder="Confirm Password *" required />
                                                    <p class="text-danger d-none" id="password_match">password does not match</p>
                                                    <p class="text-danger d-none" id="password_strong">password is not strong</p>
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
                                            <input type="date" class="form-control" name="dob" id="date_of_birth" required />
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
                                    <div class="invalid col-md-6"><?php echo $_SESSION['error']; ?></div>
                                    <?php unset($_SESSION['error']); ?>
                                    <?php endif; ?>
                                    <?php if (isset($_SESSION["success"])): ?>
                                    <div class="valid col-md-6"><?php echo $_SESSION['success']; ?></div>
                                    <?php unset($_SESSION['success']); ?>
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
    document.getElementById("register").addEventListener("submit", function(e) {
    let passwordMatch = document.getElementById("pws").value === document.getElementById("pwsConfirm").value;
    let strongPassword = checkPasswordRequirements();

    if (!passwordMatch) {
        e.preventDefault();
        document.getElementById("password_match").classList.remove("d-none");
    } else {
        document.getElementById("password_match").classList.add("d-none");
    }

    if (!strongPassword) {
        e.preventDefault();
        document.getElementById("password_strong").classList.remove("d-none");
    } else {
        document.getElementById("password_strong").classList.add("d-none");
    }
});

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

    return minLength && uppercase && lowercase && number && specialChar;
}

    function showPasswordMessage() {
            document.getElementById("password-message").style.display = "block";
        }

        function hidePasswordMessage() {
            document.getElementById("password-message").style.display = "none";
        }
    </script>


        <script>
            document.querySelector("form[action='register.php']").addEventListener("submit", function (event) {
    // Get form elements
    const password = document.getElementById("pws").value;
    const confirmPassword = document.querySelector("input[name='pwer']").value;
    const email = document.querySelector("input[name='Email']").value;
    const dob = new Date(document.querySelector("input[name='dob']").value);
    const errorContainer = document.createElement("div");
    errorContainer.className = "error-message";
    let errors = [];

    // Password validation
    const uppercase = /[A-Z]/.test(password);
    const lowercase = /[a-z]/.test(password);
    const number = /[0-9]/.test(password);
    const specialChar = /[!@#$%^&*]/.test(password);
    const minLength = password.length >= 6;

    if (!minLength || !uppercase || !lowercase || !number || !specialChar) {
        errors.push("Password must be strong and contain at least 6 characters, one uppercase letter, one lowercase letter, one number, and one special character.");
    }

    if (password !== confirmPassword) {
        errors.push("Passwords do not match.");
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        errors.push("Please enter a valid email address.");
    }

    // Age validation
    const today = new Date();
    const age = today.getFullYear() - dob.getFullYear();
    const month = today.getMonth() - dob.getMonth();
    if (month < 0 || (month === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    if (age < 16) {
        errors.push("You must be at least 16 years old.");
    }

    // Show errors if any
    if (errors.length > 0) {
        event.preventDefault(); // Prevent form submission
        errorContainer.innerHTML = errors.join("<br>");
        
        // Remove previous error messages if any
        const previousError = document.querySelector(".error-message");
        if (previousError) {
            previousError.remove();
        }

        // Append new error message
        document.querySelector(".register-right .register-heading").after(errorContainer);
    }
});

    
 
        </script>
</body>

</html>