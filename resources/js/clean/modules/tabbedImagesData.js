/*
* Tabbed Image Gallery Image Switcher action
*
* */
export function tabbedImagesData() {

    return {
        imageId: '',
        tabbedImageClass: '',
        tabbedImageButtonClass: '',

        init() {
            this.tabbedImageClass = "tabbed-image-gallery-item";
            this.tabbedImageButtonClass = "tabbed-image-gallery-button";

            this.hideAllTabs();
        },

        openTabbedImage(imageId) {
            this.imageId = imageId;
            this.hideAllTabs();

            document.getElementById(this.imageId).style.display = "block";
        },

        hideAllTabs() {
            const x = document.getElementsByClassName(this.tabbedImageClass);
            for (let i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
        },

        hide(event) {
            const btn = event.target;
            if (btn.parentNode) {
                btn.parentNode.style.display = 'none';
            }
        },

        show(event) {
            const btn = event.target;
            if (btn.parentNode) {
                btn.parentNode.style.display = 'block';
            }
        }

    };
}

