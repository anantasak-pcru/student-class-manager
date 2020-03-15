document.addEventListener('DOMContentLoaded', async () => {

    const id = document.getElementsByTagName('META').id.content

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
        },
        eventSources: [],
        eventOrder: "id",
    });

    calendar.render();

    let s_subject = document.getElementById('s_subject')
    const subject = await fetch('/student/getsubject/' + id)
        .then(response => {
            return response.json()
        })
    subject.forEach(s => {
        let option = document.createElement('option')
        option.value = s.cr_id
        option.text = s.full_subject_name
        s_subject.appendChild(option)
    });

    s_subject.addEventListener('change', () => {

        const cr_id = s_subject.value
        
        const calendar_url = '/student/getinfo/' + cr_id

        console.log(calendar_url);

        const event_source = calendar.getEventSourceById('1')
        if (event_source) {
            event_source.remove()
        }
        calendar.addEventSource(
            {
                id: '1',
                url: calendar_url,
                method: 'GET',
                failure: function () {
                    alert('there was an error while fetching events!');
                },
            }
        )
        calendar.refetchEvents()
    })
});