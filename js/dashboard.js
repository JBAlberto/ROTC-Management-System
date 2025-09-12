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
