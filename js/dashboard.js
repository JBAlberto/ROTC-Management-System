// Sidebar toggle
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebar.style.width === '60px') {
        sidebar.style.width = '220px';
        mainContent.style.marginLeft = '220px';
        mainContent.style.width = 'calc(100% - 220px)';
    } else {
        sidebar.style.width = '60px';
        mainContent.style.marginLeft = '60px';
        mainContent.style.width = 'calc(100% - 60px)';
    }
}

// Fetch upcoming events
document.addEventListener("DOMContentLoaded", function() {
    fetchUpcomingEvents();
});

function fetchUpcomingEvents() {
    fetch("rsback.php?action=upcoming")
        .then(res => res.json())
        .then(data => {
            console.log("Fetched upcoming events:", data); // DEBUG
            const container = document.getElementById("upcoming-events");
            container.innerHTML = "";

            if (!data || data.length === 0) {
                container.innerHTML = "<p>No upcoming activities.</p>";
                return;
            }

            data.forEach(ev => {
                const div = document.createElement("div");
                div.classList.add("event-item");
                div.innerHTML = `
                    <strong>${ev.title}</strong><br>
                    <small>${ev.event_date}</small><br>
                    <span>${ev.description || ""}</span>
                `;
                container.appendChild(div);
            });
        })
        .catch(err => {
            console.error("Error loading upcoming events:", err);
            const container = document.getElementById("upcoming-events");
            container.innerHTML = "<p style='color:red;'>Failed to load events.</p>";
        });
}
