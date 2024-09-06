export default class Timer {
    constructor() {
        this.startTime = null;
        this.stopped = true;
        this.elapsedTime = 0;
    }

    start() {
        if (this.stopped) {
            this.stopped = false;
            this.startTime = performance.now();
        }
    }

    stop() {
        if (!this.stopped) {
            this.stopped = true;
            this.elapsedTime += (performance.now() - this.startTime);
        }
    }

    getElapsedTime() {
        let timeSoFar = this.elapsedTime;
        if (!this.stopped) {
            timeSoFar += (performance.now() - this.startTime);
        }
        return timeSoFar / 1000; // return answer in seconds
    }
}