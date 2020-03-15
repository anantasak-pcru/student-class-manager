<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>
        @if(Auth::guard('teacher')->check())
        Teacher Panel
        @endif
        @if(Auth::guard('student')->check())
        Teacher Panel
        @endif
    </title>
    <link rel="stylesheet" href="{{asset('css/base/fontausome.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/fomaticui.css')}}">
    <link rel="stylesheet" href="{{asset('css/view/user.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/sweetalert.css')}}">
    @yield('resource')
</head>

<body>

    <div class="ui sidebar vertical menu">
        @if(Auth::guard('teacher')->check())
        <a href="{{url('teacher/')}}" class="item"><span><i class="tasks icon"></i>เช็คชื่อเข้าชั้นเรียน</span></a>
        <a href="{{url('teacher/student/report')}}" class="item"><span><i class="file alternate icon"></i>รายงานข้อมูลนักศึกษา</span></a>
        <a href="{{url('teacher/report')}}" class="item"><span><i
                    class="file alternate icon"></i>รายงานข้อมูลการเข้าชั้นเรียน</span></a>
        <a href="{{url('teacher/report3')}}" class="item"><span><i
                    class="file alternate icon"></i>รายงานข้อมูลการเข้าชั้นเรียน</span></a>
        @endif
        @if(Auth::guard('student')->check())
        <a href="{{url('student')}}" class="item"><span><i
                    class="file alternate icon"></i>รายงานข้อมูลการเข้าชั้นเรียน</span></a>
        @endif
    </div>

    <div class="pusher">
        <div class="ui menu asd borderless marginlefting">
            <a id="menu" class="item">
                <i class="fas fa-bars"></i>
            </a>
            <div class="item">
                <h3>@yield('panel_name')</h3>
            </div>

            <div class="right menu">
                <div class="item">
                    <h3><span style="margin-right:10px" class="ui red label"><i
                                class="tags icon"></i>@yield('stat')</span></h3>
                </div>
                <div style="right:20px" class="ui pointing dropdown item displayblock" tabindex="0">
                    @if(session('session_id')!=null)
                    {{session('session_fname').'  '.session('session_lname')}}
                    @endif
                    <div style="right:20px" class="menu" tabindex="0">
                            @if(Auth::guard('student')->check())
                            <a href="{{url('student/setting')}}" class="item"><i class="fas fa-power-off"></i> Setting</a>
                            @endif
                            @if(Auth::guard('teacher')->check())
                            <a href="{{url('teacher/setting')}}" class="item"><i class="fas fa-power-off"></i> Setting</a>
                            @endif
                        <a href="{{url('logout')}}" class="item"><i class="fas fa-power-off"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
        @yield('content')
    </div>
    <script src='{{asset('js/script/jquery.js')}}'></script>
    <script src='{{asset('js/script/semantic.js')}}'></script>
    <script src="{{ asset('js/script/sweetalert.js') }}"></script>
    <script>
        $(".ui.dropdown").dropdown({
            allowCategorySelection: true,
            transition: "fade up",
            context: 'sidebar',
            on: "click"
            });

            document.getElementById('menu')
            .addEventListener('click', () => {
                $('.ui.sidebar').sidebar('toggle')
            })

            $('.ui.accordion').accordion({
            selector: {

            }
        });
    </script>
    @yield('script')

</body>

</html>