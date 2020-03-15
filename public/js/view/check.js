$(".ui.dropdown").dropdown({
  allowCategorySelection: true,
  transition: "fade up",
  context: 'sidebar',
  on: "click"
});

document.getElementById('menu')
  .addEventListener('click', () => {
    $('.ui.sidebar').sidebar('toggle')
  })

$('.ui.accordion').accordion({
  selector: {

  }
});

document.addEventListener('DOMContentLoaded', async () => {

  console.log('Year', new Date().getFullYear() + 543);


  await $("#year").yearpicker({
    year: new Date().getFullYear() + 543,
    startYear: 2550,
    endYear: 2660
  });

  document.getElementById('year').value = ''
  document.getElementById('s_date').value = ''
  $('#s_term').dropdown('clear');

  document.getElementById('clear').addEventListener('click', () => {
    document.getElementById('year').value = ''
    document.getElementById('s_date').value = ''
    $('#s_term').dropdown('clear');
    $('#s_subject').dropdown('clear');
  })

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
    },
    eventSources: [],
    eventOrder: "id",
  });

  calendar.render();


  const s_subject = document.getElementById('s_subject')
  const id = document.getElementsByTagName('META').id.content
  const _token = document.getElementsByTagName('META')._token.content
  console.log(id);
  console.log(_token);
  const subject = await fetch('/teacher/getsubject/' + id)
    .then(response => {
      return response.json()
    })
  console.log(subject);
  subject.forEach(e => {
    let option = document.createElement('option')
    option.value = e.s_id
    console.log(e.s_id);
    
    option.text = e.name
    s_subject.appendChild(option)
  });

  $('#inclass tbody').on('click', 'tr', function () {
    console.log(inclass.row(this).data());
  });

  var inclass = $('#inclass').DataTable({
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
    scrollY: '60vh',
    ajax: '/teacher/getdummy',
    columns: [
      {
        data: null,
        searchable: false,
        orderable: false,
      },
      { data: 'std_id', name: 'std_id', },
      { data: 'f_name', name: 'f_name', },
      { data: 'l_name', name: 'l_name', },
      { data: 'name', name: 'name' },
      {
        data: 'status',
        name: 'status',
        className: "ui center aligned",
        //        orderable: false,
        searchable: false,
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
        data: 'status',
        name: 'status',
        width: '300px',
        className: "ui center aligned",
        render: function (data, type, row, meta) {
          //const id = Math.floor(Math.random() * 9999);
          let a, b, c = ''
          switch (row.status) {
            case '0':
              a = 'checked'
              break;
            case '1':
              b = 'checked'
              break;
            case '2':
              c = 'checked'
              break;
            default:
              break;
          }
          let btn =
            '<div style="width:300px">' +
            '<button style="margin-top:10px" class="ui mini compact green button">' +
            '<div class="ui checkbox">' +
            '<input name="pressent" ' + a + ' type="checkbox" name="example">' +
            '<label style="color:white">มา</label>' +
            '</div>' +
            '</button>' +
            '<button style="margin-top:10px" class="ui mini compact red button">' +
            '<div class="ui checkbox">' +
            '<input name="absent" ' + b + ' type="checkbox" name="example">' +
            '<label style="color:white">ขาด</label>' +
            '</div>' +
            '</button>' +
            '<button style="margin-top:10px" class="ui mini compact yellow button">' +
            '<div class="ui checkbox">' +
            '<input name="pass" ' + c + ' type="checkbox" name="example">' +
            '<label style="color:white">ลา</label>' +
            '</div>' +
            '</button>' +
            '<button id="detail" style="margin-top:10px" class="ui small compact purple button">' +
            '<span><i class="pen square icon"></i></span>' +
            '</button>' +
            '</div>'
          return btn
        }
      },
    ],
    //order: [[1, 'asc']]
  });
  inclass.on('order.dt search.dt', function () {
    inclass.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
      cell.innerHTML = i + 1;
      inclass.cell(cell).invalidate('dom');
    });
  }).draw();
  inclass.columns.adjust().draw();
  document.getElementById('search')
    .addEventListener('click', () => {
      search(inclass, calendar, _token)
    })


  document.getElementById('save')
    .addEventListener('click', async () => {
      //console.log(inclass.rows().data());
      console.log(calendar.getDate());

      let i = 0
      const data = inclass.rows().data().toArray()
      console.log(data);

      var data_map = []
      // Loop ค่าถุกเเถว จาก Table
      inclass.rows().every(function (index, element) {
        var row = $(this.node())
        var statusElement = row.find('input').eq(6);
        const ps = statusElement.prevObject[0].checked
        const as = statusElement.prevObject[1].checked
        const pss = statusElement.prevObject[2].checked
        var stat = getStat(ps, as, pss)
        data_map.push({
          chk_id: data[i].chk_id ? data[i].chk_id : null,
          cr_id: data[i].cr_id ? data[i].cr_id : null,
          std_id: data[i].std_id,
          date: document.getElementById('s_date').value,
          status: stat
        })
        i++
      })

      // Post Data
      console.log(data_map.toString());

      const result = await fetch('/teacher/check/insert', {
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "Accept": "application/json; charset=utf-8",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-Token": _token,
        },
        method: 'post',
        credentials: "same-origin",
        body: JSON.stringify({
          _token: _token,
          data: data_map,
        }),
      }).then(response => {
        return response.json()
      }).then(data => {
        console.log('success', data);
      }).catch(e => {
        console.log('Error', e);
      })
      // inclass.ajax.reload()
      // calendar.refetchEvents()
      search(inclass, calendar, _token)
    })

  $('#inclass tbody').on('click', '#detail', async function () {
    const data = inclass.row($(this).parents('tr')).data();
    const y = document.getElementById('year').value

    console.log(data);
    Swal.fire({
      title: "รายละเอียด",
      input: 'textarea',
      inputPlaceholder: 'กรุณาป้อนรายละเอียด...',
      inputValue: data.detail ? data.detail : null,
      inputAttributes: {
        'aria-label': 'Type your message here'
      },
      showCancelButton: true
    }).then(async (result) => {
      if (result.value) {

        const data_map = {
          chk_id: data.chk_id ? data.chk_id : null,
          cr_id: data.chk_id ? data.chk_id : null,
          detail: result.value,
          date: y,
        }

        console.log(data_map)

        await fetch('/teacher/check/updatedetail', {
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "Accept": "application/json; charset=utf-8",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": _token,
          },
          method: 'post',
          credentials: "same-origin",
          body: JSON.stringify(data_map),
        }).then(response => {
          return response.json()
        }).then(data => {
          console.log('success', data);
          inclass.ajax.reload()
        }).catch(e => {
          console.log('Error', e);
        })


      }

    })
  });

});
// End onload


function getStat(ps, as, pss) {
  // Return ค่าเเรกที่เจอนับจาก เข้าเรียน ขาดเรียน ลา ไม่เจอค่า Return null
  if (ps) return '0'
  if (as) return '1'
  if (pss) return '2'
  else return null
}

function search(inclass, calendar, _token) {
  const s = document.getElementById('s_subject').value
  const y = document.getElementById('year').value
  const t = document.getElementById('s_term').value
  const d = document.getElementById('s_date').value

  if (!y || !s || !t || !d) {
    Swal.fire('Error', 'error 90005', 'error')
    return
  }
  const url = '/teacher/getdata/' + d + '/' + s + '/' + y + '/' + t
  console.log(url);
  inclass.ajax.url(url).load()


  const calendar_url = '/teacher/carlendar/' + y + '/' + s + '/' + t

  console.log(calendar_url);

  const event_source = calendar.getEventSourceById('1')
  if (event_source) {
    event_source.remove()
  }
  calendar.addEventSource(
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
      failure: function (e) {
        alert('there was an error while fetching events!', e);
      },
    }
  )
  calendar.refetchEvents()
}