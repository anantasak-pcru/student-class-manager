@extends('layouts.admin')
@section('resource')

<meta name="_token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{asset('css/base/fomaticui.css')}}">
<link rel="stylesheet" href="{{asset('css/datatable/dataTables.semanticui.min.css')}}">}
<link rel="stylesheet" href="{{asset('css/datatable/buttons.semanticui.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/alertify.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/alerttify_semantic.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/yearpicker.css')}}">
<link rel="stylesheet" href="{{asset('css/base/fixedColumns.semanticui.min.css')}}">

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
{{-- <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script> --}}

<script src="{{asset('js/view/course_regist.js')}}"></script>

@endsection
@section('content')
<div align="center">

    <div style="width:90%;margin-top: 10px;">
        <h1 style="font-family: 'Itim', cursive;" class="ui center aligned">จัดการข้อมูลการลงทะเบียนรายวิชา</h1>
        <div class="ui one column grid">
            <div class="column">
                <div class="ui two column grid">
                    <div class="column">
                        <div class="ui two column grid">
                            <div class="column">
                                <fieldset>
                                    <legend>
                                        <h4><i class="user graduate icon"></i>นักศึกษา:</h4>
                                    </legend>
                                    <select id="s_fac" class="ui dropdown">
                                        <option value="">คณะ</option>
                                    </select>
                                    <select id="s_m" class="ui dropdown">
                                        <option value="">สาขา</option>
                                    </select>
                                    <select id="s_yn" class="ui fluid search selection dropdown">
                                        <option value="">รหัส</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="column">
                                <fieldset>
                                    <legend>
                                        <h4><i class="book icon"></i>วิชา: </h4>
                                    </legend>
                                    <select id="s_s" class="ui dropdown">
                                        <option value="">วิชา</option>
                                    </select>
                                    <div style="width:100%;margin-top:5px;" class="ui input">
                                        <input readonly id="s_y" type="text" placeholder="ปีการศึกษา">
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
                    <div class="column">
                        {{-- <h1>Column2</h1> --}}
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="ui two column grid">
                    <div class="column">
                        <div class="column">
                            <h3 class="ui left aligned header"><span>นักเรียนที่ไม่ได้อยู่ในชั้นเรียน</span></h3>

                            <button id="search" style="float:right;margin-top:10px;margin-bottom:10px"
                                class="ui blue button"><i class="search icon"></i>ค้นหา</button>
                            <button id="clear_subject" style="float:right;margin-top:10px;margin-bottom:10px"
                                class="ui red button"><i class="times icon"></i>วิชา</button>
                            <button id="clear_student" style="float:right;margin-top:10px;margin-bottom:10px"
                                class="ui red button"><i class="times icon"></i>นักศึกษา</button>
                            <div style="margin-top:20px">
                                <table style="width:100%" id="student" class="ui inverted teal selectable celled table">
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
                                <button id="save" style="float:right;margin-top:10px" class="ui green button"><i
                                        class="user plus icon"></i>เพิ่มเข้าชั้นเรียน</button>
                                <button id="notclass_disselectall" style="float:right;margin-top:10px"
                                    class="ui orange button"><i class="times icon"></i>ไม่เลือกทั้งหมด</button>
                                <button id="notclass_selectall" style="float:right;margin-top:10px"
                                    class="ui purple button"><i class="check icon"></i>เลือกทั้งหมด</button>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <h3 class="ui left aligned header"><span>นักเรียนที่อยู่ในชั้นเรียน</span></h3>
                        <div class="empty-button"></div>
                        <table style="width:100%;" id="inclass" class="ui inverted green selectable celled table">
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
                        <button id="remove" style="float:right;margin-top:10px" class="ui red button"><i
                                class="trash alternate icon"></i>ลบออกจากชั้นเรียน</button>
                        <button id="inclass_disselectall" style="float:right;margin-top:10px"
                            class="ui orange button"><i class="times icon"></i>ไม่เลือกทั้งหมด</button>
                        <button id="inclass_selectall" style="float:right;margin-top:10px" class="ui purple button"><i
                                class="check icon"></i>เลือกทั้งหมด</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection