/*
*  Lightbox (Modal and Slideshow)
*
* */
export function lightboxData() {

    return {
        openLighbox: false,
        slideIndexLightbox: 1,
        lightboxItemClass: "",
        dotsClass: "",
        captionId: "",


        init() {
            this.openLighbox = false;
            this.slideIndexLightbox = 1;
            this.lightboxItemClass = "lightbox-item";
            this.dotsClass = "lightbox-dots";
            this.captionId = "lightbox-caption-id";
        },

        openLightbox() {
            this.openLighbox = true;
        },

        closeLightbox() {
            this.openLighbox = false;
        },


        stepLightbox(n) {
            this.showLightboxItems(this.slideIndexLightbox += n);
        },

        currentLightbox(n) {
            this.showLightboxItems(this.slideIndexLightbox = n);
        },

        showLightboxItems(n) {
            const x = document.getElementsByClassName(this.lightboxItemClass);
            const dots = document.getElementsByClassName(this.dotsClass);
            const captionText = document.getElementById(this.captionId);
            if (n > x.length) {
                this.slideIndexLightbox = 1;
            }
            if (n < 1) {
                this.slideIndexLightbox = x.length;
            }
            for (let i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            for (let i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" opacity-off", "");

            }
            x[this.slideIndexLightbox - 1].style.display = "block";
            dots[this.slideIndexLightbox - 1].className += " opacity-off";
            captionText.innerHTML = dots[this.slideIndexLightbox - 1].alt;
        }

    };
}

