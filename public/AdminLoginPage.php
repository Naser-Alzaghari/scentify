<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"
        id="bootstrap-css">
    <style>
    .error-message {
        color: red;
        margin-bottom: 15px;
    }

    .valid {
        color: ;
    }

    .invalid {
        color: red;
    }
    </style>
</head>

<body>
    <div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt="" />
                <h3>Welcome</h3>
                <p style="font-size: 18px; color: #333; line-height: 1.6;">Welcome to our exclusive perfume store! Join
                    us today to experience the finest fragrances that reflect your personality and add a touch of
                    elegance to your life.</p>
            </div>
            <div class="col-md-9 register-right mt-5">

            
                <div class="tab-content" id="myTabContent">

                    

                    <!-- LOGIN Tab -->
                    <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h3 class="register-heading">Admin Login</h3>
                        <div class="row register-form">
                            <div class="col-md-6">
                                <form action="LoginAdmin.php" method="post">
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

</body>

</html>