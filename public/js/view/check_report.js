document.addEventListener('DOMContentLoaded', async () => {

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


  await $("#year").yearpicker({
    year: new Date().getFullYear() + 543,
    startYear: 2550,
    endYear: 2660
  });

  document.getElementById('year').value = ''
  document.getElementById('s_date').value = ''
  $('#s_term').dropdown('clear');

  const calendarEl = document.getElementById('calendar');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: ['interaction', 'dayGrid', 'timeGrid'],
    selectable: true,
    unselectAuto: false,
    locale: 'th',
    startParam: 'start',
    endParam: 'end',
    timeZoneParam: 'time',
    buttonText: {
      today: 'วันนี้'
    },
    dateClick: function (info) {
      const current = info.dateStr.split("-")
      const day = current[2]
      const year = parseInt(current[0]) + 543
      const month = current[1]
      console.log(day, month, year);
      document.getElementById('s_date').value = day + '-' + month + '-' + year
      loadStudent(inclass)
    },
    eventSources: [],
    eventOrder: "id",
  });

  calendar.render();


  const s_subject = document.getElementById('s_subject')
  const id = document.getElementsByTagName('META').id.content
  const _token = document.getElementsByTagName('META')._token.content

  const subject = await fetch('/teacher/getsubject/' + id)
    .then(response => {
      return response.json()
    })

  console.log(subject);
  subject.forEach(e => {
    let option = document.createElement('option')
    option.value = e.s_id
    option.text = e.name
    s_subject.appendChild(option)
  });

  $('#inclass tbody').on('click', 'tr', function () {
    console.log(inclass.row(this).data());
  });

  var inclass = $('#inclass').DataTable({
    dom: 'Bflrtpi',
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
    scrollY: '60vh',
    ajax: '/teacher/getdummy',
    buttons: [
      {
        extend: 'pdfHtml5',
        pageSize: 'A4',
        title: 'รายงานการเข้าชั้นเรียน',
        text: '<i class="print icon"></i>ปริ้นรายงาน',
        footer: 'false',
        //message:'message',//ถ้าใส่ message เปลี่ยน array เป็น 2 ไม่ใส่เป็น 1
        messageTop: 'วันที่ออกรายงาน ' + new Date().getDate() + '/' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '/' + ("0" + (new Date().getFullYear() + 543)).slice(-2),
        filename: 'Report',
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
              td.fontSize = 16
            });
          });
          doc.content[2].table.widths = [
            '10%',

            '15%',
            '15%',
            '15%',
            '20%',

            '10%',// จัดหัว column
            '15%',// จัดหัว column
          ]
          doc.content[2].margin = [0, 0, 0, 0] //left, top, right, bottom
          doc.content[2].table.body[0].forEach(header => {
            header.fillColor = '#ff0000'
          });
        },
      }
    ],
    columns: [
      {
        data: 'id',
        searchable: false,
        orderable: false,
        defaultContent: ''
      },
      { data: 'std_id', name: 'std_id', },
      { data: 'f_name', name: 'f_name', },
      { data: 'l_name', name: 'l_name', },
      { data: 'name', name: 'name' },
      {
        data: 'status',
        name: 'status',
        className: "ui center aligned",
        searchable: false,
        orderable: false,
        render: function (data, type, row, meta) {
          let a = ''
          switch (row.status) {
            case '0':
              a = '<h5 style="color:green"><i class="check icon"></i>เข้าเรียน</h5>'
              break;
            case '1':
              a = '<h5 style="color:red"><i class="times icon"></i>ขาดเรียน</h5>'
              break;
            case '2':
              a = '<h5 style="color:orange"><i class="exclamation triangle icon"></i>ลา</h5>'
              break;
            default:
              a = '<h5>-</h5>';
              break;
          }
          return a
        }
      },
      {
        searchable: false,
        orderable: false,
        data: 'detail',
        name: 'detail',
        className: "ui center aligned",
        render: function (data, type, row, meta) {
          if (row.detail != null) return data
          return '-'
        }
      },
    ],
  });
  inclass.on('order.dt search.dt', function () {
    inclass.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
      cell.innerHTML = i + 1;
      inclass.cell(cell).invalidate('dom');
    });
  }).draw();
  document.getElementById('search')
    .addEventListener('click', () => {
      search(calendar, _token)
    })
  document.getElementById('clear')
    .addEventListener('click', () => {
      document.getElementById('year').value = ''
      document.getElementById('s_date').value = ''
      $('#s_term').dropdown('clear');
      $('#s_subject').dropdown('clear');
    })
});

async function search(calendar, _token) {
  const s = document.getElementById('s_subject').value
  const y = document.getElementById('year').value
  const t = document.getElementById('s_term').value

  if (!s || !y || !t) return

  Swal.fire({
    title: 'กำลังโหลด',
    html: 'loading...',
    onBeforeOpen: () => {
      Swal.showLoading()
    },
    onClose: () => {

    }
  })

  const calendar_url = '/teacher/carlendar/' + y + '/' + s + '/' + t

  console.log(calendar_url);

  const event_source = calendar.getEventSourceById('1')
  if (event_source) {
    event_source.remove()
  }
  await calendar.addEventSource(
    {
      id: '1',
      url: '/teacher/carlendar/',
      method: 'POST',
      extraParams: {
        _token: _token,
        year: y,
        s_id: s,
        term: t,
      },
      failure: function () {
        alert('there was an error while fetching events!');
      },
    }
  )
  await calendar.refetchEvents()
  Swal.close();
}

function loadStudent(inclass) {
  const s = document.getElementById('s_subject').value
  const y = document.getElementById('year').value
  const t = document.getElementById('s_term').value
  const d = document.getElementById('s_date').value
  if (!y || !s || !t || !d) return
  const url = '/teacher/getdata/' + d + '/' + s + '/' + y + '/' + t
  console.log(url);
  inclass.ajax.url(url).load()
}