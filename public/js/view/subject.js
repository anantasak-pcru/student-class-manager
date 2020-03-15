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
        ajax: '/admin/subject/getall',
        columns: [
            { data: 's_id', name: 's_id' },
            { data: 'name', name: 'name' },
            { data: 'full_name', name: 'full_name' },
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
            }
        ],
        buttons: [
            {
                text: '<i class="sync icon"></i> รีเฟรช',
                action: function (e, dt, node, config) {
                    table.ajax.reload();
                }
            },
            {
                text: '<i class="plus circle icon"></i> เพิ่มข้อมูลรายวิชา',
                className: "ui green button",
                action: function (e, dt, node, config) {
                    insert(table)
                }
            }
        ]
    });

    table.buttons().container()
        .appendTo($('#example_wrapper div.eight.column:eq(0)', table.table().container()));

    $('#example tbody').on('click', '#edit', function () {
        let data = table.row($(this).parents('tr')).data();
        edit(data, table)
    });

    $('body').on('change', '#fac_id', async function () {
        const fac_id = $('#fac_id').val()
        console.log(fac_id);
        const major = $('#m_id')//$('#m_id')
        console.log()
        major.empty()
            .append($("<option></option>")
                .attr("value", '')
                .text('--- สาขา ---'))
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
    });

    $('body').on('change', '#m_id', async function () {
        const m_id = $('#m_id').val()
        console.log(m_id);
        const teacher = $('#t_id')//$('#m_id')
        console.log()
        teacher.empty()
            .append($("<option></option>")
                .attr("value", '')
                .text('--- อาจารย์ ---'))
        await fetch('/admin/teacher/get/' + m_id)
            .then(response => {
                return response.json()
            }).then(data_json => {
                console.log(data_json.data);
                const data = data_json.data
                data.forEach(p => {
                    console.log('insert', p.f_name, p.t_id)
                    teacher.append($("<option></option>")
                        .attr("value", p.t_id)
                        .text(p.f_name + ' ' + p.l_name));
                });
            })
    });

    $('#example tbody').on('click', '#delete', function () {
        const data = table.row($(this).parents('tr')).data();
        _delete(data, table)
    });


});

async function insert(table) {
    let f_data = '<option value="">--- คณะ ---</option>';

    const f_val = await fetch('/admin/faculty/getall')
        .then(response => {
            return response.json()
        }).then(data_jason => {
            console.log(data_jason);
            const test = data_jason.data;
            test.forEach(s => {
                console.log(s.fac_id, s.t_name)
                f_data += '<option value="' + s.fac_id + '">' + s.name + '</option>' + '\n'
            });
            console.log(f_data);
            return f_data
        })

    const html =
        '<br>' +
        '<h1>เพิ่มข้อมูลรายวิชา</h1>' +
        '<select style="width:300px;" id="fac_id" class="ui dropdown" required>' +
        f_val +
        '</select>' +
        '<br><br>' +
        '<select style="width:300px;" id="m_id" class="ui dropdown" required>' +
        '<option value="">--- สาขา ---</option>' +
        '</select>' +
        '<br><br>' +
        '<select style="width:300px;" id="t_id" class="ui dropdown" required>' +
        '<option value="">--- อาจารย์ ---</option>' +
        '</select>' +
        '<br><br>' +
        '<div class="ui input">' +
        '<input style="width:300px;" maxlength="10" value="" id="s_id" type="text" placeholder="กรุณาป้อนรหัสวิชา">' +
        '</div>' +
        '<br><br>' +
        '<div class="ui input">' +
        '<input style="width:300px;" maxlength="100" value="" id="name" type="text" placeholder="กรุณาป้อนข้อมูลวิชา">' +
        '</div>' +
        '<br><br>'

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
                console.log('Value', document.getElementById('fac_id').value);
                if (document.getElementById('t_id').value == '')
                    return 'กรุณาเลือกอาจารย์ผู้สอน'
                if (document.getElementById('s_id').value == '')
                    return 'กรุณาป้อนรหัสรายวิชา'
                if (document.getElementById('name').value == '')
                    return 'กรุณาป้อนชื่อรายวิชา'
            },
            preConfirm: function () {
                var array = {
                    't_id': document.getElementById("t_id").value,
                    's_id': document.getElementById("s_id").value,
                    'name': document.getElementById("name").value,
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
            console.log(result.value[0].t_id)
            console.log(result.value[0].s_id)
            console.log(result.value[0].name)
            // insert
            $.ajax({
                type: "post",
                url: "/admin/subject/insert",
                data: {
                    _token: $('meta[name="_token"]').attr('content'),
                    s_id: _data.s_id,
                    name: _data.name,
                    t_id: _data.t_id,
                },
                cache: false,
                success: function (data) {
                    console.log('success');
                    console.log(data);
                    Swal.fire('สำเร็จ', '', 'success')
                    table.ajax.reload();
                    return data;

                },
                error: function (data) {
                    console.log('error');
                    console.log(data.responseJSON.error);
                    Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                    return data;
                }
            });
        }
    })
}

async function edit(data, table) {
    let fac_data = '<option value="">--- คณะ ---</option>';
    let m_data = '<option value="">--- สาขา ---</option>';
    let t_data = '<option value="">--- อาจารย์ ---</option>';

    let fac_id = null;
    let m_id = null;

    Swal.fire({
        title: 'กำลังโหลด',
        html: 'loading... <p id="log"></p>',
        onBeforeOpen: () => {
            Swal.showLoading()
        },
        onClose: () => {

        }
    })

    if(data.t_id) {
        m_id = await fetch('/admin/teacher/getmajor/' + data.t_id)
            .then(response => {
                return response.text()
            })
        fac_id = await fetch('/admin/major/getfac/' + m_id)
            .then(response => {
                return response.text()
            })
    }

    const fac_val = await fetch('/admin/faculty/getall')
        .then(response => {
            return response.json()
        }).then(data_jason => {
            console.log(data_jason);
            const test = data_jason.data;
            test.forEach(s => {
                console.log(s.fac_id, s.name)
                if ((fac_id !== "undefined" ? fac_id : '') == s.fac_id) {
                    fac_data += '<option selected value="' + s.fac_id + '">' + s.name + '</option>' + '\n'
                } else {
                    fac_data += '<option value="' + s.fac_id + '">' + s.name + '</option>' + '\n'
                }
            });
            console.log(fac_data);
            return fac_data
        })

    const m_val = await fetch('/admin/major/get/' + fac_id)
        .then(response => {
            return response.json()
        }).then(data_jason => {
            console.log(data_jason);
            const test = data_jason.data;
            test.forEach(s => {
                if (m_id == s.m_id) {
                    m_data += '<option selected value="' + s.m_id + '">' + s.name + '</option>' + '\n'
                } else {
                    m_data += '<option value="' + s.m_id + '">' + s.name + '</option>' + '\n'
                }
            });
            console.log(m_data);
            return m_data
        })

    const t_val = await fetch(m_val == null ? '/admin/teacher/getall' : ('/admin/teacher/get/' + m_id))
        .then(response => {
            return response.json()
        }).then(data_jason => {
            console.log(data_jason);
            const test = data_jason.data;
            test.forEach(s => {
                if (data.t_id == s.t_id) {
                    t_data += '<option selected value="' + s.t_id + '">' + s.f_name + ' ' + s.l_name + '</option>' + '\n'
                } else {
                    t_data += '<option value="' + s.t_id + '">' + s.f_name + ' ' + s.l_name + '</option>' + '\n'
                }
            });
            console.log(t_data);
            return t_data
        })



    const html =
        '<br>' +
        '<h1>แก้ไขข้อมูลรายวิชา</h1>' +
        '<select style="width:300px;" id="fac_id" class="ui dropdown" required>' +
        fac_val +
        '</select>' +
        '<br><br>' +
        '<select style="width:300px;" id="m_id" class="ui dropdown" required>' +
        m_val +
        '</select>' +
        '<br><br>' +
        '<select style="width:300px;" id="t_id" class="ui dropdown" required>' +
        t_val +
        '</select>' +
        '<br><br>' +
        '<div class="ui input">' +
        '<input readonly style="width:300px;" value="' + data.s_id + '" id="s_id" type="text" placeholder="กรุณาป้อนรหัสวิชา">' +
        '</div>' +
        '<br><br>' +
        '<div class="ui input">' +
        '<input style="width:300px;" maxlength="100" value="' + data.name + '" id="name" type="text" placeholder="กรุณาป้อนข้อมูลวิชา">' +
        '</div>' +
        '<br><br>'

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
                console.log('Value', document.getElementById('fac_id').value);
                if (document.getElementById('t_id').value == '')
                    return 'กรุณาเลือกอาจารย์ผู้สอน'
                if (document.getElementById('s_id').value == '')
                    return 'กรุณาป้อนรหัสรายวิชา'
                if (document.getElementById('name').value == '')
                    return 'กรุณาป้อนชื่อรายวิชา'
            },
            preConfirm: function () {
                var array = {
                    't_id': document.getElementById("t_id").value,
                    's_id': document.getElementById("s_id").value,
                    'name': document.getElementById("name").value,
                }
                return array;
            },
            onBeforeOpen: function (dom) {
                Swal.getInput().style.display = 'none';
            }
        }
    ]).then((result) => {
        console.log('result',result);
        
        if (!result.dismiss) {
            const _data = result.value[0];
            console.log(result.value[0].s_id)
            console.log(result.value[0].name)
            console.log(result.value[0].t_id)
            const param = {
                _token: $('meta[name="_token"]').attr('content'),
                s_id: result.value[0].s_id,
                name: result.value[0].name,
                t_id: result.value[0].t_id,
            }
            console.log('param',param);
            
            //update
            $.ajax({
                type: "post",
                url: "/admin/subject/update",
                data: param,
                cache: false,
                success: function (data) {
                    console.log('success');
                    console.log(data);
                    Swal.fire('สำเร็จ', '', 'success')
                    table.ajax.reload();
                    return data;

                },
                error: function (data) {
                    console.log('error', data);
                    console.log(data.responseJSON.error);
                    Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                    return data;
                }
            });
        }
    })
}

async function _delete(data, table) {
    const name = data.name;
    const id = data.s_id;
    console.log(data);
    Swal.fire({
        title: 'ลบข้อมูลรายวิชา ?',
        text:id + "  " + name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.value) {
            // delete
            $.ajax({
                type: "get",
                url: "/admin/subject/delete/" + data.s_id,
                cache: false,
                success: function (data) {
                    console.log('success');
                    console.log(data);
                    Swal.fire(
                        'สำเร็จ',
                        'ลบ  :  ' + name,
                        'success'
                    )
                    table.ajax.reload();
                },
                error: function (data) {
                    console.log('error');
                    Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                    console.log(data);
                    table.ajax.reload();
                }
            });
        }
    })
}