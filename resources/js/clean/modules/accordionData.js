/*
*  Accordions Toggle action
*
* */
export function accordionData() {
    return {
        accordionId: '',
        sameClick: false,
        toggleClass: '',
        headerActiveClass: '',
        accordionItemClass: '',

        init() {
            this.accordionId = 'accordionOne';
            this.sameClick = false;
            this.toggleClass = "show";
            this.headerActiveClass = "accordion-button";
            this.accordionItemClass = "accordion-item";
        },

        toggleAccordion(accordionId = '') {

            // if we click on the same button twice, hide the accordion content
            if (this.accordionId === accordionId) {
                this.accordionId = accordionId;
                const x = document.getElementById(this.accordionId);

                // Hide it when shown
                if (this.sameClick === false) {
                    x.classList.remove(this.toggleClass);
                    // remove active state
                    x.previousElementSibling.classList.remove(this.headerActiveClass);

                    // change back icon to plus
                    const icon = x.previousElementSibling.getElementsByTagName('i')[0]
                    if (icon) {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                    }
                    this.sameClick = true;
                } else {
                    // Show it when hidden
                    x.classList.add(this.toggleClass);
                    x.previousElementSibling.classList.add(this.headerActiveClass);
                    // change icon to minus
                    const icon = x.previousElementSibling.getElementsByTagName('i')[0]
                    if (icon) {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                    }
                    this.sameClick = false;
                }

            } else {
                // This is not a same button click, so set it back to false
                this.sameClick = false;
                this.accordionId = accordionId;
                const x = document.getElementById(this.accordionId);

                // Hide all accordion items
                const y = document.getElementsByClassName(this.accordionItemClass);
                for (let i = 0; i < y.length; i++) {
                    const accordionItem = y[i];
                    if (accordionItem.classList.contains(this.toggleClass)) {
                        accordionItem.classList.remove(this.toggleClass);
                    }
                    if (accordionItem.previousElementSibling.classList.contains(this.headerActiveClass)) {
                        accordionItem.previousElementSibling.classList.remove(this.headerActiveClass);
                        // change back icon to plus
                        const icon = accordionItem.previousElementSibling.getElementsByTagName('i')[0]
                        if (icon) {
                            icon.classList.remove('fa-minus');
                            icon.classList.add('fa-plus');
                        }
                    }
                }


                if (x.classList) {
                    x.classList.toggle(this.toggleClass);
                    x.previousElementSibling.classList.toggle(this.headerActiveClass);
                    const icon = x.previousElementSibling.getElementsByTagName('i')[0];

                    // change icon to minus
                    if (x.previousElementSibling.classList.contains(this.headerActiveClass)) {
                        if (icon) {
                            icon.classList.remove('fa-plus');
                            icon.classList.add('fa-minus');
                        }
                    }

                } else {
                    // Fallback for IE9 and earlier
                    const toggleClassString = " " + this.toggleClass;
                    if (x.className.indexOf(this.toggleClass) === -1) {
                        x.className += toggleClassString;
                    } else {
                        x.className = x.className.replace(toggleClassString, "");
                    }
                }


            }
        },

        isAccordionContentExpanded(accordionId) {
            return this.accordionId === accordionId && this.sameClick === false

        }
    };

}
