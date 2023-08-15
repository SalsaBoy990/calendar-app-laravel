const toggler = document.getElementsByClassName("caret");
if (toggler) {
    let i;
    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function () {
            this.parentElement.parentElement.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
            this.parentElement.parentElement.classList.toggle("active-list-item");
        });
    }
}


// Offcanvas menu
/* Set the width of the side navigation to 250px */
/*
function openOffcanvasMenu() {
    const defaultNavbar = document.getElementById("main-menu");
    const defaultNavbarClone = defaultNavbar.cloneNode(true);

    // delete previous cloned content
    const mobileNav = document.getElementById("mobile-menu");
    mobileNav.innerText = '';
    mobileNav.appendChild(defaultNavbarClone);
    document.getElementById("main-menu-offcanvas").style.width = "300px";
}

/!* Set the width of the side navigation to 0, delete cloned menu *!/
function closeOffcanvasMenu() {
    document.getElementById("main-menu-offcanvas").style.width = "0";
    document.getElementById("mobile-nav").innerText = '';
}

// Sidebar close button
document.getElementById('main-menu-close-button').addEventListener('click', closeOffcanvasMenu);

// Sidebar open menu
document.getElementById("main-menu-offcanvas-toggle").addEventListener('click', openOffcanvasMenu);
// Offcanvas menu END
*/


/* Logout functions, events */
function triggerLogout(id) {
    event.preventDefault();
    document.getElementById(id).submit();
}


const logoutAdminHeaderBtn = document.getElementById('logout-form-header-trigger');
if (logoutAdminHeaderBtn) {
    logoutAdminHeaderBtn.addEventListener('click', function () {
        triggerLogout('logout-form-header');
    });
}


const logoutAdminSidebarBtn = document.getElementById('logout-form-admin-sidebar-trigger');
if (logoutAdminSidebarBtn) {
    logoutAdminSidebarBtn.addEventListener('click', function () {
        triggerLogout('logout-form-admin-sidebar');
    });
}


const logoutAdminDashboardBtn = document.getElementById('logout-form-dashboard-trigger');
if (logoutAdminDashboardBtn) {
    logoutAdminDashboardBtn.addEventListener('click', function () {
        triggerLogout('logout-form-dashboard');
    });
}


const logoutUserDropdownBtn = document.getElementById('user-dropdown-logout-trigger');
if (logoutUserDropdownBtn) {
    logoutUserDropdownBtn.addEventListener('click', function () {
        triggerLogout('user-dropdown-logout');
    });
}
