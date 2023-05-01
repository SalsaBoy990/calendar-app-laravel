const toggler = document.getElementsByClassName("caret");
let i;

for (i = 0; i < toggler.length; i++) {
    toggler[i].addEventListener("click", function () {
        this.parentElement.parentElement.parentElement.querySelector(".nested").classList.toggle("active");
        this.classList.toggle("caret-down");
        this.parentElement.parentElement.classList.toggle("active-list-item");
    });
}
