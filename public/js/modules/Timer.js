import { MainInstance } from "./Main.js";

export default class Timer {


    static = Timer;
    static ELEMENTS = {
        restart: document.querySelector('game [action="restart"]'),
        timer: document.querySelector('game timer'),
    }

    DIRECTION = {
        FORWARD: 'forward',
        REVERSE: 'reverse',
    }

    running = false;
    interval = null;
    direction = this.DIRECTION.FORWARD;
    time = {
        milisec: 0,
        seconds: 0,
        minutes: 2,
        hour: 0,
    }


    constructor(direction = this.DIRECTION.REVERSE) {
        this.interval = null;
        this.running = false;
        this.init(direction);
    }

    /**
     * Initialize the timer
    */
    init(direction = this.DIRECTION.REVERSE) {
        this.direction = direction;
        this.static.ELEMENTS.restart.addEventListener('click', () => this.reset());
    }

    /**
     * Start the timer
     */
    start() {
        this.interval = setInterval(() => this.#run(), 10);
        this.static.ELEMENTS.restart.removeAttribute('disabled');
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
        this.#setStartTimeByDifficulty();
        this.static.ELEMENTS.timer.innerHTML = this.getTimerString();
        this.static.ELEMENTS.restart.setAttribute('disabled', 'disabled');
        this.running = false;
    }

    /**
     * Run the timer and update the time every 10 milisec
     */
    #run() {
        this.running = true;
        this.direction === this.DIRECTION.FORWARD ? this.#runForward() : this.#runReverse();
        this.static.ELEMENTS.timer.innerHTML = this.getTimerString();
        if (this.#checkTimeLeft()) {
            this.stop();
            MainInstance.getGame().gameOver();
        }
    }

    /**
     * Run the timer in forward and update the time every 10 milisec
     */
    #runForward() {
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
    }

    /**
     * Run the timer in reverse and update the time every 10 milisec
     */
    #runReverse() {
        this.time.milisec = --this.time.milisec;

        if (this.time.milisec < 0) {
            this.time.milisec = 99;
            this.time.seconds = --this.time.seconds;
        }
        if (this.time.seconds < 0) {
            this.time.seconds = 59;
            this.time.minutes = --this.time.minutes;
        }
        if (this.time.minutes < 0) {
            this.time.minutes = 59;
            this.time.hour = --this.time.hour;
        }
    }

    #checkTimeLeft() {
        return this.time.hour === 0 && this.time.minutes === 0 && this.time.seconds === 0 && this.time.milisec === 0;
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

    /**
     * Set the start time by difficulty
     * @returns {void}
     */
    #setStartTimeByDifficulty() {
        const GameInstance = MainInstance.getGame();
        const { milisec, seconds, minutes, hour } = GameInstance.getStartTimeObjectByDifficulty();
        this.time.milisec = milisec;
        this.time.seconds = seconds;
        this.time.minutes = minutes;
        this.time.hour = hour;
    }



}