@extends('layouts.user')
@section('stat','Teacher')
@section('panel_name','Teacher Panel')
@section('resource')
<meta name="_token" content="{{ csrf_token() }}" />
<meta name="id" content="{{session('session_id')}}" />
<link rel="stylesheet" href="{{asset('css/datatable/dataTables.semanticui.min.css')}}">
<link rel="stylesheet" href="{{asset('css/datatable/buttons.semanticui.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/yearpicker.css')}}">
@endsection
@section('script')
{{-- <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> --}}
<script src="{{asset('js/datatable/jquery.dataTables.min.js')}}"></script>

{{-- <script src="https://cdn.datatables.net/1.10.20/js/dataTables.semanticui.min.js"></script> --}}
<script src="{{asset('js/datatable/dataTables.semanticui.min.js')}}"></script>

{{-- <script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script> --}}
<script src="{{asset('js/datatable/dataTables.buttons.min.js')}}"></script>

{{-- <script src="https://cdn.datatables.net/1.10.20/js/dataTables.semanticui.min.js"></script> --}}
<script src="{{asset('js/datatable/dataTables.semanticui.min.js')}}"></script>

{{-- <script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.semanticui.min.js"></script> --}}
<script src="{{asset('js/datatable/buttons.semanticui.min.js')}}"></script>
{{-- <meta name="id" content="{{session('session_id')}}"/> --}}
<link href='{{asset('fullcalendar/core/main.css')}}' rel='stylesheet' />
<link href='{{asset('fullcalendar/daygrid/main.css')}}' rel='stylesheet' />
<script src='{{asset('fullcalendar/core/main.js')}}'></script>
<script src='{{asset('fullcalendar/daygrid/main.js')}}'></script>
<script src='{{asset('fullcalendar/interaction/main.min.js')}}'></script>
<script src='{{asset('fullcalendar/timergrid/main.min.js')}}'></script>
<script src="{{asset('js/script/yearpicker.js')}}"></script>
<script src="{{asset('js/view/check.js')}}" type="text/javascript"></script>
@endsection
@section('content')
<div class="container">
        <h1 style="font-family: 'Itim', cursive;" class="ui header center aligned">เช็คชื่อเข้าชั้นเรียน</h1>
    <div class="ui two column grid">
        <div class="column">
            <div id='calendar'></div>
        </div>
        <div class="column">
            <div class="table">
                <div class="ui three column grid">
                    <div class="column">
                        <div style="width:100%;" class="ui input">
                            <input readonly id="s_date" type="text" placeholder="วันที่">
                        </div>
                        <select id="s_subject" class="ui dropdown">
                            <option value="">วิชา</option>
                        </select>
                    </div>
                    <div class="column">
                        <div style="width:100%" class="ui input">
                            <input readonly id="year" type="text" placeholder="ปีการศึกษา">
                        </div>
                        <select style="width:100%" id="s_term" class="ui dropdown">
                            <option value="">ภาคเรียน</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="column">
                        <div style="width:100%;">
                                <button style="width:100%" id="clear" style="float:right;margin-top:5px;margin-bottom:10px"
                                class="ui red button"><i class="times icon"></i>ล้างค่า</button>
                        </div>
                        <div style="width:100%;margin-bottom:10px;">
                            <button id="search" style="float:right;margin-top:5px;margin-bottom:10px;width:48%"
                                class="ui teal button"><i class="search icon"></i>ค้นหา</button>
                            <button id="save" style="float:right;margin-top:5px;margin-bottom:10px;width:48%"
                                class="ui teal button"><i class="save icon"></i>Save</button>
                        </div>
                    </div>
                </div>
                <table style="width:100%;" id="inclass" class="ui selectable celled table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อ</th>
                            <th>สกุล</th>
                            <th>สาขา</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
       </div>
    </div>
</div>
@endsection