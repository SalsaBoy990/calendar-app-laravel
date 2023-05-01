/*
* Modal action
*
* */
export function modalData() {
    return {
        modal: false,

        openModal() {
            this.modal = true;
        },

        closeModal() {
            this.modal = false;
        }
    };
}
