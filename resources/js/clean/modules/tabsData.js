/*
*  Tab Switcher action
*
* */
export function tabsData(tabId = 'London', activeColor = 'red') {

    return {
        tabId: '',
        tabsClass: "",
        buttonClass: "",
        activeButtonClass: '',

        init() {
            this.tabId = tabId;
            this.tabsClass = "tabs";
            this.buttonClass = "tab-switcher";
            this.activeButtonClass = activeColor;

            this.switchTab(this.tabId);
        },

        switchTab(tabId) {
            this.tabId = tabId;
            const x = document.getElementsByClassName(this.tabsClass);
            for (let i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            const activeButton = document.getElementsByClassName(this.buttonClass);
            const activeButtonClassString = " " + this.activeButtonClass;
            for (let i = 0; i < x.length; i++) {
                activeButton[i].className = activeButton[i].className.replace(activeButtonClassString, "");
            }
            document.getElementById(this.tabId).style.display = "block";

        }


    };
}
