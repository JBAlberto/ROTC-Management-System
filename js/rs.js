let events = [];
let currentDate = new Date();

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// 🔹 Fetch events from backend
function fetchEvents() {
    fetch('rsback.php?action=list')
        .then(res => res.json())
        .then(data => {
            if (data.success === false) {
                console.error("Backend error:", data.error);
                return;
            }
            events = data;
            renderCalendar();
        })
        .catch(err => console.error("Fetch error:", err));
}

// 🔹 Render calendar
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
        const dayEvents = events.filter(e => {
            let cleanDate = e.event_date.split(" ")[0]; // handles DATE or DATETIME
            return cleanDate === dateStr;
        });

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

// 🔹 Change month
function changeMonth(delta) {
    currentDate.setMonth(currentDate.getMonth() + delta);
    renderCalendar();
}

// 🔹 Load event into form
function loadEvent(event) {
    document.getElementById('event-id').value = event.id;
    document.getElementById('title').value = event.title;
    document.getElementById('event_date').value = event.event_date.slice(0,10);
    document.getElementById('description').value = event.description;
    document.getElementById('delete-btn').style.display = 'inline-block';
}


// 🔹 Clear form
function clearForm() {
    document.getElementById('event-id').value = '';
    document.getElementById('title').value = '';
    document.getElementById('event_date').value = '';
    document.getElementById('description').value = '';
    document.getElementById('delete-btn').style.display = 'none';
}


// 🔹 Save event (add/edit)
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
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                clearForm();
                fetchEvents();
            } else {
                alert("Error saving event: " + data.error);
                console.error("Save error:", data.error);
            }
        })
        .catch(err => console.error("Request error:", err));
});

document.addEventListener('DOMContentLoaded', fetchEvents);
document.getElementById('delete-btn').addEventListener('click', function() {
    const id = document.getElementById('event-id').value;
    if(!id) return;

    if(confirm("Are you sure you want to delete this event?")) {
        fetch('rsback.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            console.log("Deleted event:", data);
            clearForm();
            fetchEvents(); // Refresh calendar
        });
    }
});
