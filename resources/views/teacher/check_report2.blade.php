@extends('layouts.user')
@section('stat','Teacher')
@section('panel_name','Teacher Panel')
@section('resource')
<meta name="_token" content="{{ csrf_token() }}" />
<meta name="id" content="{{session('session_id')}}" />
<link rel="stylesheet" href="{{asset('css/datatable/dataTables.semanticui.min.css')}}">
<link rel="stylesheet" href="{{asset('css/datatable/buttons.semanticui.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/yearpicker.css')}}">
<style>
tbody {
    display:block;
    height:600px;
    overflow:auto;
}
thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}
thead {
    width: calc( 100% - 1em )
}
table {
    width:400px;
}
</style>
@endsection
@section('script')

@endsection
@section('content')
<div class="container">
    <h1 style="font-family: 'Itim', cursive;" class="ui header center aligned">รายงานข้อมูลการเช็คชื่อเข้าชั้นเรียน</h1>
    <h1 style="font-family: 'Itim', cursive;" class="ui header center aligned">วิชา {{$s_name->name}}</h1>
    <div style="height:500px" class="ui one column grid">
        <div style="width:100vw;" class="column">
            <table class="ui selectable celled table">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>รหัสนักศึกษา</th>
                        <th>ชื่อ - สกุล</th>
                        @foreach ($value2 as $d)
                        @if (isset($d->date))
                        <th>{{$d->date}}</th>
                        @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($value as $val) --}}
                    @isset ($value[0])
                    @foreach ($value[0] as $s)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$s->std_id}}</td>
                        <td>{{$s->f_name.' '.$s->f_name}}</td>
                        @for ($i = 0; $i < count($value); $i++)
                        
                        @if (isset($value[$i][$loop->iteration - 1]->status))
                            @if ($value[$i][$loop->iteration - 1]->status == '0')
                                <td>เข้าเรียน</td>
                            @elseif($value[$i][$loop->iteration - 1]->status == '1')
                                <td>ขาดเรียน</td>
                            @elseif($value[$i][$loop->iteration - 1]->status == '2')
                                <td>ลา</td>
                            @endif
                        @else
                            <td></td>
                        @endif

                        @endfor
                    </tr>
                    {{-- @endforeach --}}
                    @endforeach
                    @endisset

                    @if(!isset($value[0]))
                    <tr>
                        <td></td>
                        <td style="text-align: center;">
                            ไม่พบข้อมูล !!
                        </td>
                        <td></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection