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
        ajax: '/admin/position/getall',
        columns: [
            { data: 'p_id', name: 'p_id' },
            { data: 'name', name: 'name' },
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
                text: '<i class="plus circle icon"></i> เพิ่มข้อมูลตำเเหน่ง',
                className: "ui green button",
                action: async function (e, dt, node, config) {
                    const { value: name } = await Swal.fire({
                        title: 'เพิ่มข้อมูลตำเเหน่ง',
                        input: 'text',
                        inputPlaceholder: 'กรุณาป้อนชื่อตำเเหน่ง',
                        inputAttributes: {
                            maxlength: 50,
                            autocapitalize: 'off',
                            autocorrect: 'off'
                        },
                        inputValidator: (value) => {
                            return !value && 'กรุณาป้อนชื่อตำเเหน่ง'
                        },
                    })

                    if (name) {
                        $.ajax({
                            type: "post",
                            url: "/admin/position/insert",
                            data: {
                                _token: $('meta[name="_token"]').attr('content'),
                                name: name
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
                                Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                                table.ajax.reload();
                            }
                        });
                    }
                }
            }
        ]
    });

    table.buttons().container()
        .appendTo($('#example_wrapper div.eight.column:eq(0)', table.table().container()));

    $('#example tbody').on('click', '#edit', function () {
        var data = table.row($(this).parents('tr')).data();
        console.log(data);
        ////////////
        Swal.fire({
            title: 'แก้ไขข้อมูลตำเเหน่ง',
            input: 'text',
            inputValue: data.name,
            inputAttributes: {
                maxlength: 50,
                autocapitalize: 'off',
                autocorrect: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                return !value && 'กรุณาป้อนชื่อตำเเหน่ง'
            },
            preConfirm: (login) => {
                $.ajax({
                    type: "post",
                    url: "/admin/position/update",
                    data: {
                        _token: $('meta[name="_token"]').attr('content'),
                        p_id: data.p_id,
                        name: login
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
                        Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                        table.ajax.reload();
                    }
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            console.log(result);
        })
        ///////////////  
    });

    // Delete Funtion
    $('#example tbody').on('click', '#delete', function () {
        const data = table.row($(this).parents('tr')).data();
        const name = data.name;
        console.log(data);
        Swal.fire({
            title: 'ลบข้อมูลตำเเหน่ง ?',
            text:name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "get",
                    url: "/admin/position/delete/" + data.p_id,
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
                        console.log(data);
                        Swal.fire('ผิดพลาด', data.responseJSON.error, 'error')
                        table.ajax.reload();
                    }
                });
            }
        })
    });
});