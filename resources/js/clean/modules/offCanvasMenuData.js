/*
* Modal action
*
* */
export function offCanvasMenuData() {
    return {
        sidenav: false,
        clickedOutside: false,

        toggleOffcanvasMenu() {
            if (this.sidenav === true) {
                this.closeOffcanvasMenu();
            } else {
                this.openOffcanvasMenu();
            }
        },

        openOffcanvasMenu() {
            const defaultNavbar = document.getElementById("main-menu");
            const defaultNavbarClone = defaultNavbar.cloneNode(true);

            // delete previous cloned content
            const mobileNav = document.getElementById("mobile-menu");
            mobileNav.innerText = '';
            mobileNav.appendChild(defaultNavbarClone);
            document.getElementById("main-menu-offcanvas").style.width = "300px";

            this.sidenav = true;
            this.clickedOutside = false;
        },

        closeOffcanvasMenu() {
            document.getElementById("main-menu-offcanvas").style.width = "0";
            document.getElementById("mobile-menu").innerText = '';

            this.sidenav = false;
            this.clickedOutside = false;
        },

        closeOnOutsideClick() {
            // do not close on initial outside (of the sidebar div) click on the hamburger btn
            if (this.sidenav === true && this.clickedOutside === true) {
                this.closeOffcanvasMenu();
            } else {
                this.clickedOutside = true;
            }

        }
    };
}
