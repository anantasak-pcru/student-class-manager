<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>
    <link rel="stylesheet" href="{{asset('css/view/welcome.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/fontausome.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/sweetalert.css')}}">
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <script src="{{ asset('js/script/sweetalert.js') }}"></script>
    <script src="{{ asset('js/script/jquery.js') }}"></script>
</head>

<body>
    <div class="container">
        <nav class="nav-bar">
            <div class="nav-logo">
                <h1> <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i> Student class manager</a> </h1>
            </div>
        </nav>
        <div class="box-1">
            <h1>ระบบเช็คชื่อเข้าชั้นเรียน</h1>
            <h1>Student class manager</h1>
        </div>

        <div class="box-2">
            {{ csrf_field() }}
            <h1>Login</h1>
            <div class="from-control">
                <div class="input">
                    <input name="username" type="text" placeholder="uername"><br>
                    <input name="password" type="password" placeholder="password"><br>
                    {{-- <input type="checkbox" tabindex="3" class="" name="remember" id="remember"> --}}
                    <div align="center" id="menu">
                        <div>
                            <a onclick="radio('admin')" id="admin" href="#" class="buttons"><i
                                    class="fas fa-user-shield"></i> Admin</a>
                            <a onclick="radio('teacher')" id="teacher" href="#" class="buttons"><i
                                    class="fas fa-chalkboard-teacher"></i> Teacher</a>
                            <a onclick="radio('student')" id="student" href="#" class="buttons selected"><i
                                    class="fas fa-user-graduate"></i> Student</a>
                        </div>
                    </div>
                </div>
            </div>
            <div align="center">
                <button onclick="LoginUser()" type="submit"> Login </button>
            </div>

        </div>

        <footer>

            <h3>Copyright <span><i class="fas fa-copyright"></i></span> Anantasak Nonkhunthod 2019</h3>
            <div class="line-break"></div>
            <a href="https://github.com/anantasak-pcru/student-class-manager"><i
                    class="fab fa-github-alt lg"></i> Github.com</a>

        </footer>

    </div>
    <script src="{{asset('js/view/welcome.js')}}"></script>
</body>

</html>