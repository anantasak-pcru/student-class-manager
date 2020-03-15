@extends('layouts.admin')
@section('resource')

<meta name="_token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{asset('css/base/fomaticui.css')}}">
<link rel="stylesheet" href="{{asset('css/datatable/dataTables.semanticui.min.css')}}">}
<link rel="stylesheet" href="{{asset('css/datatable/buttons.semanticui.min.css')}}">

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

<script src="{{asset('js/script/pdfmake.min.js')}}"></script>
<script src="{{asset('js/script/vfs_fonts.js')}}"></script>
<script src="{{asset('js/script/buttons.html5.min.js')}}"></script>
<script src="{{asset('js/script/buttons.print.min.js')}}"></script>

<script src="{{asset('js/view/major_report.js')}}"></script>

@endsection
@section('content')
<div align="center">
    <div style="width:50%;margin-top: 10px;">
        <h1 style="font-family: 'Itim', cursive;" class="ui center aligned">รายงานข้อมูลสาขา</h1>
        <table style="width:100%;" id="example" class="ui selectable celled table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รหัสสาขา</th>
                    <th>ชื่อสาขา</th>
                    <th>ชื่อคณะ</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection