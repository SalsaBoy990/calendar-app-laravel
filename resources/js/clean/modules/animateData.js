/*
*  Animate action
*
* */
export function animateData() {

    return {
        target: '',

        animate(animationName) {
          this.target = animationName || 'normal';
        },
    };
}
