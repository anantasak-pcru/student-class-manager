$(document).ready(async function () {

    pdfMake.fonts = {
        THSarabunNew: {
            normal: 'THSarabun.ttf',
            bold: 'THSarabun-Bold.ttf',
            italics: 'THSarabun-Italic.ttf',
            bolditalics: 'THSarabun-Bold-Italic.ttf'
        },
        Roboto: {
            normal: 'Roboto-Regular.ttf',
            bold: 'Roboto-Medium.ttf',
            italics: 'Roboto-Italic.ttf',
            bolditalics: 'Roboto-Italic.ttf'
        }
    }


    var table = $('#example').DataTable({
        dom: 'fBrtpi',
        language: {
            processing: 'กำลังโหลด...',
            paginate: {
                previous: "ก่อนหน้า",
                next: "ถัดไป"
            },
            info: "แสดงทั้งหมด _TOTAL_ รายการ",
            infoEmpty :      "แสดงทั้งหมด 0 รายการ",
            infoFiltered: "(กรองจากรายการทั้งหมด _MAX_ รายการ)",
            search: '<i class="search icon"></i> ค้นหา :',
            emptyTable: '<i class="server icon"></i> ไม่มีข้อมูล',
        },
        serverSide: true,
        paging: false,
        processing: true,
        scrollX: true,
        scrollY: '60vh',
        scrollCollapse: true,
        ajax: '/teacher/student/getall',
        columns: [
            {
                data: 'id',
                searchable: false,
                orderable: false,
                defaultContent: ''
            },
            { data: 'std_id', name: 'std_id' },
            { data: 'f_name', name: 'f_name' },
            { data: 'l_name', name: 'l_name' },
            { data: 'tel', name: 'tel' },
            { data: 'address', name: 'address' },
            { data: 'major', name: 'major' },
            { data: 'faculty', name: 'faculty' },
        ],
        buttons: [
            {
                extend: 'pdfHtml5',
                pageSize: 'A4',
                title: 'รายงานการข้อมูลนักศึกษา',
                text: '<i class="print icon"></i>ปริ้นรายงาน',
                footer: false,
                //message:'message',//ถ้าใส่ message เปลี่ยน array เป็น 2 ไม่ใส่เป็น 1
                messageTop: 'วันที่ออกรายงาน ' + new Date().getDate() + '/' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '/' + ("0" + (new Date().getFullYear() + 543)).slice(-2),
                filename: 'Student Report',
                exportOptions: {
                    modifier: {
                        page: 'current',
                    }
                },
                customize: function (doc) {
                    console.log("DATA ==== ", doc.content)
                    doc.defaultStyle = {
                        font: 'THSarabunNew'
                    }
                    doc.content[0].fontSize = 22
                    doc.content[1].fontSize = 18
                    var body_data = doc.content[2].table.body
                    body_data.forEach(tr => {
                        tr.forEach(td => {
                            td.alignment = 'center'
                            td.fontSize = 8
                        });
                    });
                    doc.content[2].table.widths = [
                        '5%',
                        '10%',

                        '15%',
                        '15%',
                        '15%',
                        '10%',

                        '20%',// จัดหัว column
                        '15%',// จัดหัว column
                    ]
                    doc.content[2].margin = [0, 0, 0, 0] //left, top, right, bottom
                    doc.content[2].table.body[0].forEach(header => {
                        header.fillColor = '#ff0000'
                    });
                },
            },
            {
                text: '<i class="sync icon"></i> รีเฟรช',
                action: function (e, dt, node, config) {
                    table.ajax.reload();
                }
            },
        ]
    });
    table.buttons().container()
        .appendTo($('#example_wrapper div.eight.column:eq(0)', table.table().container()));
    table.on('order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
            table.cell(cell).invalidate('dom');
        });
    }).draw();

    const fac = await fetch('/teacher/faculty/getall')
        .then(response => {
            return response.json()
        }).then(data => {
            return data.data
        })

    let p_fac = $('#s_fac');

    fac.forEach(e => {
        p_fac.append($("<option></option>")
            .attr("value", e.fac_id)
            .text(e.name));
    });

    p_fac.on('change', async function () {
        if (!p_fac.val()) return
        console.log('click', p_fac.val());
        let major = await fetch('/teacher/major/get/' + p_fac.val())
            .then(response => {
                return response.json()
            }).then(data => {
                return data.data
            })

        let p_m = $('#s_m')

        p_m.empty()
        p_m.dropdown('clear')
        console.log('empty');
        major.forEach(e => {
            p_m.append($("<option></option>")
                .attr("value", e.m_id)
                .text(e.name));
        });
        $('#s_m').dropdown('clear');

    })

    document.getElementById('search')
        .addEventListener('click', () => {
            const m = document.getElementById('s_m').value
            const f = document.getElementById('s_fac').value
            const yn = document.getElementById('s_yn').value
            if (!m && !f && !yn) {
                table.ajax.url('/teacher/student/getall').load()
            }
            if (m) {
                const url = '/teacher/student/get/' + m + '/null/' + (yn ? yn : 'null')
                table.ajax.url(url).load()
                console.log(url);
                return
            }
            if (f) {
                const url = '/teacher/student/get/null/' + f + '/' + (yn ? yn : 'null')
                table.ajax.url(url).load()
                console.log(url);
                return
            }

            if (!f && !m && yn) {
                const url = '/teacher/student/get/null/null/' + yn
                table.ajax.url(url).load()
                console.log(url);
                return
            }
        })

    document.getElementById('clear')
        .addEventListener('click', () => {
            $('#s_m').empty()
            $('#s_m').dropdown('clear')
            $('#s_yn').dropdown('clear')
            $('#s_fac').dropdown('clear')
        })

    const yn = await fetch('/teacher/course_regist/getyn')
        .then(response => {
            return response.json()
        })
    yn.forEach(e => {
        $('#s_yn').append($("<option></option>")
            .attr("value", e.year)
            .text(e.year));
    });
});