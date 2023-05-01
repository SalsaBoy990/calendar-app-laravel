/*
*  Dropdown action
*
* */
export function dropdownData() {
    return {
        openDropdown: false,

        toggleDropdown() {
            this.openDropdown = !this.openDropdown;
        },

        hideDropdown() {
            this.openDropdown = false;
        }
    };
}
