@extends('layouts.user')
@section('stat','Student')
@section('panel_name','Student Panel')
@section('resource')
<meta name="_token" content="{{ csrf_token() }}" />
<meta name="id" content="{{session('session_id')}}" />
<link rel="stylesheet" href="{{asset('css/base/alertify.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/alerttify_semantic.min.css')}}">
@endsection
@section('script')
<script src="{{asset('js/script/alertifyjs.js')}}"></script>
<script>
    $('.ui.form')
  .form({
    fields: {
      f_name: {
        identifier: 'f_name',
        rules: [
          {
            type   : 'empty',
            prompt : 'กรุณาป้อนชื่อของคุณ'
          }
        ]
      },
      l_name: {
        identifier: 'l_name',
        rules: [
          {
            type   : 'empty',
            prompt : 'กรุณาป้อนนามสกุลของคุณ'
          }
        ]
      },
      address: {
        identifier: 'address',
        rules: [
          {
            type   : 'empty',
            prompt : 'กรุณาป้อนที่อยู่ของคุณ'
          }
        ]
      },
      tel: {
        identifier: 'tel',
        rules: [
          {
            type   : 'empty',
            prompt : 'กรุณาป้อนเบอร์โทรของคุณ'
          },
          {
            type: 'regExp',
            value: /^\d+$/,
            prompt : 'เบอร์โทรต้องเป็นตัวเลขเท่านั้น'
          }
        ]
      },
    }
  })
;
document.getElementById('chk_pw').addEventListener('click',async ()=>{
    alertify.defaults.theme.ok = "ui green button";
        alertify.defaults.theme.cancel = "ui red button";
        alertify.prompt('เปลี่ยนรหัสผ่าน', 'กรอกรหัสผ่าน', null,
            async function (e, value) {
                if (value == '') {
                    alertify.notify('กรุณากรอกรหัสผ่าน', 'error', 5, function () { })
                    return
                }
                console.log("Token", $('meta[name="_token"]').attr('content'))
                await fetch('/student/updatepassword', {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": $('meta[name="_token"]').attr('content')
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body: JSON.stringify({
                        std_id: document.getElementById("std_id").value,
                        password: value
                    }),
                }).then(response => {
                    if (!response.ok) throw 'รหัสผ่านสั้นเกินไป *6 ตัวอักรขึ้นไป'
                    return response.json()
                }).then(data_json => {
                    console.log(data_json)
                    Swal.fire('สำเร็จ', data_json.status, 'success')
                    return
                }).catch(err => {
                    Swal.fire('ผิดพลาด', err, 'error')
                    return
                })
            },
            function () { })
            .set('labels', { ok: 'บันทึก', cancel: 'ยกเลิก' })
            .set('closable', false)
            .set({ 'movable': false, 'moveBounded': false });
        return;
})
</script>
@endsection
@section('content')
<div class="container">
    <div class="ui one column grid">
        <div style="width:50%;margin-left:25%" class="column">
                <h1 style="font-family: 'Itim', cursive;" class="ui header center aligned">ตั้งค่าข้อมูลส่วนตัว</h1>
                <div class="ui horizontal divider">
                    <label for="">รหัสนักศึกษา <span>{{$data->std_id}}</span></label>
                </div>
                <form style="text-align:center" class="ui form segment" method="POST" action="{{url('student/update')}}">
                    {{ csrf_field() }}
                    <input value="{{$data->m_id}}" type="hidden" name="m_id">
                    <input value="{{$data->std_id}}" id="std_id" type="hidden" name="std_id">
                    <input value="setting" type="hidden" name="setting">
                    <div class="two fields">
                        <div class="field">
                            <label>ชื่อ</label>
                            <input maxlength="100" value="{{$data->f_name}}" placeholder="ชื่อ" name="f_name" type="text">
                        </div>
                        <div class="field">
                            <label>สกุล</label>
                            <input maxlength="100" value="{{$data->l_name}}" placeholder="สกุล" name="l_name" type="text">
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>ที่อยู่</label>
                            <input maxlength="100" value="{{$data->address}}" placeholder="ที่อยู่" name="address" type="text">
                        </div>
                        <div class="field">
                            <label>เบอร์โทร</label>
                            <input maxlength="15" value="{{$data->tel}}" placeholder="เบอร์โทร" name="tel" type="text">
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>สาขา</label>
                            <input readonly value="{{$data->major}}" placeholder="สาขา" type="text">
                        </div>
                        <div class="field">
                            <label>คณะ</label>
                            <input value="{{$data->faculty}}" type="text">
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>เปลี่ยนรหัสผ่าน</label>
                            <button id="chk_pw" style="width:100%" type="button"
                                class="ui purple button">เปลี่ยนรหัสผ่าน</button>
                        </div>
                    </div>
                    <button type="submit" class="ui green button">บันทึก</button>
                    <div class="ui error message"></div>
                </form>
                @if (session('success'))
                <div style="text-align:center" class="ui success message">
                    <div class="header">สำเร็จ</div>
                    <p>{{session('success')}}</p>
                </div>
                @endif
                @if (session('error'))
                <div style="text-align:center" class="ui error message">
                    <div class="header">ผิดพลาด</div>
                    <p class="aligned center">{{session('error')}}</p>
                </div>
                @endif
        </div>
    </div>
</div>
@endsection