<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="{{asset('css/view/home.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/fontausome.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/sweetalert.css')}}">
    <script src="{{ asset('js/script/sweetalert.js') }}"></script>
    @yield('resource')
</head>

<body>
    <div class="container">
        <div class="ui sidebar vertical left menu overlay visible very thin icon"
            style="-webkit-transition-duration: 0.1s; overflow: visible !important;">
            <div id="test_logo" class="item logo">
                <div style="height:300px;display:none;">
                    <img id="1" src="{{asset('image/coding.png')}}" height="300px" width="100%">
                </div>
                <div style="height:48px;">
                    <img id="1" src="{{asset('image/coding.png')}}" height="48px" width="100%">
                </div>
            </div>
            <div class="ui accordion displaynone">
                <a id="title" class="title item">นักศึกษา<i class="dropdown icon"></i></a>
                <div class="content">
                    <a class="item" href="{{url('/admin/student')}}">จัดการข้อมูล</a>
                    <a class="item" href="{{url('/admin/student/report')}}">รายงานข้อมูล</a>
                </div>
            </div>
            <div class="ui accordion displaynone">
                <a id="title" class="title item">อาจารย์<i class="dropdown icon"></i></a>
                <div class="content">
                    <a class="item" href="{{url('/admin/teacher')}}">จัดการข้อมูล</a>
                    <a class="item" href="{{url('/admin/teacher/report')}}">รายงานข้อมูล</a>
                </div>
            </div>
            <div class="ui accordion displaynone">
                <a id="title" class="title item">ผู้ดูเเลระบบ<i class="dropdown icon"></i></a>
                <div class="content">
                    <a class="item" href="{{url('/admin/admin')}}">จัดการข้อมูล</a>
                    <a class="item" href="{{url('/admin/admin/report')}}">รายงานข้อมูล</a>
                </div>
            </div>
            <div class="ui accordion displaynone">
                <a id="title" class="title item">ลงทะเบียนรายวิชา<i class="dropdown icon"></i></a>
                <div class="content">
                    <a class="item" href="{{url('/admin/course_regist')}}">จัดการข้อมูล</a>
                    <a class="item" href="{{url('/admin/course_regist/report')}}">รายงานข้อมูล</a>
                </div>
            </div>
            <div class="ui accordion displaynone">
                <a id="title" class="title item">คณะ / สาขา / วิชา / ตำเเหน่ง<i class="dropdown icon"></i></a>
                <div style="overflow:auto;height:300px" class="content">
                    <div style="text-align: center;font-size:1.3em;background-color:#eeeeee" class="header">
                        <i class="fas fa-edit"></i> จัดการข้อมูล
                    </div>
                    <a style="font-size:1.1em" class="item" href="{{url('/admin/faculty')}}">คณะ</a>
                    <a style="font-size:1.1em" class="item" href="{{url('/admin/major')}}">สาขา</a>
                    <a style="font-size:1.1em" class="item" href="{{url('/admin/subject')}}">วิชา</a>
                    <a style="font-size:1.1em" class="item" href="{{url('/admin/position')}}">ตำเเหน่ง</a>
                    <div style="text-align: center;font-size:1.3em;background-color:#eeeeee" class="header"><i
                            class="fas fa-print"></i> รายงานข้อมูล</div>
                    <a style="font-size:1.1em" class="item" href="{{url('/admin/faculty/report')}}">คณะ</a>
                    <a style="font-size:1.1em" class="item" href="{{url('/admin/major/report')}}">สาขา</a>
                    <a style="font-size:1.1em" class="item" href="{{url('/admin/subject/report')}}">วิชา</a>
                    <a style="font-size:1.1em" class="item" href="{{url('/admin/position/report')}}">ตำเเหน่ง</a>
                </div>
            </div>
            <div class="ui left pointing dropdown item displaynone displayblock" tabindex="0">
                <i class="fas fa-users-cog"></i>

                <div class="menu" tabindex="-1">
                    <div class="header">นักศึกษา</div>
                    <div class="ui divider"></div>
                    <a class="item" href="{{url('/admin/student')}}">จัดการข้อมูลนักศึกษา</a>
                    <a class="item" href="{{url('/admin/student/report')}}">รายงานข้อมูลนักศึกษา</a>
                    <div class="ui divider"></div>

                    <div class="header">อาจารย์</div>
                    <div class="ui divider"></div>
                    <a class="item" href="{{url('/admin/teacher')}}">จัดการข้อมูลอาจารย์</a>
                    <a class="item" href="{{url('/admin/teacher/report')}}">รายงานข้อมูลอาจารย์</a>

                    <div class="header">ผู้ดูเเลระบบ</div>
                    <div class="ui divider"></div>
                    <a class="item" href="{{url('/admin/admin')}}">จัดการข้อมูลผู้ดูเเลระบบ</a>
                    <a class="item" href="{{url('/admin/admin/report')}}">รายงานข้อมูลผู้ดูเเลระบบ</a>

                    <div class="header">ลงทะเบียนรายวิชา</div>
                    <div class="ui divider"></div>
                    <a class="item" href="{{url('/admin/course_regist')}}">จัดการข้อมูลการลงทะเบียนรายวิชา</a>
                    <a class="item" href="{{url('/admin/course_regist/report')}}">รายงานข้อมูลการลงทะเบียนรายวิชา</a>

                </div>
            </div>
            <div class="ui left pointing dropdown item displaynone displayblock" tabindex="0">
                <i class="fas fa-print"></i>

                <div class="menu" tabindex="-1">
                    <div style="font-size:1em" class="header">รายงาน</div>
                    <div class="ui divider"></div>
                    <a class="item" href="{{url('/admin/admin/report')}}">ผู้ดูเเลระบบ</a>
                    <a class="item" href="{{url('/admin/student/report')}}">นักศึกษา</a>
                    <a class="item" href="{{url('/admin/teacher/report')}}">อาจารย์</a>
                    <a class="item" href="{{url('/admin/faculty/report')}}">คณะ</a>
                    <a class="item" href="{{url('/admin/major/report')}}">สาขา</a>
                    <a class="item" href="{{url('/admin/subject/report')}}">วิชา</a>
                    <a class="item" href="{{url('/admin/position/report')}}">ตำเเหน่ง</a>
                    <a class="item" href="{{url('/admin/course_regist/report')}}">ลงทะเบียนรายวิชา</a>
                </div>
            </div>
        </div>
        <div class="pusher">
            <div id="mymenu" class="ui menu asd borderless marginlefting"
                style="border-radius: 0!important; border: 0; margin-left: 260px; -webkit-transition-duration: 0.1s;">
                <a class="item openbtn">
                    <i class="fas fa-bars"></i>
                </a>
                <a class="item">Admin Panel </a>
                <div class="right menu">
                    <div class="item">
                        <h3><span style="margin-right:10px" class="ui red label"><i class="tags icon"></i>Admin</span>
                        </h3>
                    </div>
                    <div style="right:20px" class="ui pointing dropdown item displayblock" tabindex="0">
                        @if(session('session_id')!=null)
                        <h3><span style="font-size:0.9em">{{session('session_fname').'  '.session('session_fname')}}</span></h3>
                        @endif
                        <div style="right:20px" class="menu" tabindex="0">
                            <a href="{{url('admin/setting')}}" class="item"><i class="fas fa-power-off"></i> Setting</a>
                            <a href="{{url('logout')}}" class="item"><i class="fas fa-power-off"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="test">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src='{{asset('js/script/jquery.js')}}'></script>
    <script src="{{asset('js/script/yearpicker.js')}}"></script>
    <script src='{{asset('js/script/semantic.js')}}'></script>
    <script src="{{asset('js/view/home.js')}}" type="text/javascript"></script>
    @yield('script')

</body>

</html>