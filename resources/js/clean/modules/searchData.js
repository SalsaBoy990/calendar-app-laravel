/*
*  Search component
*
* */
export function searchData() {

    return {
        panelOpen: false,

        togglePanel() {
            this.panelOpen = !this.panelOpen;
        },

        initializeProperties() {
            this.panelOpen = false;
        }

    }
}
