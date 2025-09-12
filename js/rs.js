let events = []
let currentDate = new Date();
events = [
  {
    id: 999,
    title: "Hardcoded Test",
    event_date: "2025-08-02",
    description: "Test event"
  }
];

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

function fetchEvents() {
    fetch('rsback.php?action=list')
        .then(res => res.json())
        .then(data => {
            console.log("Fetched events:", data);
            events = data;
            renderCalendar();
        })
        .catch(err => console.error("Fetch error:", err));
}

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    document.getElementById('current-month').textContent =
        currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });

    const header = document.getElementById('calendar-header');
    header.innerHTML = '';
    daysOfWeek.forEach(day => {
        const cell = document.createElement('div');
        cell.className = 'calendar-header-day';
        cell.textContent = day;
        header.appendChild(cell);
    });

    const calendar = document.getElementById('calendar');
    calendar.innerHTML = '';

    for (let i = 0; i < firstDay; i++) {
        const empty = document.createElement('div');
        empty.className = 'calendar-day empty';
        calendar.appendChild(empty);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = events.filter(e => e.event_date.slice(0, 10) === dateStr);

        const cell = document.createElement('div');
        cell.className = 'calendar-day';
        cell.innerHTML = `<strong>${day}</strong>`;

        dayEvents.forEach(event => {
            const eDiv = document.createElement('div');
            eDiv.className = 'event';
            eDiv.textContent = event.title;
            eDiv.onclick = () => loadEvent(event);
            cell.appendChild(eDiv);
        });

        calendar.appendChild(cell);
    }
}

function changeMonth(delta) {
    currentDate.setMonth(currentDate.getMonth() + delta);
    renderCalendar();
}

function loadEvent(event) {
    document.getElementById('event-id').value = event.id;
    document.getElementById('title').value = event.title;
    document.getElementById('event_date').value = event.event_date.slice(0, 10);
    document.getElementById('description').value = event.description;
}

function clearForm() {
    document.getElementById('event-id').value = '';
    document.getElementById('title').value = '';
    document.getElementById('event_date').value = '';
    document.getElementById('description').value = '';
}

document.getElementById('event-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const id = document.getElementById('event-id').value;
    const title = document.getElementById('title').value;
    const event_date = document.getElementById('event_date').value;
    const description = document.getElementById('description').value;

    const payload = { title, event_date, description };
    let url = 'rsback.php?action=add';

    if (id) {
        payload.id = id;
        url = 'rsback.php?action=edit';
    }

    fetch(url, {
        method: 'POST',
        body: JSON.stringify(payload),
        headers: { 'Content-Type': 'application/json' }
    }).then(() => {
        clearForm();
        fetchEvents();
    });
});

document.addEventListener('DOMContentLoaded', fetchEvents);
