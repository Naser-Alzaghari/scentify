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
    include('NavAdmin.php');
    ?>
    
    
        <div class="form-container d-flex flex-column justify-content-center">
            <div class="container register">
                <div class="row">
                <div class="col-md-12 register-right">
                        <div class="tab-content" id="myTabContent">
                            <!-- LOGIN Tab -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
    
    </script>
</body>

</html>