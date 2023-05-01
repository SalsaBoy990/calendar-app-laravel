/*
*  Tab Switcher action
*
* */
export function tabsData() {

    return {
        tabId: '',
        tabsClass: "tabs",
        buttonClass: "tab-switcher",
        activeButtonClass: "red",

        init() {
            this.tabId = 'London';
            this.tabsClass = "tabs";
            this.buttonClass = "tab-switcher";
            this.activeButtonClass = "red";

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
