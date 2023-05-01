/*
* Slideshow actions
*
* */
export function sliderData() {

    return {
        slideIndex: 1,
        slideItemClass: 'slide-item',
        slideDotsClass: 'slide-dots',

        init() {
            this.slideIndex = 1;
            this.showSlides(this.slideIndex);
        },

        switchSlide(n) {
            this.slideIndex = this.slideIndex + n;
            this.showSlides(this.slideIndex);
        },

        currentSlide(n) {
            this.showSlides(this.slideIndex = n);
        },

        showSlides(n) {
            const x = document.getElementsByClassName(this.slideItemClass);
            const dots = document.getElementsByClassName(this.slideDotsClass);
            if (n > x.length) {
                this.slideIndex = 1;
            }
            if (n < 1) {
                this.slideIndex = x.length;
            }

            for (let i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            for (let i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" white", "");
            }
            x[this.slideIndex - 1].style.display = "block";
            dots[this.slideIndex - 1].className += " white";
        },

    }
}
