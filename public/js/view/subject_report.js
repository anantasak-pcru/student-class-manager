$(document).ready(function () {

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
        paging: false,
        processing: true,
        scrollX: true,
        scrollY: '60vh',
        scrollCollapse: true,
        ajax: '/admin/subject/getall',
        columns: [
            {
                data: 'id',
                searchable: false,
                orderable: false,
                defaultContent: ''
            },
            { data: 's_id', name: 's_id' },
            { data: 'name', name: 'name' },
            { data: 'full_name', name: 'full_name' },
        ],
        buttons: [
            {
                extend: 'pdfHtml5',
                pageSize: 'A4',
                title: 'รายงานข้อมูลรายวิชา',
                text: '<i class="print icon"></i>ปริ้นรายงาน',
                footer: false,
                messageTop: 'วันที่ออกรายงาน ' + new Date().getDate() + '/' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '/' + ("0" + (new Date().getFullYear() + 543)).slice(-2),
                filename: 'Subject Report',
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
                            td.fontSize = 12
                        });
                    });
                    doc.content[2].table.widths = [
                        '20%',
                        '20%',
                        '30%',
                        '30%',
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
});
