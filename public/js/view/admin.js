$(document).ready(function () {
    var table = $('#example').DataTable({
        dom: 'flBrtpi',
        language: {
            processing: 'กำลังโหลด...',
            loadingRecords: 'กำลังโหลด...',
            paginate: {
                previous: "ก่อนหน้า",
                next: "ถัดไป"
            },
            info: "แสดงทั้งหมด _TOTAL_ รายการ",
            infoEmpty: "แสดง 0 รายการ",
            infoFiltered: "(กรองจากรายการทั้งหมด _MAX_ รายการ)",
            search: '<i class="search icon"></i> ค้นหา :',
            emptyTable: '<i class="server icon"></i> ไม่มีข้อมูล',
            zeroRecords: '<i class="server icon"></i> ไม่มีข้อมูล',
        },
        serverSide: true,
        lengthChange: false,
        processing: true,
        scrollX: true,
        scrollY: '60vh',
        scrollCollapse: true,
        ajax: '/admin/admin/getall',
        columns: [
            { data: 'admin_id', name: 'admin_id' },
            { data: 'f_name', name: 'f_name' },
            { data: 'l_name', name: 'l_name' },

            {
                data: null,
                className: "ui center aligned",
                defaultContent: '<a id="edit" class="ui yellow button"><i class="fas fa-edit"></i>   แก้ไข</a>',
                orderable: false
            },
            {
                data: null,
                className: "ui center aligned",
                defaultContent: '<a id="delete" class="ui red button"><i class="fas fa-trash-alt"></i>   ลบ</a>',
                orderable: false
            },
        ],
        buttons: [
            {
                text: '<i class="sync icon"></i> รีเฟรช',
                action: function (e, dt, node, config) {
                    table.ajax.reload();
                }
            },
            {
                text: '<i class="fas fa-user-plus"></i> เพิ่มข้อมูลผู้ดูเเลระบบ',
                className: "ui green button",
                action: async function (e, dt, node, config) {
                    await insert(table)
                }
            }
        ]
    });
    table.buttons().container()
        .appendTo($('#example_wrapper div.eight.column:eq(0)', table.table().container()));

    $('body').on('change', '#fac_id', async function (e) {
        const select = $('#fac_id')
        const major = $('#m_id')
        const fac_id = select.val()
        major.empty()
            .append($("<option></option>")
                .attr("value", '')
                .text('--- สาขา ---'));
        await fetch('/admin/major/get/' + fac_id)
            .then(response => {
                return response.json()
            }).then(data_json => {
                console.log(data_json.data);
                const data = data_json.data
                data.forEach(p => {
                    console.log('insert', p.name, p.m_id)
                    major.append($("<option></option>")
                        .attr("value", p.m_id)
                        .text(p.name));
                });
            })
    })
    $('body').on('click', '#edit', async function () {
        const data = table.row($(this).parents('tr')).data();
        console.log('test', data)
        edit(table, data)
    })
    $('body').on('click', '#delete', async function () {
        const data = table.row($(this).parents('tr')).data();
        console.log('test', data)
        _delete(table, data)
    })
    $('body').on('click', '#chk_password', async function () {
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
                        admin_id: document.getElementById("admin_id").value,
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
});
// insert Funtion
async function insert(table) {
    let p_data = '<option value="">--- ตำเเหน่ง ---</option>';
    ////////////
    const p_val = await fetch('/admin/position/getall')
        .then(response => {
            return response.json()
        }).then(data_jason => {
            console.log(data_jason);
            const test = data_jason.data;
            test.forEach(s => {
                console.log(s.p_id, s.name)
                p_data += '<option value="' + s.p_id + '">' + s.name + '</option>' + '\n'
            });
            console.log(p_data);
            return p_data
        })

    const html =

        '<br>' +
        '<h1>เพิ่มข้อมูลผู้ดูเเลระบบ</h1>' +
        '<div style="width:100%;" class="ui labeled input"><div class="ui label">รหัสผู้ดูเเลระบบ</div>' +
        '<input maxlength="12" value="" id="admin_id" type="text" placeholder="รหัสผู้ดูเเลระบบ">' +
        '</div><br><br>' +
        '<div style="width:100%;" class="ui labeled input"><div class="ui label">ชื่อ</div>' +
        '<input maxlength="100" value="" id="f_name" type="text" placeholder="ชื่อ">' +
        '</div><br><br>' +
        '<div style="width:100%;" class="ui labeled input"><div class="ui label">นามสกุล</div>' +
        '<input maxlength="100" value="" id="l_name" type="text" placeholder="ชื่อ">' +
        '</div><br><br>' +
        // '<div style="width:100%;" class="ui labeled input"><div class="ui label">ที่อยู่</div>' +
        // '<input value="" id="address" type="text" placeholder="ที่อยู่">' +
        // '</div><br><br>' +
        // '<div style="width:100%;" class="ui labeled input"><div class="ui label">เบอร์โทร</div>' +
        // '<input value="" id="tel" type="text" placeholder="เบอร์โทร">' +
        // '</div><br><br>' +
        '<div style="width:100%;" class="ui labeled input"><div class="ui label">รหัสผ่าน</div>' +
        '<input maxlength="100" value="" id="password" type="text" placeholder="รหัสผ่าน">' +
        '</div><br><br>' +
        '<select style="width:100%;" id="p_id" class="ui dropdown" required>' +
        p_val +
        '</select>' +
        '<br><br>' +
        '</div>'


    Swal.mixin({
        confirmButtonText: 'บันทึก',
        cancelButtonText: 'ยกเลิก',
        showCancelButton: true,
        buttonsStyling: true,
        input: 'text',
    }).queue([
        {
            html: html,
            inputValidator: () => {
                if (document.getElementById('admin_id').value == '')
                    return 'กรุณากรอกรหัสผู้ดูเเลระบบ'
                if (document.getElementById('f_name').value == '')
                    return 'กรุณากรอกชื่อ'
                if (document.getElementById('l_name').value == '')
                    return 'กรุณากรอกนามสกุล'
                if (document.getElementById("password").value == '')
                    return 'กรุณาป้อนรหัสผ่าน'
                if (document.getElementById("password").value.toString().trim().length < 6)
                    return 'รหัสผ่านสั้นเกินไป *6 ตัวอักษรขึ้นไป'
                if (document.getElementById('p_id').value == '')
                    return 'กรุณาเลือกตำเเหน่ง'
            },
            preConfirm: function () {
                var array = {
                    'admin_id': document.getElementById("admin_id").value,
                    'f_name': document.getElementById("f_name").value,
                    'l_name': document.getElementById("l_name").value,
                    'password': document.getElementById("password").value,
                    'p_id': document.getElementById("p_id").value,
                }
                return array;
            },
            onBeforeOpen: function (dom) {
                Swal.getInput().style.display = 'none';
            }
        }
    ]).then((result) => {
        if (!result.dismiss) {
            const _data = result.value[0];
            console.log(result.value)
            insert
            $.ajax({
                type: "post",
                url: "/admin/admin/insert",
                data: {
                    _token: $('meta[name="_token"]').attr('content'),
                    admin_id: _data.admin_id,
                    f_name: _data.f_name,
                    l_name: _data.l_name,
                    password: _data.password,
                    p_id: _data.p_id,
                },
                cache: false,
                success: function (data) {
                    console.log('success');
                    console.log(data);
                    Swal.fire('สำเร็จ', '', 'success')
                    table.ajax.reload();

                },
                error: function (data) {
                    console.log('error');
                    console.log(data);
                    console.log(data.responseJSON.error);
                    Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                }
            });
        }
    })
}

async function edit(table, data) {
    let p_data = '<option value="">--- ตำเเหน่ง ---</option>';
    ////////////
    Swal.fire({
        title: 'กำลังโหลด',
        html: 'loading...',
        onBeforeOpen: () => {
            Swal.showLoading()
        },
        onClose: () => {

        }
    })
    Swal.showLoading()

    const p_val = await fetch('/admin/position/getall')
        .then(response => {
            return response.json()
        }).then(data_jason => {
            console.log(data_jason)
            const test = data_jason.data;
            test.forEach(s => {
                if (data.p_id == s.p_id) {
                    p_data += '<option selected value="' + s.p_id + '">' + s.name + '</option>' + '\n'
                } else {
                    p_data += '<option value="' + s.p_id + '">' + s.name + '</option>' + '\n'
                }
            });
            console.log(p_data);
            return p_data
        })


    const html =
        '<br>' +
        '<h1>แก้ไขข้อมูลผู้ดูเเลระบบ</h1>' +
        '<div style="width:100%;" class="ui labeled disabled input"><div class="ui label">รหัสผู้ดูเเลระบบ</div>' +
        '<input maxlength="12" readonly value="' + data.admin_id + '" id="admin_id" type="text" placeholder="รหัสผู้ดูเเลระบบ">' +
        '</div><br><br>' +
        '<div style="width:100%;" class="ui labeled input"><div class="ui label">ชื่อ</div>' +
        '<input maxlength="100" value="' + data.f_name + '" id="f_name" type="text" placeholder="ชื่อ">' +
        '</div><br><br>' +
        '<div maxlength="100" style="width:100%;" class="ui labeled input"><div class="ui label">นามสกุล</div>' +
        '<input  value="' + data.l_name + '" id="l_name" type="text" placeholder="นามสกุล">' +
        '</div><br><br>' +
        // '<div style="width:100%;" class="ui labeled input"><div class="ui label">ที่อยู่</div>' +
        // '<input value="' + data.address + '" id="address" type="text" placeholder="ที่อยู่">' +
        // '</div><br><br>' +
        // '<div style="width:100%;" class="ui labeled input"><div class="ui label">ที่อยู่</div>' +
        // '<input value="' + data.tel + '" id="tel" type="text" placeholder="เบอร์โทร">' +
        // '</div><br><br>' +
        '<button style="width:100%;" id="chk_password" class="green ui button">เปลียนรหัสผ่าน</button>' +
        '<br><br>' +
        '<select style="width:100%;" id="p_id" class="ui dropdown" required>' +
        p_val +
        '</select>' +
        '<br><br>' +
        '</div>'

    Swal.mixin({
        customClass: 'swal-wide',
        confirmButtonText: 'บันทึก',
        cancelButtonText: 'ยกเลิก',
        showCancelButton: true,
        buttonsStyling: true,
        input: 'text',
    }).queue([
        {
            html: html,
            inputValidator: () => {
                if (document.getElementById('admin_id').value == '')
                    return 'กรุณากรอกรหัสผู้ดูเเลระบบ'
                if (document.getElementById('f_name').value == '')
                    return 'กรุณากรอกชื่อ'
                if (document.getElementById('l_name').value == '')
                    return 'กรุณากรอกนามสกุล'
                if (document.getElementById('p_id').value == '')
                    return 'กรุณาเลือกตำเเหน่ง'
            },
            preConfirm: function () {
                var array = {
                    'admin_id': document.getElementById("admin_id").value,
                    'f_name': document.getElementById("f_name").value,
                    'l_name': document.getElementById("l_name").value,
                    'p_id': document.getElementById("p_id").value,
                }
                return array;
            },
            onBeforeOpen: function (dom) {
                Swal.getInput().style.display = 'none';
            }
        }
    ]).then((result) => {
        console.log(result)
        if (!result.dismiss) {
            const _data = result.value[0];
            console.log(result.value)
            // update
            $.ajax({
                type: "post",
                url: "/admin/admin/update",
                data: {
                    _token: $('meta[name="_token"]').attr('content'),
                    admin_id: _data.admin_id,
                    f_name: _data.f_name,
                    l_name: _data.l_name,
                    p_id: _data.p_id,
                },
                cache: false,
                success: function (data) {
                    console.log('success');
                    console.log(data);
                    Swal.fire('สำเร็จ', '', 'success')
                    table.ajax.reload();

                },
                error: function (data) {
                    console.log('error');
                    console.log(data);
                    console.log(data.responseJSON.error);
                    Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                    table.ajax.reload();
                }
            });
        }
    })
}

async function _delete(table, data) {
    Swal.fire({
        title: 'ลบข้อมูลผู้ดูเเลระบบ ?',
        text: data.admin_id + "  " + data.f_name + "  " + data.l_name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (!result.value) return
        await fetch('/admin/admin/delete/' + data.admin_id)
            .then(response => {
                if (!response.ok) throw 'Error'
                Swal.fire('สำเร็จ', '', 'success')
                table.ajax.reload()
                return response.json()
            }).then(data => {
                console.log(data)
            }).catch(err => {
                Swal.fire('ผิดพลาด', err, 'error')
                console.log(err)
            })
    })
}