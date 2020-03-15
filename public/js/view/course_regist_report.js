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

    console.log('Year', new Date().getFullYear() + 543);

    await $("#s_y").yearpicker({
        year: new Date().getFullYear() + 543,
        startYear: 2550,
        endYear: 2660
    });

    document.getElementById('s_y').value = ''
    $('#s_term').dropdown('clear');

    document.getElementById('search').addEventListener('click', () => {
        console.log('click');
        const subject = document.getElementById('s_s').value
        const year = document.getElementById('s_y').value
        const term = document.getElementById('s_term').value
        if (!subject || !year || !term) {
            let msg = ''
            if (!subject) msg += '[วิชา] '
            if (!year) msg += '[ปีการศึกษา] '
            if (!term) msg += '[ภาคเรียน] '
            console.log('error');
            Swal.fire('กรูณาเลือกข้อมูล', msg, 'error')
        } else {
            reload(inclass)
        }

    })

    let p_s = $('#s_s')

    const subject = await fetch('/admin/subject/getall')
        .then(response => {
            return response.json()
        }).then(data => {
            return data.data
        })

    subject.forEach(e => {
        p_s.append($("<option></option>")
            .attr("value", e.s_id)
            .text(e.s_id + ' : ' + e.name));
    });

    let inclass = $('#inclass').DataTable({
        dom: 'fBrtpi',
        language: {
            processing: 'กำลังโหลด...',
            loadingRecords: "กำลังโหลด...",
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
        paging: false,
        processing: true,
        scrollX: true,
        scrollY: '40vh',
        scrollCollapse: true,
        ajax: '/admin/course_regist/getdummy',
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
            { data: 'major_name', name: 'major_name' },
            { data: 'fac_name', name: 'fac_name' },
        ],
        buttons: [
            {
                extend: 'pdfHtml5',
                pageSize: 'A4',
                title: () => {
                    const e = document.getElementById("s_s");
                    const sub = e.options[e.selectedIndex].text;
                    const t = document.getElementById('s_term').value
                    const y = document.getElementById('s_y').value
                    return 'รายงานข้อมูลการลงทะเบียนรายวิชา' +
                        (sub != 'วิชา' && t && y ? ('\n' + sub.replace(':', '') + '  ' + 'ปีการศึกษา ' + t + '/' + y) : '')
                },
                text: '<i class="print icon"></i>ปริ้นรายงาน',
                footer: false,
                //message:'message',//ถ้าใส่ message เปลี่ยน array เป็น 2 ไม่ใส่เป็น 1
                messageTop: 'วันที่ออกรายงาน ' + new Date().getDate() + '/' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '/' + ("0" + (new Date().getFullYear() + 543)).slice(-2),
                filename: 'Admin Report',
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
                    doc.content[0].fontSize = 18
                    doc.content[1].fontSize = 16
                    var body_data = doc.content[2].table.body
                    body_data.forEach(tr => {
                        tr.forEach(td => {
                            td.alignment = 'center'
                            td.fontSize = 10
                        });
                    });
                    doc.content[2].table.widths = [
                        '10%',
                        '10%',
                        '20%',
                        '20%',
                        '20%',// จัดหัว column
                        '20%',// จัดหัว column
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
                    inclass.ajax.reload();
                }
            },
        ]
    });
    inclass.buttons().container()
        .appendTo($('#example_wrapper div.eight.column:eq(0)', inclass.table().container()));
    inclass.on('order.dt search.dt', function () {
        inclass.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
            inclass.cell(cell).invalidate('dom');
        });
    }).draw();

});

function getInclassUrl() {
    const subject = document.getElementById('s_s').value
    const year = document.getElementById('s_y').value
    const term = document.getElementById('s_term').value
    const data = '/admin/course_regist/getinclass/' + subject + '/' + year + '/' + term
    console.log('url', data);
    return data
}

function reload(inclass) {
    const url1 = getInclassUrl()
    inclass.ajax.url(url1).load();
}