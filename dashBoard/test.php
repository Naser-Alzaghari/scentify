<?php
// تضمين الملفات الضرورية
include_once 'config.php';
include_once 'Category.php';

// إنشاء كائن فئة
$category = new Category($pdo);

// جلب جميع الفئات غير المحذوفة
$categories = $category->getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Orders - Scentify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Css.css">

    <style>
    .order-status-cancelled {
        background-color: red;
        color: white;
    }

    .order-status-completed {
        background-color: green;
        color: white;
    }

    .order-status-pending {
        background-color: yellow;
        color: black;
    }

    .order-status-processing {
        background-color: orange;
        color: white;
    }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- Navbar -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="index.php">
                    <!-- Add your SVG logo here -->
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        xmlns:svgjs="http://svgjs.dev/svgjs" width="80" viewBox="0 0 2000 1080">
                        <g transform="matrix(1,0,0,1,-1.2121212121212466,0.7733073897825307)"><svg viewBox="0 0 396 214"
                                data-background-color="#ffffff" preserveAspectRatio="xMidYMid meet" height="1080"
                                width="2000" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="tight-bounds" transform="matrix(1,0,0,1,0.2400000000000091,0.858315080377551)">
                                    <svg viewBox="0 0 395.52 212.86300367630665" height="212.86300367630665"
                                        width="395.52">
                                        <g><svg viewBox="0 0 395.52000000000004 212.86300367630668"
                                                height="212.86300367630665" width="395.52">
                                                <g transform="matrix(1,0,0,1,0,103.19045879172415)"><svg
                                                        viewBox="0 0 395.52000000000004 109.6725448845825"
                                                        height="109.6725448845825" width="395.52000000000004">
                                                        <g id="textblocktransform"><svg
                                                                viewBox="0 0 395.52000000000004 109.6725448845825"
                                                                height="109.6725448845825" width="395.52000000000004"
                                                                id="textblock">
                                                                <g><svg viewBox="0 0 395.52000000000004 109.6725448845825"
                                                                        height="109.6725448845825"
                                                                        width="395.52000000000004">
                                                                        <g transform="matrix(1,0,0,1,0,0)"><svg
                                                                                width="395.52000000000004"
                                                                                viewBox="1.8 -37.1 175.42999999999998 48.650000000000006"
                                                                                height="109.6725448845825"
                                                                                data-palette-color="#212121">
                                                                                <path
                                                                                    d="M11.2 0.65Q6.5 0.65 4.15-1 1.8-2.65 1.8-5.35L1.8-5.35Q1.8-6.65 2.48-7.5 3.15-8.35 4.6-8.35L4.6-8.35Q4.85-8.35 5.45-8.25L5.45-8.25Q5.3-7.6 5.3-7L5.3-7Q5.3-6.4 5.35-6.05L5.35-6.05Q5.5-3.9 7.15-2.63 8.8-1.35 11.65-1.35L11.65-1.35Q14.3-1.35 15.93-2.33 17.55-3.3 17.55-5.1L17.55-5.1Q17.55-6.35 16.88-7.23 16.2-8.1 15.18-8.7 14.15-9.3 12.3-10.15L12.3-10.15 10.75-10.85Q8-12.15 6.5-13.08 5-14 3.9-15.6 2.8-17.2 2.8-19.55L2.8-19.55Q2.8-22.9 5.38-24.55 7.95-26.2 11.95-26.2L11.95-26.2Q16.1-26.2 18.45-24.9 20.8-23.6 20.8-21.2L20.8-21.2Q20.8-20.2 20.28-19.53 19.75-18.85 18.65-18.75L18.65-18.75Q18.4-18.7 17.95-18.7L17.95-18.7Q17.45-18.7 17.15-18.8L17.15-18.8Q17.2-19.05 17.2-19.7L17.2-19.7Q17.2-20.5 17-21.05L17-21.05Q16.8-22.6 15.43-23.43 14.05-24.25 11.8-24.25L11.8-24.25Q9.7-24.25 8.33-23.45 6.95-22.65 6.95-20.95L6.95-20.95Q6.95-19.5 7.78-18.45 8.6-17.4 9.75-16.73 10.9-16.05 13.3-14.9L13.3-14.9 14.25-14.45Q16.75-13.25 18.23-12.35 19.7-11.45 20.75-10.05 21.8-8.65 21.8-6.65L21.8-6.65Q21.8-3.65 19.28-1.5 16.75 0.65 11.2 0.65L11.2 0.65ZM37.4 0.65Q31.8 0.65 28.7-2.78 25.6-6.2 25.6-12.65L25.6-12.65Q25.6-18.95 28.77-22.55 31.95-26.15 37.75-26.15L37.75-26.15Q41.85-26.15 44.12-24.38 46.4-22.6 46.4-19.8L46.4-19.8Q46.4-18 45.4-16.9 44.4-15.8 42.8-15.8L42.8-15.8Q41.1-15.8 40.25-16.85L40.25-16.85Q41.85-17.6 41.85-20.15L41.85-20.15Q41.85-22.25 40.6-23.25 39.35-24.25 37.3-24.25L37.3-24.25Q34.2-24.25 32.32-21.43 30.45-18.6 30.45-13.35L30.45-13.35Q30.45-7.65 32.67-5 34.9-2.35 38.65-2.35L38.65-2.35Q41.4-2.35 43.35-3.7 45.3-5.05 45.9-7.5L45.9-7.5Q46.65-7.15 46.65-6.1L46.65-6.1Q46.65-5.55 46.45-4.8L46.45-4.8Q45.65-2.2 43.25-0.78 40.85 0.65 37.4 0.65L37.4 0.65ZM71.44-6.6Q72.19-6.3 72.19-5.45L72.19-5.45Q72.19-4.9 71.84-4.05L71.84-4.05Q70.94-1.75 68.55-0.55 66.14 0.65 62.84 0.65L62.84 0.65Q57.3 0.65 54.12-2.8 50.95-6.25 50.95-12.65L50.95-12.65Q50.95-18.95 54.09-22.55 57.24-26.15 63.05-26.15L63.05-26.15Q67.25-26.15 69.82-24.25 72.39-22.35 72.39-18.85L72.39-18.85Q72.39-15.15 69.39-13.2 66.39-11.25 61.14-11.25L61.14-11.25Q58.49-11.25 55.84-11.55L55.84-11.55Q56.45-2.35 63.99-2.35L63.99-2.35Q66.69-2.35 68.67-3.48 70.64-4.6 71.44-6.6L71.44-6.6ZM62.84-24.25Q59.59-24.25 57.72-21.45 55.84-18.65 55.8-13.4L55.8-13.4Q56.84-13.3 59.14-13.2L59.14-13.2Q62.95-13.2 65.25-14.58 67.55-15.95 67.55-19.5L67.55-19.5Q67.55-22.1 66.19-23.18 64.84-24.25 62.84-24.25L62.84-24.25ZM99.64-6Q99.74-2.15 100.39-0.45L100.39-0.45Q99.24 0.6 97.79 0.6L97.79 0.6Q96.64 0.6 95.89-0.08 95.14-0.75 95.14-1.9L95.14-1.9 95.14-15.2Q95.09-19.5 93.99-21.55 92.89-23.6 90.24-23.6L90.24-23.6Q88.54-23.6 87.02-22.38 85.49-21.15 84.52-18.55 83.54-15.95 83.54-12L83.54-12Q83.54-5.75 83.64-3.8 83.74-1.85 84.29-0.4L84.29-0.4Q83.89 0 83.22 0.3 82.54 0.6 81.69 0.6L81.69 0.6Q80.49 0.6 79.77-0.13 79.04-0.85 79.04-2.1L79.04-2.1 79.04-20.05Q78.99-21.45 78.67-22.03 78.34-22.6 77.64-22.6L77.64-22.6Q76.69-22.6 76.34-21.9L76.34-21.9Q75.74-22.75 75.74-23.55L75.74-23.55Q75.74-24.7 76.59-25.4 77.44-26.1 78.94-26.1L78.94-26.1Q80.94-26.1 82.02-24.73 83.09-23.35 83.09-20.7L83.09-20.7 83.09-20.1Q84.14-23 86.34-24.58 88.54-26.15 91.59-26.15L91.59-26.15Q99.64-26.15 99.64-15.8L99.64-15.8 99.64-6ZM114.19-25.45Q115.04-25.45 116.49-25.5 117.94-25.55 118.79-25.6L118.79-25.6 118.74-24.4Q118.69-23.8 118.34-23.48 117.99-23.15 117.49-23.15L117.49-23.15 112.54-23.15 112.54-21.35 112.49-5.65Q112.49-2.95 114.89-2.95L114.89-2.95Q115.59-2.95 116.27-3.2 116.94-3.45 117.14-3.9L117.14-3.9Q117.74-3.1 117.74-2.2L117.74-2.2Q117.74-0.95 116.49-0.2 115.24 0.55 113.24 0.55L113.24 0.55Q110.79 0.55 109.39-1 107.99-2.55 107.99-5.3L107.99-5.3 107.99-23.15 107.24-23.15Q105.24-23.15 104.24-23L104.24-23 104.29-24.25Q104.34-24.85 104.69-25.15 105.04-25.45 105.59-25.45L105.59-25.45 107.99-25.45 107.99-29.65Q107.99-30.8 108.72-31.45 109.44-32.1 110.64-32.1L110.64-32.1Q112.04-32.1 113.19-31.1L113.19-31.1Q112.64-29.2 112.59-25.45L112.59-25.45 114.19-25.45ZM126.94-29.75Q125.69-29.75 124.84-30.6 123.99-31.45 123.99-32.65L123.99-32.65Q123.99-33.9 124.84-34.75 125.69-35.6 126.94-35.6L126.94-35.6Q128.14-35.6 128.99-34.75 129.84-33.9 129.84-32.65L129.84-32.65Q129.84-31.45 128.99-30.6 128.14-29.75 126.94-29.75L126.94-29.75ZM129.39-6Q129.39-2.3 130.09-0.45L130.09-0.45Q129.04 0.6 127.49 0.6L127.49 0.6Q126.39 0.6 125.66-0.08 124.94-0.75 124.94-1.9L124.94-1.9 124.89-19.55Q124.89-23.3 124.19-25.15L124.19-25.15Q125.49-26.15 126.79-26.15L126.79-26.15Q127.94-26.15 128.64-25.5 129.34-24.85 129.34-23.7L129.34-23.7 129.39-6ZM147.04-37.1Q150.19-37.1 152.04-35.95 153.89-34.8 153.89-32.75L153.89-32.75Q153.89-31.3 152.96-30.4 152.04-29.5 150.79-29.5L150.79-29.5Q149.44-29.5 148.94-30.15L148.94-30.15Q149.84-31.35 149.84-32.75L149.84-32.75Q149.84-35.3 146.94-35.3L146.94-35.3Q144.39-35.3 143.66-33.1 142.94-30.9 142.94-26.8L142.94-26.8 142.94-25.45 145.59-25.45Q146.39-25.45 147.76-25.5 149.14-25.55 149.94-25.6L149.94-25.6 149.89-24.4Q149.84-23.8 149.51-23.48 149.19-23.15 148.69-23.15L148.69-23.15 142.94-23.15 142.94-6Q142.94-2.3 143.64-0.45L143.64-0.45Q142.49 0.6 141.04 0.6L141.04 0.6Q139.89 0.6 139.16-0.08 138.44-0.75 138.44-1.9L138.44-1.9 138.44-23.15 137.74-23.15Q135.69-23.15 134.74-23L134.74-23 134.79-24.25Q134.84-24.85 135.19-25.15 135.54-25.45 136.04-25.45L136.04-25.45 138.44-25.45 138.44-27.6Q138.44-37.1 147.04-37.1L147.04-37.1ZM177.18-23.45L177.23-2.5Q177.23 11.55 165.23 11.55L165.23 11.55Q161.48 11.55 158.98 10.28 156.48 9 156.48 6.2L156.48 6.2Q156.48 4.75 157.41 3.78 158.33 2.8 159.83 2.8L159.83 2.8Q161.33 2.8 161.93 3.65L161.93 3.65Q161.28 4.1 160.98 4.78 160.68 5.45 160.68 6.05L160.68 6.05Q160.68 7.9 162.01 8.85 163.33 9.8 165.23 9.8L165.23 9.8Q172.98 9.8 172.98-2.25L172.98-2.25Q172.98-3.25 173.18-5.95L173.18-5.95Q172.13-2.9 169.96-1.25 167.78 0.4 164.58 0.4L164.58 0.4Q160.73 0.4 158.86-2 156.98-4.4 156.98-10L156.98-10 156.98-19.55Q156.98-23.05 156.28-25.1L156.28-25.1Q157.53-26.15 158.88-26.15L158.88-26.15Q160.03-26.15 160.76-25.48 161.48-24.8 161.48-23.65L161.48-23.65 161.48-10.6Q161.48-6.1 162.48-4.05 163.48-2 165.88-2L165.88-2Q168.83-2 170.78-4.93 172.73-7.85 172.73-13.8L172.73-13.8Q172.73-19.9 172.63-21.8 172.53-23.7 171.98-25.15L171.98-25.15Q173.13-26.15 174.58-26.15L174.58-26.15Q175.68-26.15 176.43-25.43 177.18-24.7 177.18-23.45L177.18-23.45Z"
                                                                                    opacity="1"
                                                                                    transform="matrix(1,0,0,1,0,0)"
                                                                                    fill="#212121"
                                                                                    class="wordmark-text-0"
                                                                                    data-fill-palette-color="primary"
                                                                                    id="text-0"></path>
                                                                            </svg></g>
                                                                    </svg></g>
                                                            </svg></g>
                                                    </svg></g>
                                                <g transform="matrix(1,0,0,1,144.10203748537228,0)"><svg
                                                        viewBox="0 0 107.31592502925547 77.29940308555146"
                                                        height="77.29940308555146" width="107.31592502925547">
                                                        <g><svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                                                x="8" y="0"
                                                                viewBox="21.731 26.068057897173418 56.543 47.863942102826584"
                                                                enable-background="new 0 0 100 100" xml:space="preserve"
                                                                height="77.29940308555146" width="91.31592502925547"
                                                                class="icon-icon-0" data-fill-palette-color="accent"
                                                                id="icon-0">
                                                                <path
                                                                    d="M70.252 51.597A26.04 26.04 0 0 0 75.081 37.495 1.64 1.64 0 0 0 74.6 36.263 1.6 1.6 0 0 0 73.367 35.783C70.748 35.892 68.197 36.391 65.779 37.236A26.2 26.2 0 0 0 64.216 29.665 1.647 1.647 0 0 0 61.978 28.735 25.9 25.9 0 0 0 55.555 32.968 26.2 26.2 0 0 0 51.226 26.598 1.54 1.54 0 0 0 50.668 26.216 1.645 1.645 0 0 0 48.777 26.598 26.2 26.2 0 0 0 44.449 32.966 25.9 25.9 0 0 0 38.028 28.735 1.647 1.647 0 0 0 35.79 29.664 26.2 26.2 0 0 0 34.224 37.236 26.3 26.3 0 0 0 26.636 35.783 1.65 1.65 0 0 0 24.921 37.495 26.05 26.05 0 0 0 29.751 51.597C24.563 54.002 21.731 57.24 21.731 60.846 21.731 62.812 22.575 64.678 24.239 66.393A1.66 1.66 0 0 0 25.852 66.835C29.062 65.964 31.567 66.189 32.862 67.483 33.55 68.171 33.938 69.211 34.014 70.573A1.645 1.645 0 0 0 35.222 72.069C39.649 73.288 44.761 73.932 50.003 73.932 65.856 73.932 78.274 68.183 78.274 60.845 78.271 57.238 75.44 54.002 70.252 51.597M66.443 51.143C66.42 51.166 66.39 51.181 66.368 51.207L66.063 51.557C65.763 51.895 65.466 52.234 65.145 52.555 64.686 53.014 64.205 53.445 63.713 53.86 63.564 53.986 63.41 54.104 63.258 54.225A23 23 0 0 1 60.369 56.195C60.246 56.266 60.126 56.342 60.002 56.411 59.442 56.719 58.871 57.001 58.288 57.26 58.16 57.317 58.028 57.364 57.899 57.419 57.437 57.613 56.969 57.794 56.495 57.958 56.304 58.024 56.11 58.086 55.917 58.146 55.476 58.286 55.028 58.411 54.577 58.523 54.394 58.569 54.213 58.619 54.028 58.66 53.942 58.68 53.854 58.689 53.769 58.708 53.79 58.677 53.807 58.644 53.828 58.613L53.885 58.526 53.891 58.52 53.906 58.498 53.926 58.471 53.952 58.43 53.997 58.363 54.005 58.351C54.01 58.344 54.012 58.336 54.016 58.33 54.237 57.987 54.449 57.641 54.652 57.288L54.662 57.272C54.804 57.026 54.939 56.777 55.073 56.526L55.08 56.518 55.084 56.511 55.151 56.387H55.15L55.156 56.378 55.162 56.36C55.477 55.758 55.769 55.146 56.036 54.523L56.041 54.516 56.043 54.511 56.074 54.439 56.079 54.426 56.124 54.322C56.127 54.316 56.126 54.309 56.128 54.303 56.394 53.667 56.636 53.023 56.85 52.368L56.915 52.171 56.912 52.17A26 26 0 0 0 57.51 49.962H57.511L57.512 49.954C57.681 49.196 57.818 48.431 57.918 47.657L57.92 47.64C57.989 47.106 58.04 46.569 58.077 46.029 58.078 46.003 58.083 45.978 58.084 45.951 58.153 45.874 58.22 45.796 58.294 45.722 58.761 45.255 59.248 44.81 59.754 44.387 59.784 44.362 59.817 44.34 59.848 44.314A22.8 22.8 0 0 1 64.844 41.176C64.854 41.172 64.861 41.164 64.87 41.159A22.9 22.9 0 0 1 71.639 39.226 22.7 22.7 0 0 1 66.443 51.143M54.76 45.969A23 23 0 0 1 54.541 47.859C54.514 48.025 54.483 48.189 54.453 48.354 54.37 48.81 54.276 49.264 54.166 49.713 54.121 49.896 54.069 50.076 54.02 50.257 53.904 50.686 53.78 51.112 53.638 51.533 53.572 51.727 53.497 51.92 53.426 52.112A23 23 0 0 1 52.954 53.314C52.869 53.51 52.772 53.703 52.682 53.897 52.502 54.282 52.321 54.667 52.12 55.042 52.018 55.231 51.903 55.414 51.796 55.601 51.583 55.972 51.372 56.343 51.138 56.703 51.02 56.885 50.887 57.057 50.764 57.234 50.521 57.585 50.282 57.937 50.018 58.276L50.008 58.288C49.972 58.241 49.931 58.198 49.895 58.151 49.752 57.964 49.624 57.771 49.487 57.581A23 23 0 0 1 48.764 56.535C48.654 56.362 48.554 56.182 48.449 56.007 48.217 55.621 47.989 55.234 47.78 54.835 47.693 54.669 47.615 54.499 47.532 54.331A24 24 0 0 1 46.951 53.072C46.879 52.9 46.815 52.725 46.747 52.551A22 22 0 0 1 46.282 51.261C46.22 51.072 46.166 50.879 46.11 50.687 45.986 50.267 45.872 49.843 45.772 49.415 45.722 49.199 45.678 48.982 45.634 48.765A23 23 0 0 1 45.419 47.532 24 24 0 0 1 45.225 45.602C45.22 45.507 45.218 45.411 45.213 45.315L45.214 45.3C45.214 45.287 45.211 45.275 45.21 45.261 45.196 44.936 45.169 44.613 45.169 44.286 45.169 43.63 45.199 42.977 45.255 42.328 45.273 42.126 45.308 41.928 45.331 41.727 45.382 41.278 45.433 40.83 45.51 40.386 45.547 40.173 45.601 39.964 45.644 39.753 45.732 39.321 45.821 38.889 45.935 38.462 45.987 38.266 46.053 38.073 46.11 37.879 46.239 37.441 46.373 37.005 46.528 36.574A22.8 22.8 0 0 1 49.999 30.276 22.73 22.73 0 0 1 54.829 44.286C54.83 44.851 54.801 45.411 54.76 45.969M56.873 36.247A22.7 22.7 0 0 1 61.694 32.581C62.241 34.554 62.518 36.578 62.525 38.626A26 26 0 0 0 58.076 41.507C58.052 41.527 58.025 41.545 58.001 41.564 57.989 41.455 57.971 41.347 57.958 41.238A25 25 0 0 0 57.846 40.376C57.824 40.229 57.798 40.082 57.773 39.934A28 28 0 0 0 57.527 38.666 26 26 0 0 0 57.308 37.752C57.28 37.641 57.25 37.529 57.22 37.417A27 27 0 0 0 56.921 36.406C56.903 36.354 56.891 36.3 56.873 36.247M38.307 32.581A22.8 22.8 0 0 1 43.129 36.248 26 26 0 0 0 42.003 41.567C41.981 41.548 41.957 41.532 41.934 41.514A26 26 0 0 0 37.475 38.625C37.48 36.576 37.76 34.553 38.307 32.581M35.131 41.161C35.14 41.165 35.148 41.173 35.157 41.178A22.7 22.7 0 0 1 40.139 44.305C40.175 44.334 40.213 44.36 40.249 44.39 40.755 44.813 41.242 45.258 41.708 45.724 41.782 45.799 41.85 45.877 41.918 45.955A25.95 25.95 0 0 0 46.233 58.709C46.151 58.691 46.067 58.682 45.985 58.663 45.788 58.619 45.594 58.567 45.399 58.517A22 22 0 0 1 42.12 57.427C41.983 57.369 41.842 57.32 41.706 57.259A23 23 0 0 1 40 56.414C39.871 56.343 39.747 56.263 39.619 56.189A22 22 0 0 1 38.356 55.41 23 23 0 0 1 36.739 54.224C36.588 54.103 36.434 53.985 36.286 53.86A23 23 0 0 1 34.852 52.554C34.531 52.234 34.234 51.895 33.935 51.558 33.832 51.44 33.726 51.321 33.627 51.204 33.606 51.18 33.577 51.165 33.555 51.142A22.7 22.7 0 0 1 28.361 39.226C30.726 39.518 33.002 40.182 35.131 41.161M50 70.638C45.47 70.638 41.059 70.133 37.166 69.173 36.885 67.535 36.223 66.188 35.189 65.153 33.176 63.14 30.004 62.53 26.002 63.407 25.352 62.553 25.023 61.695 25.023 60.845 25.023 58.512 27.575 56.083 31.908 54.238 32.111 54.455 32.313 54.673 32.524 54.884A25.98 25.98 0 0 0 49.997 62.493H50.02L50.029 62.492 50.065 62.491A26 26 0 0 0 67.476 54.884C67.687 54.673 67.889 54.455 68.092 54.238 72.426 56.084 74.977 58.512 74.977 60.845 74.977 65.478 64.719 70.638 50 70.638"
                                                                    fill="#212121" data-fill-palette-color="accent">
                                                                </path>
                                                            </svg></g>
                                                    </svg></g>
                                                <g>
                                                    <rect width="144.10203748537225" height="9.890383279757971"
                                                        y="66.40901980579349" x="251.41796251462773" fill="#212121"
                                                        data-fill-palette-color="accent"></rect>
                                                    <rect width="144.10203748537225" height="9.890383279757971"
                                                        y="66.40901980579349" x="2.842170943040401e-14" fill="#212121"
                                                        data-fill-palette-color="accent"></rect>
                                                    <rect width="144.10203748537225" height="9.890383279757971"
                                                        y="46.628253246277545" x="251.41796251462773" fill="#212121"
                                                        data-fill-palette-color="accent"></rect>
                                                    <rect width="144.10203748537225" height="9.890383279757971"
                                                        y="46.628253246277545" x="2.842170943040401e-14" fill="#212121"
                                                        data-fill-palette-color="accent"></rect>
                                                </g>
                                            </svg></g>
                                        <defs></defs>
                                    </svg>
                                    <rect width="395.52" height="212.86300367630665" fill="none" stroke="none"
                                        visibility="hidden"></rect>
                                </g>
                            </svg></g>
                    </svg>
                    <!-- SVG content here -->
                </a>
                <a class="navbar-brand brand-logo-mini" href="index.php">
                    <img src="images/logo-mini.svg" alt="logo" />
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                            data-toggle="dropdown">
                            <i class="fas fa-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="ti-info-alt mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">Just now</p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="ti-settings mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">Private message</p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="ti-user mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">2 days ago</p>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="Qadome.JPG" alt="profile" class="profile-image" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a class="dropdown-item">
                                <i class="ti-settings text-primary"></i> Settings
                            </a>
                            <a class="dropdown-item">
                                <i class="ti-power-off text-primary"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
    </div>
    <div class="container-fluid page-body-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-users.php">
                        <i class="icon-head menu-icon"></i>
                        <span class="menu-title">Manage Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-orders.php">
                        <i class="icon-cart menu-icon"></i>
                        <span class="menu-title">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-products.php">
                        <i class="icon-box menu-icon"></i>
                        <span class="menu-title">Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-category.php">
                        <i class="icon-tag menu-icon"></i>
                        <span class="menu-title">Category</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-coupons.php">
                        <i class="icon-tag menu-icon"></i>
                        <span class="menu-title">Coupons</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">
                <h2 class="mt-4">Manage Orders</h2>
                <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#categoryModal"
                        onclick="clearForm()">Add New Category</button>
                    <div class="table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $num = 1;
                        foreach ($categories as $cat) {
                            $id = $cat['category_id'];
                            $name = htmlspecialchars($cat['category_name']);
                            $created_at = htmlspecialchars($cat['created_at']);
                            $updated_at = htmlspecialchars($cat['updated_at']);
                            $image = htmlspecialchars($cat['image']);

                            echo "<tr>
                            <td>{$num}</td>
                            <td>{$name}</td>
                            <td>{$created_at}</td>
                            <td>{$updated_at}</td>
                            <td><img src='{$image}' alt='Category Image' class='img-thumbnail'></td>
                             <td>
                            <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#categoryModal' onclick=\"editCategory({$id}, '{$name}', '{$image}')\">Edit</button>
                            <button class='btn btn-danger btn-sm' onclick='confirmDelete({$id})'>Delete</button>
                             </td>
                             </tr>";
                            $num++;
                        }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>

            <!-- View Order Products Modal -->
            <div class="modal fade" id="viewProductsModal" tabindex="-1" role="dialog"
                aria-labelledby="viewProductsModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewProductsModalLabel">Order Products</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="orderProductsContainer"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024.
                        Scentify. All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made
                        with 🤍<i class="ti-heart text-danger ml-1"></i></span>
                </div>
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a
                            href="https://www.scentify.com/" target="_blank">Scentify</a></span>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // Function to view products in an order
    function viewOrderProducts(orderId) {
        $.ajax({
            url: 'fetch_order_products.php',
            method: 'POST',
            data: {
                order_id: orderId
            },
            success: function(response) {
                $('#orderProductsContainer').html(response);
                $('#viewProductsModal').modal('show');
            }
        });
    }

    // Function to cancel an order with confirmation
    function cancelOrder(orderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'cancel_order.php',
                    method: 'POST',
                    data: {
                        order_id: orderId
                    },
                    success: function(response) {
                        if (response == 'success') {
                            Swal.fire(
                                'Cancelled!',
                                'The order has been cancelled.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to cancel the order.',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    }
    </script>
</body>

</html>