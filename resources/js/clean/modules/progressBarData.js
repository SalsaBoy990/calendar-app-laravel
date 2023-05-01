/*
*   Progress Bar action
*
* */
export function progressBarData() {

    return {
        speed: 30,
        width: 2,
        label: '',
        id: 0,

        triggerMove() {
            this.id = window.setInterval(() => this.move(), this.speed);
        },

        move() {
            if (this.width === 100) {
                clearInterval(this.id);
                this.width = 2;
                this.label = '';
            } else {
                this.width++;
                this.label = this.width + '%';
            }
        }
    };

}
