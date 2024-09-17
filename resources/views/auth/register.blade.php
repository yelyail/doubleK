<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Double-K Computer</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>         
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">  
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}"> 
</head>
<body>  
    <a class="navbar-brand" href="#">
        <img src="{{ URL('assets/images/loh.png') }}" alt="Logo" width="85" class="pic1">
    </a>   
    <div class="card-body-register">
        <div class="row justify-content-center">
            <form id="registrationForm" action="{{ route('register.save') }}" class="form-control" method="POST">
                @csrf
                <div class="Container-date">
                    <div class="row">
                        <div class="rowdate2">
                            <a class="Logo" href="#">
                                <img src="{{ URL('assets/images/logo.jpg') }}" alt="Logo" width="85" class="pic">
                            </a> 
                        </div>
                    </div>
                </div>
                <h2 class="titletxt">Registration Here</h2>
                <div class="inputs">
                    <div class="input-body">
                        <i class="fas fa-user"></i>
                        <input type="text" name="fullname" placeholder="Juan Dela Cruz" class="input-field" required autofocus>
                    </div>
                    <div class="input-body">
                        <i class="fas fa-users"></i>
                        <select class="select-field" name="jobtype" required> 
                            <option value="" disabled selected hidden>Job Type</option>
                            <option value="0">Admin</option>
                            <option value="1">Helper</option>
                        </select>  
                    </div>
                    <div class="input-body">
                        <i class="fas fa-phone"></i>
                        <input type="text" name="user_contact" placeholder="09123654371" class="input-field" required>
                    </div>
                    <div class="input-body">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="admin" class="input-field" required autofocus>
                    </div>
                    <div class="input-body">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password" placeholder="Password" class="input-field" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-danger">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
