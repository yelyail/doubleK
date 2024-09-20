<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Double-K Computer</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">  
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}"> 
</head>
<body>  
    <a class="navbar-brand" href="#">
        <img src="{{ URL('assets/images/logo.jpg') }}" alt="Logo" width="85" class="pic1">
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
                <h2 class="titletxt">Employee Information</h2>
                <div class="inputs">
                    <div class="input-body">
                        <i class="fas fa-user"></i>
                        <input type="text" name="fullname" placeholder="Juan Dela Cruz" class="input-field" required autofocus>
                    </div>
                    <div class="input-body">
                        <i class="fas fa-users"></i>
                        <select class="select-field" name="jobtype" required> 
                            <option value="" disabled selected hidden>Job Type</option>
                            <option value="1">Admin</option>
                            <option value="0">Helper</option>
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
                    <div class="input-body ">
                            <i class="fas fa-lock"></i>
                            <x-text-input id="password" class="input-field form-control" type="password" name="password" required autocomplete="current-password" placeholder="********"/>
                            <span toggle="#password" class="fa fa-fw fa-eye password-toggle" onclick="passVisib()"></span>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    <button type="submit" name="submit" class="btn btn-danger">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('assets/js/status.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("registrationForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
        
        var form = event.target;
        var formData = new FormData(form);
        
        var password = form.elements["password"].value;
        var contactNum = form.elements["user_contact"].value; 
        
        if (contactNum.length !== 10 || !/^\d{10}$/.test(contactNum)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Contact Number',
                text: 'The contact number must be exactly 10 digits.',
            });
            return; 
        }
        if (password.length < 8) {
            Swal.fire({
                icon: 'error',
                title: 'Password Length',
                text: 'The password must be at least 8 characters long.'
            });
            return; 
        }

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    if (data.errors) {
                        if (data.errors.fullname) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Fullname Already Registered',
                                text: 'The fullname has already been registered. Please use a different fullname.'
                            });
                        } else if (data.errors.username) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Username Already Taken',
                                text: 'The username is already in use. Please use a different username.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Failed',
                                text: 'An error occurred while processing your registration.'
                            });
                        }
                    }
                });
            } else {
                return response.json().then(data => {
                    if (data.status === 'success') {
                        form.reset(); // Reset the form
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful',
                            text: data.message,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                return;
                            }
                        });
                    }
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'An error occurred while processing your registration.'
            });
        });
    });
    </script>
    </body>
    @if(session('alertShow'))
        <script>
            Swal.fire({
                icon:"{{ session('icon') }}",
                title:"{{ session('title') }}",
                text:"{{ session('text') }}",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK",
                allowOutsideClick:false,
                allowEscapeKey:false,
                allowEnterKey:false,
            }).then((result)=>{
                if(result.isConfirmed){
                    return;
                }
            });
        </script>
    @endif
</html>
