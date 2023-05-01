/*
*  Global dark-mode and scroll-to-top actions
*
* */
export function data() {
    return {
        scrollTop: 0,
        darkMode: localStorage.getItem('darkMode') === 'true',

        toggleDarkMode() {
            this.darkMode = !this.darkMode;
        },

        isDarkModeOn() {
            return this.darkMode === true;
        },

        setScrollToTop() {
            this.scrollTop = document.body.scrollTop;
        },

        scrollToTop() {
            document.body.scrollTop = 0;

        },

        init() {
            this.$watch('darkMode', val => localStorage.setItem('darkMode', val));
        },
    };
}
