/*
*  Close alert action
*
* */
export function alertData() {

    return {
        openAlert: true,

        showAlert() {
            this.openAlert = true;
        },

        hideAlert() {
            this.openAlert = false;
        }
    };
}

