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
            const lightLogo = document.getElementById('light-logo');
            const darkLogo = document.getElementById('dark-logo');
            if (this.darkMode === true) {
                if (lightLogo) {
                    lightLogo.style.display = 'none';
                }

                if (darkLogo) {
                    darkLogo.style.display = 'block';
                }
            } else {
                if (lightLogo) {
                    lightLogo.style.display = 'block';
                }

                if (darkLogo) {
                    darkLogo.style.display = 'none';
                }
            }


            this.$watch('darkMode', (val) => {
                localStorage.setItem('darkMode', val);
                const lightLogo = document.getElementById('light-logo');
                const darkLogo = document.getElementById('dark-logo');

                if (val === true) {
                    if (lightLogo) {
                        lightLogo.style.display = 'none';
                    }

                    if (darkLogo) {
                        darkLogo.style.display = 'block';
                    }
                } else {
                    if (lightLogo) {
                        lightLogo.style.display = 'block';
                    }

                    if (darkLogo) {
                        darkLogo.style.display = 'none';
                    }
                }
            });
        },
    };
}
