@extends('layouts.user')
@section('stat','Student')
@section('panel_name','Student Panel')
@section('resource')
<meta name="_token" content="{{ csrf_token() }}" />
<meta name="id" content="{{session('session_id')}}" />
@endsection
@section('script')
<link href='{{asset('fullcalendar/core/main.css')}}' rel='stylesheet' />
<link href='{{asset('fullcalendar/daygrid/main.css')}}' rel='stylesheet' />
<script src='{{asset('fullcalendar/core/main.js')}}'></script>
<script src='{{asset('fullcalendar/daygrid/main.js')}}'></script>
<script src='{{asset('fullcalendar/interaction/main.min.js')}}'></script>
<script src='{{asset('fullcalendar/timergrid/main.min.js')}}'></script>
<script src='{{asset('js/view/s_report.js')}}'></script>
@endsection
@section('content')
<div class="container">
    <div class="ui one column grid">
        <div style="width:50%;margin-left:25%" class="column">
            <h1 style="font-family: 'Itim', cursive;" class="ui center aligned">รายงานข้อมูลการเข้าชั้นเรียน</h1>
            <select style="width:100%" id="s_subject" class="ui dropdown">
                <option value="">วิชา</option>
            </select>
            <div id='calendar'></div>
        </div>
    </div>
</div>
@endsection