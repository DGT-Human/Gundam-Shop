<!DOCTYPE html>
<!-- Coding by CodingLab | www.codinglabweb.com-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $title }}</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/template/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
@include('head')
@include('header')
@include('admin.alert')
<section class="container forms">
    <div class="form login">
        <div class="form-content">
            <header>Signup</header>
            <form action="/users/signup/store" method="post">
                <div class="field input-field">
                    <input type="text" placeholder="name" class="input" name= "name">
                </div>

                <div class="field input-field">
                    <input type="email" placeholder="Email" class="input" name= "email">
                </div>

                <div class="field input-field">
                    <input type="password" placeholder="Create password" class="password" name= "password">
                </div>

                <div class="field input-field">
                    <input type="password" placeholder="Confirm password" class="password" name= "password_confirmation">
                    <i class='bx bx-hide eye-icon'></i>
                </div>

                <div class="field button-field">
                    <button type="submit">Signup</button>
                </div>
                @csrf
            </form>

            <div class="form-link">
                <span>Already have an account? <a href="/users/login" >Login</a></span>
            </div>
        </div>

        <div class="line"></div>

        <div class="media-options">
            <a href="#" class="field facebook">
                <i class='bx bxl-facebook facebook-icon'></i>
                <span>Login with Facebook</span>
            </a>
        </div>

        <div class="media-options">
            <a href="#" class="field google">
                <img src="/template/images/google.png" alt="" class="google-img">
                <span>Login with Google</span>
            </a>
        </div>

    </div>
</section>

<!-- JavaScript -->
<script src="/template/js/script.js"></script>
</body>
</html>
