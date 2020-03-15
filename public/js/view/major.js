$(document).ready(function () {
    $(document).on('click', '#faculty', function () {
    })

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
        ajax: '/admin/major/getall',
        columns: [
            { data: 'm_id', name: 'm_id' },
            { data: 'name', name: 'name' },
            { data: 'fac_name', name: 'fac_name' },
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
                text: '<i class="plus circle icon"></i> เพิ่มข้อมูลสาขา',
                className: "ui green button",
                action: async function (e, dt, node, config) {
                    let p_data = '<option value="">--- คณะ ---</option>';
                    ////////////
                    const fac_val = await fetch('/admin/faculty/getall')
                        .then(response => {
                            return response.json()
                        }).then(data_jason => {
                            console.log(data_jason);
                            const test = data_jason.data;
                            test.forEach(s => {
                                console.log(s.fac_id, s.name)
                                p_data += '<option value="' + s.fac_id + '">' + s.name + '</option>' + '\n'
                            });
                            console.log(p_data);
                            return p_data
                        })


                    const html =
                        '<br>' +
                        '<h1>เพิ่มข้อมูลสาขา</h1>' +
                        '<select style="width:300px;" id="fac_id" class="ui dropdown" required>' +
                        fac_val +
                        '</select>' +
                        '<br><br>' +
                        '<div class="ui input">' +
                        '<input style="width:300px;" maxlength="50" value="" id="m_name" type="text" placeholder="กรุณาป้อนข้อมูลสาขา">' +
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
                                console.log('Value', document.getElementById('fac_id').value);
                                if (document.getElementById('fac_id').value == '')
                                    return 'กรุณาเลือกคณะ'
                                if (document.getElementById('m_name').value == '')
                                    return 'กรุณาป้อนข้อมูลสาขา'
                            },
                            preConfirm: function () {
                                var array = {
                                    'fac_id': document.getElementById("fac_id").value,
                                    'm_name': document.getElementById("m_name").value,
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
                            console.log(result.value[0].fac_id)
                            console.log(result.value[0].m_name)
                            // insert
                            $.ajax({
                                type: "post",
                                url: "/admin/major/insert",
                                data: {
                                    _token: $('meta[name="_token"]').attr('content'),
                                    fac_id: _data.fac_id,
                                    name: _data.m_name,
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
                                    console.log(data.responseJSON.error);
                                    Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                                    table.ajax.reload();
                                }
                            });
                        }
                    })
                }
            }
        ]
    });

    table.buttons().container()
        .appendTo($('#example_wrapper div.eight.column:eq(0)', table.table().container()));

    $('#example tbody').on('click', '#edit', async function () {
        let data = table.row($(this).parents('tr')).data();
        let p_data = '<option value="">--- คณะ ---</option>';
        console.log('Table data : ', data);
        ////////////
        const fac_val = await fetch('/admin/faculty/getall')
            .then(response => {
                return response.json()
            }).then(data_jason => {
                console.log(data_jason);
                const test = data_jason.data;
                test.forEach(s => {
                    console.log(s.fac_id, s.name)
                    if (s.fac_id == data.fac_id) {
                        p_data += '<option selected value="' + s.fac_id + '">' + s.name + '</option>' + '\n'
                    } else {
                        p_data += '<option value="' + s.fac_id + '">' + s.name + '</option>' + '\n'
                    }
                });
                console.log(p_data);
                return p_data
            })


        const html =
            '<br>' +
            '<h1>แก้ไขข้อมูลสาขา</h1>' +
            '<select style="width:300px;" id="fac_id" class="ui dropdown" required>' +
            fac_val +
            '</select>' +
            '<br><br>' +
            '<div class="ui input">' +
            '<input style="width:300px;" maxlength="50" value="' + data.name + '" id="m_name" type="text" placeholder="กรุณาป้อนข้อมูลสาขา">' +
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
                    console.log('Value', document.getElementById('fac_id').value);
                    if (document.getElementById('fac_id').value == '')
                        return 'กรุณาเลือกคณะ'
                    if (document.getElementById('m_name').value == '')
                        return 'กรุณาป้อนข้อมูลสาขา'
                },
                preConfirm: function () {
                    var array = {
                        'fac_id': document.getElementById("fac_id").value,
                        'm_name': document.getElementById("m_name").value,
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
                console.log(result.value[0].fac_id)
                console.log(result.value[0].m_name)
                //update
                $.ajax({
                    type: "post",
                    url: "/admin/major/update",
                    data: {
                        _token: $('meta[name="_token"]').attr('content'),
                        fac_id: _data.fac_id,
                        name: _data.m_name,
                        m_id: data.m_id,
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

        ///////////////  
    });


    // Delete Funtion
    $('#example tbody').on('click', '#delete', function () {
        const data = table.row($(this).parents('tr')).data();
        const name = data.name;
        console.log(data);
        Swal.fire({
            title: 'ลบข้อมูลสาขา ?',
            text:name,
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
                    url: "/admin/major/delete/" + data.m_id,
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
                        return data;

                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                        return data;
                    }
                });
            }
        })
    });
});