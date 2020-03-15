@extends('layouts.admin')
@section('resource')

<meta name="_token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{asset('css/base/fomaticui.css')}}">
<link rel="stylesheet" href="{{asset('css/datatable/dataTables.semanticui.min.css')}}">}
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

<script src="{{asset('js/script/pdfmake.min.js')}}"></script>
<script src="{{asset('js/script/vfs_fonts.js')}}"></script>
<script src="{{asset('js/script/buttons.html5.min.js')}}"></script>
<script src="{{asset('js/script/buttons.print.min.js')}}"></script>

<script src="{{asset('js/view/course_regist_report.js')}}"></script>

<style>
    #inclass_filter {
        margin-bottom: 10px;
    }
</style>

@endsection
@section('content')
<div align="center">
    <div style="width:90%;margin-top: 10px;">
        <h1 style="font-family: 'Itim', cursive;" class="ui center aligned">รายงานข้อมูลการลงทะเบียนรายวิชา</h1>
        <div class="ui one column grid">
            <div class="column">
                <div class="ui one column grid">
                    <div class="column">
                        <fieldset>
                            <legend>
                                <h4><i class="book icon"></i>วิชา: </h4>
                            </legend>
                            <select id="s_s" class="ui dropdown">
                                <option value="">วิชา</option>
                            </select>
                            <div style="width:100%;margin-top:5px;" class="ui input">
                                <input id="s_y" type="text" placeholder="ปีการศึกษา">
                            </div>
                            <select id="s_term" class="ui dropdown">
                                <option value="">ภาคเรียน</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom:100px" class="column">
            <div style="margin-bottom:5px" class="ui two column grid">
                <div class="column">
                    <h3 class="ui left aligned header"><span>นักเรียนที่อยู่ในชั้นเรียน</span></h3>
                </div>
                <div class="column">
                    <button id="search" style="float:right;" class="ui blue button"><i
                            class="search icon"></i>ค้นหา</button>
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
                        <th>คณะ</th>
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