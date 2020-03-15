@extends('layouts.admin')
@section('resource')

<meta name="_token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{asset('css/base/fomaticui.css')}}">
<link rel="stylesheet" href="{{asset('css/datatable/dataTables.semanticui.min.css')}}">}
<link rel="stylesheet" href="{{asset('css/datatable/buttons.semanticui.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/alerttify_semantic.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/alertify.min.css')}}">

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

<script src="{{asset('js/script/alertifyjs.js')}}"></script>


<script src="{{asset('js/script/pdfmake.min.js')}}"></script>
<script src="{{asset('js/script/vfs_fonts.js')}}"></script>
<script src="{{asset('js/script/buttons.html5.min.js')}}"></script>
<script src="{{asset('js/script/buttons.print.min.js')}}"></script>

<script src="{{asset('js/view/student_report.js')}}"></script>

@endsection
@section('content')
<div align="center">
    <div style="width:80%;margin-top: 10px;margin-bottom:100px">
        <h1 style="font-family: 'Itim', cursive;" class="ui center aligned">รายงานข้อมูลนักศึกษา</h1>
        <div class="ui four column grid">
            <div class="column">
                <select id="s_fac" class="ui dropdown">
                    <option value="">คณะ</option>
                </select>
            </div>
            <div class="column">
                <select id="s_m" class="ui dropdown">
                    <option value="">สาขา</option>
                </select>
            </div>
            <div class="column">
                <select id="s_yn" class="ui dropdown">
                    <option value="">รหัส</option>
                </select>
            </div>
            <div style="margin-bottom:5px" class="column">
                <button id="search" style="margin-top:5px;width:48%;;float:right" class="ui green button">
                    <i class="search icon"></i>
                    ค้นหา
                </button>
                <button id="clear" style="margin-top:5px;width:48%;;float:left" class="ui red button">
                    <i class="times icon"></i>
                    ล้างค้า
                </button>
            </div>
        </div>

        <table style="width:100%" id="example" class="ui selectable celled table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ</th>
                    <th>สกุล</th>
                    <th>ที่อยู่</th>
                    <th>เบอร์โทร</th>
                    <th>คณะ</th>
                    <th>สาขา</th>
                </tr>
            </thead>
            <tbody>
                <th>ลำดับ</th>
                <th>รหัสนักศึกษา</th>
                <th>ชื่อ</th>
                <th>สกุล</th>
                <th>ที่อยู่</th>
                <th>เบอร์โทร</th>
                <th>คณะ</th>
                <th>สาขา</th>
            </tbody>
            <tfoot>
                <tr>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@endsection