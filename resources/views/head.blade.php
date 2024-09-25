<head>
<title>{{$title}}</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
<link rel="icon" type="image/png" href="/template/images/icons/favicon.png"/>
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/fonts/linearicons-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/animate/animate.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/select2/select2.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/slick/slick.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/MagnificPopup/magnific-popup.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/vendor/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="/template/css/util.css">
<link rel="stylesheet" type="text/css" href="/template/css/main.css">
<!--===============================================================================================-->
<meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Màu của tất cả các liên kết trong phân trang */
        .page-link {
            color: #606366; /* Màu chữ của số trang */
            text-decoration: none; /* Bỏ gạch dưới */
        }

        /* Màu của số trang hiện tại */
        .page-item.active .page-link {
            background-color: #606366; /* Màu nền của số trang hiện tại */
            color: #fff; /* Màu chữ của số trang hiện tại */
            border-color: #606366; /* Màu viền của số trang hiện tại */
        }

        /* Màu của các số trang khi di chuột qua */
        .page-link:hover {
            color: #606366; /* Màu chữ khi di chuột qua */
            text-decoration: underline; /* Gạch dưới khi di chuột qua */
        }

        /* Màu của các phần tử phân trang bị vô hiệu hóa */
        .page-item.disabled .page-link {
            color: #6c757d; /* Màu chữ của phần tử bị vô hiệu hóa */
            background-color: #e9ecef; /* Màu nền của phần tử bị vô hiệu hóa */
            border-color: #dee2e6; /* Màu viền của phần tử bị vô hiệu hóa */
        }
        .custom-text {
            font-family: 'Arial', sans-serif; /* Thay đổi phông chữ */
            font-size: 30px; /* Thay đổi kích thước chữ */
            color: #fffffc; /* Thay đổi màu chữ */
        }
    </style>
</head>




