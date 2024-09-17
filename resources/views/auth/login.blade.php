<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Double-K Computer Parts</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}"> 
    <link rel="stylesheet" href="{{ asset(path: 'assets/css/login.css') }}"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <a class="navbar-brand" href="#">
        <img src="{{ URL('assets/images/logo.jpg') }}" alt="Logo" width="85" class="pic1">
    </a>   
    <div class="card-body-login">
        <div class="row justify-content-center">
                <form method="POST" action="{{ route('login.action') }}" class="form-control">
                    @csrf
                    <div class="Container-date">
                        <div class="rowdate2">
                            <a class="Logo" href="#">
                                <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" width="85" class="pic">
                            </a> 
                        </div>
                    </div>

                    <h1 class="titletxt">Double-K Computer Parts</h1><br>

                    <div class="inputs">
                        <div class="input-body">
                            <i class="fa-regular fa-user"></i>
                            <x-text-input id="username" class="input-field" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="Username" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                        
                        <div class="input-body ">
                            <i class="fas fa-lock"></i>
                            <x-text-input id="password" class="input-field form-control" type="password" name="password" required autocomplete="current-password" placeholder="********"/>
                            <span toggle="#password" class="fa fa-fw fa-eye password-toggle" onclick="passVisib()"></span>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <button type="submit" name="submit" class="btn btn-danger">LOGIN</button>
                    </div>
                </form>
        </div>        
    </div>

    <script src="{{ asset('assets/js/status.js') }}"></script>
    @if(session('alertShow'))
        <script>
            swal.fire({
                icon: "{{ session('icon') }}",
                title: "{{ session('title') }}",
                text: "{{ session('text') }}",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('dashboard') }}";
                }
            });
        </script>
    @endif
</body>
</html>
