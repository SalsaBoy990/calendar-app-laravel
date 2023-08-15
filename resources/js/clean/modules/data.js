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
            if (this.darkMode === true) {
                document.getElementById('light-logo').style.display = 'none';
                document.getElementById('dark-logo').style.display = 'block';
            } else {
                document.getElementById('light-logo').style.display = 'block';
                document.getElementById('dark-logo').style.display = 'none';
            }


            this.$watch('darkMode', (val) => {
                localStorage.setItem('darkMode', val);
                if (val === true) {
                    document.getElementById('light-logo').style.display = 'none';
                    document.getElementById('dark-logo').style.display = 'block';
                } else {
                    document.getElementById('light-logo').style.display = 'block';
                    document.getElementById('dark-logo').style.display = 'none';
                }
            });
        },
    };
}
