export default class Timer {


    static = Timer;
    static ELEMENTS = {
        restart: document.querySelector("game restart"),
        timer: document.querySelector('game timer'),
    }

    running = false;
    interval = null;
    time = {
        milisec: 0,
        seconds: 0,
        minutes: 0,
        hour: 0,
    }


    constructor() {
        this.interval = null;
        this.running = false;
        this.init();
    }

    /**
     * Initialize the timer
    */
    init() {
        this.static.ELEMENTS.restart.addEventListener('click', () => this.reset());
    }

    /**
     * Start the timer
     */
    start() {
        this.interval = setInterval(() => this.#run(), 10);
        this.static.ELEMENTS.restart.classList.add("active");
    }

    /**
     * Stop the timer
    */
    stop() {
        clearInterval(this.interval);
        this.running = false;
    }

    /**
     * Reset the timer
     */
    reset() {
        clearInterval(this.interval);
        this.time.milisec = this.time.seconds = this.time.minutes = 0;
        this.static.ELEMENTS.timer.innerHTML = this.getTimerString();
        this.static.ELEMENTS.restart.classList.remove("active");
        this.running = false;
    }

    /**
     * Run the timer and update the time every 10 milisec
     */
    #run() {
        this.running = true;
        this.time.milisec = ++this.time.milisec;

        if (this.time.milisec === 100) {
            this.time.milisec = 0;
            this.time.seconds = ++this.time.seconds;
        }
        if (this.time.seconds == 60) {
            this.time.minutes = ++this.time.minutes;
            this.time.seconds = 0;
        }
        if (this.time.minutes == 60) {
            this.time.minutes = 0;
            this.time.hour = ++this.time.hour;
        }
        this.static.ELEMENTS.timer.innerHTML = this.getTimerString();
    }

    /**
     * get the time in string format
     * @returns {string}
     */
    getTimerString() {
        let time = ``;
        time = `${this.#formatTime(this.time.minutes)}`
        time += ` : ${this.#formatTime(this.time.seconds)}`
        time += ` : ${this.#formatTime(this.time.milisec)}`
        return time;
    }

    /**
     * check the time and add zero if its less than 10
     * @param {number} i 
     * @returns 
     */
    #formatTime(i) {
        return i < 10 ? `0${i}` : i;
    }
}