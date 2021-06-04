// Class for timers
class Timer {

    interval;
    timerId = null;
    _callback = null;



    constructor(interval = 1000) {
        this.interval = interval;
    }

    // Start the timer (start calling the callback repeatedly)
    start() {
        this.stop();
        this.timerId = window.setInterval(this._callback, this.interval);
    }

    // Stop the timer, if is set (stop calling the callback)
    stop() {
        if (this.timerId != null) {
            window.clearInterval(this.timerId);
        }
        this.timerId = null;
    }

    // Assign the appropriate action to timer tick (a function)
    set callback(callback) {
        this._callback =  callback;
    }
}

export {Timer};