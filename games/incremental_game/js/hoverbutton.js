export default class HoverButton {
    constructor(button, timeToComplete, callback) {
        this.button = button;
        this.progress = 0;
        this.timeToComplete = timeToComplete;
        this.hovered = false;
        this.disabled = false;
        this.callback = callback;

        this.button.onmouseenter = () => {
            this.hovered = true;
        }
        this.button.onmouseleave = () => {
            this.hovered = false;
        }
    }

    update(elapsedTime) {
        if (this.hovered && !this.disabled) {
            this.progress += elapsedTime;
            while (this.progress > this.timeToComplete) {
                this.progress -= this.timeToComplete;
                this.callback();
            }
        }
        let gradientStop = Math.min(Math.round(this.progress / this.timeToComplete * 100), 100);
        let backgroundColor;
        if (this.disabled) {
            backgroundColor = "#c3c3c9"
        }
        else {
            backgroundColor = "#e9e9ed"
        }
        this.button.style.backgroundImage = `linear-gradient(to right, #21c473 ${gradientStop}%, ${backgroundColor} ${gradientStop}%)`
    }

    disable() {
        this.disabled = true;
        this.button.disabled = true;
    }

    enable() {
        this.disabled = false;
        this.button.disabled = false;
    }
}