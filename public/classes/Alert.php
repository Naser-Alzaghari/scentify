<?php
    class Alert {
        public function showAlert() {
            if (isset($_SESSION['added_item'])) {
                echo "<div class='alert alert-success alert-position' role='alert' id='bottom-alert'>
                    {$_SESSION['added_item']} has been added to cart!
                </div>";
                echo "<script>// Show the alert when the page loads
                        window.addEventListener('load', function() {
                        // Get the alert element
                        const alert = document.getElementById('bottom-alert');
    
                        // Show the alert
                        alert.style.display = 'block';
    
                        // Hide the alert after 4 seconds
                        setTimeout(function() {
                            alert.style.display = 'none';
                        }, 4000);  // 4000 milliseconds = 4 seconds
                        });</script>";
                unset($_SESSION['added_item']);
            }
        }
    }
?>