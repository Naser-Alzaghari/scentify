<?php
    session_start();
    if(!isset($_SESSION["user_id"])){
        header('location:../public/AdminLoginPage.php');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Scentify</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/png" href="/favicon-48x48.png" sizes="48x48" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <style>
    /* CSS for Chart Containers */
    .chart-container {
        width: 100%;
        height: 300px;
        padding: 10px;
        box-sizing: border-box;
    }

    .card {
        height: 100%;
    }

    .stats-card {
        background-color: #2ad766;
        padding: 15px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .stats-card-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .stats-card-text {
        flex: 1;
    }

    .stats-card-title {
        color: #fff;
        font-size: 1.2rem;
        font-weight: bold;
    }

    .stats-card-number {
        color: #fff;
        font-size: 2rem;
        font-weight: bold;
    }

    .navbar-brand svg {
        width: 100px;
        height: auto;
        max-height: 50px;
    }

.nav .nav-link.active {
background-color: #007bff !important; /* لون الخلفية عند التفعيل */
color: #ffffff !important; /* لون النص الأبيض */
}

.nav .nav-link.active .menu-title {
color: #ffffff !important; /* تأكد من أن النص داخل العنصر يكون لونه أبيض أيضًا */
}
    </style>
</head>

<body>
<div class="container-fluid page-body-wrapper">
      <?php include "header.php" ?>
    <!-- Sidebar -->
        <?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'manage-users.php') ? 'active' : ''; ?>" href="manage-users.php">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Manage Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'manage-orders.php') ? 'active' : ''; ?>" href="manage-orders.php">
                <i class="icon-cart menu-icon"></i>
                <span class="menu-title">Orders</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'manage-products.php') ? 'active' : ''; ?>" href="manage-products.php">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Products</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'manage-category.php') ? 'active' : ''; ?>" href="manage-category.php">
                <i class="icon-tag menu-icon"></i>
                <span class="menu-title">Category</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'manage-coupons.php') ? 'active' : ''; ?>" href="manage-coupons.php">
                <i class="icon-tag menu-icon"></i>
                <span class="menu-title">Coupons</span>
            </a>
        </li>
    </ul>
</nav>


        <div class="main-panel">
            <div class="content-wrapper">

                <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="col-md-12 grid-margin mb-3">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="font-weight-bold">Welcome, Mohammed</h3>
                                <h6 class="font-weight-normal mb-0">All systems are running smoothly!</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stats-card text-center col-4">
                        <div class="stats-card-content">
                            <div class="stats-card-text">
                                <h5 class="stats-card-title">Number of Clients</h5>
                                <p class="stats-card-number" id="clients-count">Loading...</p>
                            </div>
                        </div>
                    </div>
                    </div>
                <!-- Number of Clients -->


                <!-- Order Details and Sales Report -->
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-title">Order Details</p>
                                <div class="chart-container">
                                    <canvas id="order-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-title">Sales Report</p>
                                <div class="chart-container">
                                    <canvas id="sales-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Countries and Top Product in a Slider -->
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card position-relative">
                        <div class="card-body">
                            <div id="detailedReports"
                                class="carousel slide detailed-report-carousel position-static pt-2"
                                data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                                <div class="ml-xl-4 mt-3">
                                                    <p class="card-title">User Countries</p>
                                                    <h1 class="text-primary" id="user-country-percentage">75%</h1>
                                                    <h3 class="font-weight-500 mb-xl-4 text-primary">Jordan
                                                    </h3>
                                                    <p class="mb-2 mb-xl-0">The proportion of active users by
                                                        country. Data represents the share of sessions by region
                                                        within the selected date range.</p>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xl-9">
                                                <div class="chart-container">
                                                    <canvas id="user-countries-chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                                <div class="ml-xl-4 mt-3">
                                                    <p class="card-title">Top Product</p>
                                                    <h1 class="text-primary">$24k</h1>
                                                    <h3 class="font-weight-500 mb-xl-4 text-primary">Product Sales
                                                    </h3>
                                                    <p class="mb-2 mb-xl-0">The most successful products by sales in
                                                        the given period. Data shows the top-performing products
                                                        based on revenue.</p>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xl-9">
                                                <div class="chart-container">
                                                    <canvas id="top-product-chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#detailedReports" role="button"
                                    data-slide="prev">
                                    <span class="fas fa-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#detailedReports" role="button"
                                    data-slide="next">
                                    <span class="fas fa-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
             
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript and Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
    // Fetch data using AJAX
    $(document).ready(function() {
        $.ajax({
            url: 'dashboard.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update number of clients dynamically
                $('#clients-count').text(data.clients);

                // Order Details chart
                var ctxOrderDetails = document.getElementById('order-chart').getContext('2d');
                var orderDetailsChart = new Chart(ctxOrderDetails, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                        datasets: [{
                            label: 'Orders',
                            data: data.orderDetails.total_orders,
                            borderColor: '#f64e60',
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Sales Report chart
                var ctxSalesReport = document.getElementById('sales-chart').getContext('2d');
                var salesReportChart = new Chart(ctxSalesReport, {
                    type: 'bar',
                    data: {
                        labels: data.salesReport.map(item => item.month),
                        datasets: [{
                            label: 'Total Sales',
                            data: data.salesReport.map(item => item.total_sales),
                            backgroundColor: '#1f3bb3'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // User Countries chart
                var ctxUserCountries = document.getElementById('user-countries-chart').getContext(
                    '2d');
                var userCountriesChart = new Chart(ctxUserCountries, {
                    type: 'doughnut',
                    data: {
                        labels: data.userCountries.map(item => item.country),
                        datasets: [{
                            data: data.userCountries.map(item => item.total),
                            backgroundColor: ['#d9aaf7', '#1f3bb3', '#00c5dc',
                                '#f64e60', '#f7f700', '#04f702','#fb5708','#8e2a5c','#800000','#c0c0c0','#c4ead2','#fbc457'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });

                // Top Product chart
                var ctxTopProduct = document.getElementById('top-product-chart').getContext('2d');
                var topProductChart = new Chart(ctxTopProduct, {
                    type: 'bar',
                    data: {
                        labels: data.topProducts.map(item => item.product_name),
                        datasets: [{
                            label: 'Sales',
                            data: data.topProducts.map(item => item.total_sold),
                            backgroundColor: ['#1f3bb3', '#00c5dc', '#f64e60', ''],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ', error);
            }
        });
    });
    </script>
</body>

</html>