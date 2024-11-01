<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Scentify</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/gallery/title_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/gallery/title_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/gallery/title_logo.png">
    <meta name="msapplication-TileImage" content="assets/img/gallery/title_logo.png">
    <meta name="theme-color" content="#ffffff">



    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="assets/css/theme.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />

</head>

<body>
    <main class="main" id="top">
        <?php include "nav_bar.php" ?>
        <section class='py-11 bg-light-gradient border-bottom border-white border-5'>
        <div class='bg-holder overlay overlay-light'
            style='background-image:url(assets/img/gallery/background_perfume.PNG);background-size:cover;'>
        </div>
            <!--/.bg-holder-->
            <div class="container">
                <div class="row flex-center">
                    <div class="col-12">
                        <div class="d-flex align-items-center flex-column">
                            <h1 class="fw-normal text-center mb-4">Contact us</h1>
                        </div>
                        <div class="card p-3 border border-3 border-primary1 bg-primary1" >
                            <form id="contact_us" method="POST" action="send_email.php">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="full_name" id="full_name" required>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="email_address" class="form-label">Email address</label>
                                        <input type="email" class="form-control" name="email_address"
                                            id="email_address" required>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" name="subject" id="subject" required>
                                    </div>
                                </div>

                                <div class="mb-3 form-floating">
                                    <textarea class="form-control" name="Message"
                                    id="Message" style="min-height:200px" placeholder="" required></textarea>
                                    <label for="Message" class="form-label">Leave a comment here</label>
                                        
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary1">Send</button>
                                </div>
                            </form>
                            <p class="text-success d-none" id="message_sent">message sent successfully</p>
                            <p class="text-danger d-none" id="message_fail">message faild to send</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include "footer.html" ?>
    <?php
        if(isset($_SESSION["mail"])){
            if($_SESSION["mail"]){
                echo "<script>document.getElementById('message_sent').classList.remove('d-none')</script>";
            } else {
                echo "<script>document.getElementById('message_fail').classList.remove('d-none')</script>";
            }
            unset($_SESSION["mail"]);
        }
    ?>
    <script src="assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <!-- <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script> -->
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script>
    feather.replace();
    </script>
</body>

</html>