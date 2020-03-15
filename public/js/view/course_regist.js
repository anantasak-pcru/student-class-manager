$(document).ready(async function () {

    console.log('Year', new Date().getFullYear() + 543);


    await $("#s_y").yearpicker({
        year: new Date().getFullYear() + 543,
        startYear: 2550,
        endYear: 2660
    });

    $("#s_y").val('')
    $('#s_term').dropdown('clear');

    $('#student tbody').on('click', 'tr', function () {
        $(this).toggleClass('active');
    });

    $('#inclass tbody').on('click', 'tr', function () {
        $(this).toggleClass('active');
    });

    document.getElementById('clear_subject').addEventListener('click', () => {
        $('#s_s').dropdown('clear');
        $('#s_y').val('');
        $('#s_term').dropdown('clear');
    })

    document.getElementById('clear_student').addEventListener('click', () => {
        $('#s_fac').dropdown('clear');
        $('#s_m').dropdown('clear');
        $('#s_yn').dropdown('clear');
    })

    $('#remove').click(function () {
        let selected_value = inclass.rows('.active').data().toArray()
        console.log(selected_value);
        let data = []
        console.log('type', typeof selected_value);
        console.log(selected_value);
        if (selected_value.length <= 0) return
        selected_value.forEach(e => {
            data.push(e.cr_id)
        });
        Swal.fire({
            icon: 'info',
            title: 'ลบ ' + selected_value.length + ' คนออกจากชั้นเรียน',
            text: '',
            showCancelButton: true,
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.value) {
                fetch('/admin/course_regist/remove', {
                    headers: {
                        "Content-Type": "application/json; charset=utf-8",
                        "Accept": "application/json; charset=utf-8",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": $('meta[name="_token"]').attr('content')
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body: JSON.stringify({
                        val: data,
                    }),
                }).then(response => {
                    return response.json()
                }).then(data => {
                    reload(notinclass, inclass)
                    console.log('success', data);
                }).catch(e => {
                    reload(notinclass, inclass)
                    console.log('Error', e);
                })
            }
        })
    });

    $('#save').click(function () {
        //alert( table.rows('.active').data().length +' row(s) selected' );
        let selected_value = notinclass.rows('.active').data().toArray()
        let data = []
        console.log('type', typeof selected_value);
        console.log(selected_value);
        if (selected_value.length <= 0) return
        selected_value.forEach(e => {
            data.push(e.std_id)
        });
        Swal.fire({
            icon: 'info',
            title: 'เพิ่ม ' + selected_value.length + ' ตนเข้าชั้นเรียน',
            text: '',
            showCancelButton: true,
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.value) {
                fetch('/admin/course_regist/insert', {
                    headers: {
                        "Content-Type": "application/json; charset=utf-8",
                        "Accept": "application/json; charset=utf-8",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": $('meta[name="_token"]').attr('content')
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body: JSON.stringify({
                        val: data,
                        s_id: document.getElementById('s_s').value,
                        year: document.getElementById('s_y').value,
                        term: document.getElementById('s_term').value
                    }),
                }).then(response => {
                    return response.json()
                }).then(data => {
                    reload(notinclass, inclass)
                    console.log('success', data);
                }).catch(e => {
                    reload(notinclass, inclass)
                    console.log('Error', e);
                })
            }
        })
    });

    document.getElementById('inclass_selectall').addEventListener('click', () => {
        inclass.rows().every(function (rowIdx, tableLoop, rowLoop) {
            $(this.node()).addClass('active');
        })
    })
    document.getElementById('inclass_disselectall').addEventListener('click', () => {
        inclass.rows().every(function (rowIdx, tableLoop, rowLoop) {
            $(this.node()).removeClass('active');
        })
    })
    document.getElementById('notclass_selectall').addEventListener('click', () => {
        notinclass.rows().every(function (rowIdx, tableLoop, rowLoop) {
            $(this.node()).addClass('active');
        })
    })
    document.getElementById('notclass_disselectall').addEventListener('click', () => {
        notinclass.rows().every(function (rowIdx, tableLoop, rowLoop) {
            $(this.node()).removeClass('active');
        })
    })

    document.getElementById('search').addEventListener('click', () => {
        // console.log('click');
        // table.ajax.url('/admin/admin/getall').load();
        const fac = document.getElementById('s_fac').value
        const major = document.getElementById('s_m').value
        const subject = document.getElementById('s_s').value
        const yn = document.getElementById('s_yn').value
        const year = document.getElementById('s_y').value
        const term = document.getElementById('s_term').value
        if (/*!fac || !major ||*/ !subject /*|| !yn*/ || !year || !term) {
            let msg = ''
            if (!subject) msg += '[วิชา] '
            if (!year) msg += '[ปีการศึกษา] '
            if (!term) msg += '[ภาคเรียน] '
            console.log('error');
            Swal.fire('กรูณาเลือกข้อมูล', msg, 'error')
            return

        }
        // console.log(fac, major, subject, yn, year);
        reload(notinclass, inclass)

    })

    // let p_y = $('#s_y')
    let p_s = $('#s_s')
    let p_yn = $('#s_yn')
    let p_fac = $('#s_fac')
    let p_m = $('#s_m')

    const year = await fetch('/admin/course_regist/getyear')
        .then(response => {
            return response.json()
        })

    const subject = await fetch('/admin/subject/getall')
        .then(response => {
            return response.json()
        }).then(data => {
            return data.data
        })

    const fac = await fetch('/admin/faculty/getall')
        .then(response => {
            return response.json()
        }).then(data => {
            return data.data
        })

    const yn = await fetch('/admin/course_regist/getyn')
        .then(response => {
            return response.json()
        })

    console.log(subject);
    console.log(yn);

    subject.forEach(e => {
        console.log(e.s_id);
        p_s.append($("<option></option>")
            .attr("value", e.s_id)
            .text(e.s_id + ' : ' + e.name));
    });

    fac.forEach(e => {
        p_fac.append($("<option></option>")
            .attr("value", e.fac_id)
            .text(e.name));
    });

    yn.forEach(e => {
        p_yn.append($("<option></option>")
            .attr("value", e.year)
            .text(e.year));
    });

    p_fac.on('change', async function () {
        if (!p_fac.val()) return
        console.log('click', p_fac.val());
        let major = await fetch('/admin/major/get/' + p_fac.val())
            .then(response => {
                return response.json()
            }).then(data => {
                return data.data
            })

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

    let notinclass = $('#student').DataTable({
        dom: 'flrtpi',
        language: {
            processing: 'กำลังโหลด...',
            emptyTable: '<i class="server icon"></i> ไม่มีข้อมูล',
        },
        serverSide: true,
        lengthChange: false,
        info: false,
        processing: true,
        paging: false,
        searching: false,
        scrollX: true,
        scrollY: '40vh',
        scrollCollapse: true,
        ajax: '/admin/course_regist/getdummy',
        columns: [
            {
                data: null,
                searchable: false,
                orderable: false,
            },
            { data: 'std_id', name: 'std_id' },
            { data: 'f_name', name: 'f_name' },
            { data: 'l_name', name: 'l_name' },
            { data: 'name', name: 'name' },
            { data: 'fac_name', name: 'fac_name' },
        ],
        order: [[1, 'asc']]
    });
    notinclass.buttons().container()
        .appendTo($('#example_wrapper div.eight.column:eq(0)', notinclass.table().container()));
    notinclass.on('order.dt search.dt', function () {
        notinclass.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
            notinclass.cell(cell).invalidate('dom');
        });
    }).draw();


    let inclass = $('#inclass').DataTable({
        dom: 'flrtpi',
        language: {
            processing: 'กำลังโหลด...',
            emptyTable: '<i class="server icon"></i> ไม่มีข้อมูล',
        },
        serverSide: true,
        lengthChange: false,
        info: false,
        processing: true,
        paging: false,
        searching: false,
        scrollCollapse: true,
        scrollX: true,
        scrollY: '40vh',
        ajax: '/admin/course_regist/getdummy',
        columns: [
            {
                data: null,
                searchable: false,
                orderable: false,
            },
            { data: 'std_id', name: 'std_id' },
            { data: 'f_name', name: 'f_name' },
            { data: 'l_name', name: 'l_name' },
            { data: 'major_name', name: 'major_name' },
            { data: 'fac_name', name: 'fac_name' },
        ],
        order: [[1, 'asc']]
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
    const fac = document.getElementById('s_fac').value
    const major = document.getElementById('s_m').value
    const subject = document.getElementById('s_s').value
    const yn = document.getElementById('s_yn').value
    const year = document.getElementById('s_y').value
    const term = document.getElementById('s_term').value
    // $m_id,$s_id,$yn,$year,$term
    const data = '/admin/course_regist/getinclass/' + subject + '/' + year + '/' + term
    console.log('url', data);
    return data
}
function getNotInclassUrl() {
    const fac = document.getElementById('s_fac').value ? document.getElementById('s_fac').value : 'null'
    const major = document.getElementById('s_m').value ? document.getElementById('s_m').value : 'null'
    const subject = document.getElementById('s_s').value
    const yn = document.getElementById('s_yn').value ? document.getElementById('s_yn').value : 'null'
    const year = document.getElementById('s_y').value
    const term = document.getElementById('s_term').value
    // $m_id,$s_id,$yn,$year,$term
    console.log('major', major);

    const data = '/admin/course_regist/getnotinclass/' + yn + '/' + fac + '/' + major + '/' + subject + '/' + year + '/' + term
    console.log('url', data);
    return data
}

function reload(notinclass, inclass) {
    const url = getNotInclassUrl()
    notinclass.ajax.url(url).load();
    const url1 = getInclassUrl()
    inclass.ajax.url(url1).load();
}