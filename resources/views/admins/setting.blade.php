@extends('layouts.admin')
@section('resource')
<meta name="_token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{asset('css/base/fomaticui.css')}}">
<link rel="stylesheet" href="{{asset('css/base/alertify.min.css')}}">
<link rel="stylesheet" href="{{asset('css/base/alerttify_semantic.min.css')}}">
<style>
  #mymenu {
    margin-top: 20px
  }
</style>
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
document.getElementById('chk_password').addEventListener('click',async ()=>{
    alertify.defaults.theme.ok = "ui green button";
        alertify.defaults.theme.cancel = "ui red button";
        alertify.prompt('เปลี่ยนรหัสผ่าน', 'กรอกรหัสผ่าน', null,
            async function (e, value) {
                console.log(value);
                if (value == '') {
                    alertify.notify('กรุณากรอกรหัสผ่าน', 'error', 5, function () { })
                    return
                }
                console.log("Token", $('meta[name="_token"]').attr('content'))
                await fetch('/admin/admin/updatepassword', {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": $('meta[name="_token"]').attr('content')
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body: JSON.stringify({
                        admin_id: document.getElementById("admin_id").innerText,
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
<div align="center">
  <div style="width:50%;margin-top: 10px;">
    <h1 style="font-family: 'Itim', cursive;" class="ui center aligned">ตั้งค่าข้อมูลส่วนตัว</h1>
    <div class="ui horizontal divider">
      <label for="">ID : <span id="admin_id">{{$data->admin_id}}</span></label>
    </div>
    <form class="ui form segment" method="POST" action="{{url('admin/admin/update')}}">
      {{ csrf_field() }}
      <input type="hidden" name="admin_id" value="{{$data->admin_id}}">
      <input type="hidden" name="setting" value="setting">
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
          <label>ตำแหน่ง</label>
          <input readonly value="{{$data->name}}" placeholder="Username" type="text">
        </div>
        <div class="field">
          <label>เปลี่ยนรหัสผ่าน</label>
          <button id="chk_password" style="width:100%" type="button" class="ui purple button">เปลี่ยนรหัสผ่าน</button>
        </div>
      </div>
      <div class="ui green submit button">บันทึก</div>
      <div class="ui error message"></div>
    </form>
    @if (session('success'))
    <div class="ui success message">
      <div class="header">สำเร็จ</div>
      <p>{{session('success')}}</p>
    </div>
    @endif
    @if (session('error'))
    <div class="ui error message">
      <div class="header">ผิดพลาด</div>
      <p>{{session('error')}}</p>
    </div>
    @endif
  </div>
</div>
@endsection