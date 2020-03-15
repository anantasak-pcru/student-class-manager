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
<script src="{{asset('js/script/yearpicker.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
$("#year").yearpicker({
    year: new Date().getFullYear() + 543,
    startYear: 2550,
    endYear: 2660
  });

  document.getElementById('year').value = ''
})
    function openurl(){
        const s  = document.getElementById('s_s').value
        const y  = document.getElementById('year').value
        const t  = document.getElementById('s_term').value
        if(!s || !y || !t) return
        window.location.href = "/teacher/report2/"+s+'/'+y+'/'+t
    }

</script>
@endsection
@section('content')
<div class="container">
    <h1 style="font-family: 'Itim', cursive;" class="ui header center aligned">รายงานข้อมูลการเช็คชื่อเข้าชั้นเรียน</h1>
    <div class="ui one column grid">
        <div style="width:50%;margin-left:25%" class="column">
            <form style="text-align:center" class="ui form segment">
                {{ csrf_field() }}
                <input value="setting" type="hidden" name="setting">
                <div class="two fields">
                    <div class="field">
                        <select style="width:100%" id="s_s" class="ui dropdown">
                            <option value="">วิชา</option>
                            @foreach ($subject as $s)
                            <option value="{{$s->s_id}}">{{$s->s_id.' : '.$s->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <input readonly id="year" type="text" placeholder="ปีการศึกษา">
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <select style="width:100%" id="s_term" class="ui dropdown">
                            <option value="">ภาคเรียน</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="field">

                    </div>
                </div>
                <button type="button" onclick="openurl()" class="ui green button">ค้นหา</button>
                <div class="ui error message"></div>
            </form>
        </div>
    </div>
</div>
@endsection