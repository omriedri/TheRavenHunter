import { UserInterface } from './UI.js';
import Timer from './Timer.js';
import { MainInstance } from './Main.js';
import { Utilities } from './Utilities.js';
import { Notifier } from './Notifier.js';
import GameEndingModal from './modals/GameEnding.js';
import GameOverModal from './modals/GameOver.js';

export class Game {

    static = Game;
    static ELEMENTS = {
        BIRD    : document.querySelector('game img#bird'),
        CAGE    : document.querySelector('game #cage'),
        RESTART : Timer.ELEMENTS.restart,
        EXIT_BTN: document.querySelector('game [action="exit"]'),
        LIFE_BAR: document.querySelector('game #life-bar .progress-bar'),
    }

    SOUNDS = {
        HIT: new Audio('/public/sounds/hit.mp3'),
        KILL: new Audio('/public/sounds/kill.mp3'),
        MOVE: new Audio('/public/sounds/move.mp3'),
        SHOT: new Audio('/public/sounds/shot-slow.mp3'),
        OVER: new Audio('/public/sounds/game-over.mp3'),
    }

    GameEndingModal = new GameEndingModal();
    GameOverModal = new GameOverModal();

    #DIFICULTY = {
        EASY    : 1,
        MEDIUM  : 2,
        HARD    : 3,
        INSANE  : 4,
    }

    #SURVIVALS = {
        EASY    : 1,
        MEDIUM  : 3,
        HARD    : 5,
        INSANE  : 6,
    }

    #INTERVALS = {
        EASY    : 600,
        MEDIUM  : 400,
        HARD    : 300,
        INSANE  : 300,
    }

    #START_TIME = {
        EASY    : { milisec: 0, seconds: 0, minutes: 2, hour: 0 },
        MEDIUM  : { milisec: 0, seconds: 0, minutes: 1, hour: 0 },
        HARD    : { milisec: 0, seconds: 45, minutes: 0, hour: 0 },
        INSANE  : { milisec: 0, seconds: 30, minutes: 0, hour: 0 },
    }

    scoreTime = 0;
    hitCounts = 0;
    Timer = new Timer();
    coordinates = { x: 0, y: 0 };
    cage =        { width: 0, height: 0 };
    last =        { scoreTime: 0, scoreValue: 0 };
    intervals =   { movements: null, visibility: null };

    constructor() {
        this.init();
    }

    init() {
        this.#setListeners();
        this.#setGame();
    }

    #setListeners() {
        Game.ELEMENTS.RESTART.addEventListener('click', () => this.restart());
        Game.ELEMENTS.EXIT_BTN.addEventListener('click', () => this.exit());
        Game.ELEMENTS.CAGE.addEventListener('pointerdown', (e) => this.#shot(e));
        Game.ELEMENTS.CAGE.addEventListener('pointerdown', (e) => this.#hitBird(e));
        GameEndingModal.ELEMENTS.BTN_EXIT.addEventListener('click', () => this.exit());
        GameEndingModal.ELEMENTS.BTN_TRY_AGAIN.addEventListener('click', () => this.restart());
        GameOverModal.ELEMENTS.BTN_EXIT.addEventListener('click', () => this.exit());
        GameOverModal.ELEMENTS.BTN_TRY_AGAIN.addEventListener('click', () => this.restart());
        window.addEventListener('resize', () => this.#seCageSize());
    }

    #setGame() {
        this.#seCageSize();
        this.#setBirdPosition();
        this.#setGameSounds();
        this.Timer.init();
    }

    #setGameSounds() {
        const soundOn = MainInstance.getSettings()?.sounds !== false;
        Object.values(this.SOUNDS).forEach(audio => audio.muted = !soundOn );
    }

    #seCageSize() {
        Game.ELEMENTS.CAGE.style.height = `${window.innerHeight - 250}px`;
        Game.ELEMENTS.CAGE.style.width  = `${window.innerWidth - 100}px`;
    }

    #setBirdPosition(x = null, y = null) {
        if(x ===  0 && y === 0) {
            this.coordinates.x = 0;
            this.coordinates.y = 0;
        } else {
            this.coordinates.x = this.#getRandomXCoordinate();
            this.coordinates.y = this.#getRandomYCoordinate();
        }
        this.static.ELEMENTS.BIRD.style.top = `${this.coordinates.y}px`;
        this.static.ELEMENTS.BIRD.style.right = `${this.coordinates.x}px`;
    }

    #getRandomXCoordinate() {
        return Math.floor(Math.random() * (Game.ELEMENTS.CAGE.offsetWidth - 50));
    }

    #getRandomYCoordinate() {
        return Math.floor(Math.random() * (Game.ELEMENTS.CAGE.offsetHeight - 50));
    }

    #moveBird() {
        if(!this.isRunning()) this.start();
        this.#setBirdPosition();
    }

    #switchVisibility() {
        this.static.ELEMENTS.BIRD.classList.toggle('disappear');
    }

    #shot() {
        if(!this.isRunning()) return;
        this.#playSoundEffect(this.SOUNDS.SHOT);
    }

    #hitBird(event) {
        if(!this.isRunning()) return;
        if(event instanceof MouseEvent && !this.#isMouseOverTheTarget(event)) return;
        this.hitCounts++;
        this.#setBirdLifePrecentage();
        if(this.hitCounts >= this.#getRequiredHitsByDifficulty()) {
            this.#playSoundEffect(this.SOUNDS.SHOT);
            this.#playSoundEffect(this.SOUNDS.KILL);
            this.end();
            return;
        }
        this.#playSoundEffect(this.SOUNDS.HIT);
        this.#moveBird();
    }

    /**
     * Check if the mouse is over the bird
     * @param {MouseEvent} e
     * @returns {boolean}
     */
    #isMouseOverTheTarget(e) {
        return Utilities.isMouseOverElement(this.static.ELEMENTS.BIRD, e.clientX, e.clientY);
    }


    #updateScore() {
        this.last.scoreTime = this.Timer.time.milisec;
        this.last.scoreValue = this.hitCounts;
    }

    isRunning() {
        return this.Timer.running;
    }


    reset() {
        this.Timer.reset();
        this.coordinates = { x: 0, y: 0 };
        this.hitCounts = 0;
        this.scoreTime = 0;
        this.#updateScore();
        this.#setBirdPosition(0, 0);
        this.#setBirdLifePrecentage();
        this.#clearMovementsInterval();
        this.#clearVisibilityInterval();
        
    }

    restart() {
        this.reset();
        setTimeout(() => this.start(), 500);
    }

    abort() {
        this.reset();
        this.#forgetSession();
    }

    start() {
        this.reset();
        this.#startSession();
        this.Timer.start();
        this.intervals.movements = setInterval(() => this.#moveBird(), this.#getItervalTimeByDifficulty());
        if(MainInstance.getSettings().difficulty === this.#DIFICULTY.INSANE) {
            this.intervals.visibility = setInterval(() => this.#switchVisibility(), 1000);
        }
    }

    async end() {
        const time = (this.Timer.getTimerString()).replace(/\s/g, '');
        this.Timer.stop();
        this.#updateScore();
        this.#clearMovementsInterval();
        this.#clearVisibilityInterval();
        this.#showGameEndingModal().#endSession().then(data => {
            if(!isNaN(parseInt(data?.score))) {
                this.GameEndingModal.static.ELEMENTS.SCORE.innerText = data.score;
                this.GameEndingModal.static.ELEMENTS.TIME.innerText = time;
            } else {
                this.GameEndingModal.modal.hide();
                new Notifier('', 'An error occurred while trying to end the game', 'danger', 3000);
            }
        }).catch(error => {
            new Notifier('', error?.message ?? error, 'danger', 3000);
        }).finally(() => {
            this.reset();
        });
    }

    exit() {
        this.abort();
        UserInterface.navigateTo(UserInterface.PAGES.HOME);
    }

    gameOver() {
        this.abort();
        this.GameOverModal.launch();
        this.#playSoundEffect(this.SOUNDS.OVER);
    }

    #showGameEndingModal() {
        this.GameEndingModal.modal.show();
        return this;
    }

    getDifficultyName() {
        const difficultiesKeys = Object.keys(this.#DIFICULTY);
        const difficulty = MainInstance.getSettings().difficulty;
        return difficultiesKeys[difficulty - 1];
    }

    /**
     * Get the start time object by difficulty
     * @returns {{milisec: number, seconds: number, minutes: number, hour: number}}
     */
    getStartTimeObjectByDifficulty() {
        switch (MainInstance.getSettings().difficulty) {
            case this.#DIFICULTY.EASY:
                return this.#START_TIME.EASY;
            case this.#DIFICULTY.MEDIUM:
                return this.#START_TIME.MEDIUM;
            case this.#DIFICULTY.HARD:
                return this.#START_TIME.HARD;
            case this.#DIFICULTY.INSANE:
                return this.#START_TIME.INSANE;
            default:
                return this.#START_TIME.EASY;
        }
    }

    #getRequiredHitsByDifficulty() {
        switch(MainInstance.getSettings().difficulty) {
            case this.#DIFICULTY.EASY:
                return this.#SURVIVALS.EASY;
            case this.#DIFICULTY.MEDIUM:
                return this.#SURVIVALS.MEDIUM;
            case this.#DIFICULTY.HARD:
                return this.#SURVIVALS.HARD;
            case this.#DIFICULTY.INSANE:
                return this.#SURVIVALS.INSANE;
            }
    }

    #getItervalTimeByDifficulty() {
        switch(MainInstance.getSettings().difficulty) {
            case this.#DIFICULTY.EASY:
                return this.#INTERVALS.EASY;
            case this.#DIFICULTY.MEDIUM:
                return this.#INTERVALS.MEDIUM;
            case this.#DIFICULTY.HARD:
                return this.#INTERVALS.HARD;
            case this.#DIFICULTY.INSANE:
                return this.#INTERVALS.INSANE;
            default:
                return 750;
        }
    }

    #clearMovementsInterval() {
        if(this.intervals.movements) {
            clearInterval(this.intervals.movements);
            this.intervals.movements = null;
        }
    }

    #clearVisibilityInterval() {
        if(this.intervals.visibility) {
            clearInterval(this.intervals.visibility);
            this.intervals.visibility = null;
        }
        if(this.static.ELEMENTS.BIRD.classList.contains('disappear')) {
            this.static.ELEMENTS.BIRD.classList.remove('disappear');
        }
    }

    #setBirdLifePrecentage() {
        const hitsLeft = this.#getRequiredHitsByDifficulty() - this.hitCounts;
        const hitsRequired = this.#getRequiredHitsByDifficulty();
        const hitsPercentage = (hitsLeft / hitsRequired) * 100;
        this.static.ELEMENTS.LIFE_BAR.style.width = `${hitsPercentage}%`;
        if(hitsPercentage <= 25) {
            this.static.ELEMENTS.LIFE_BAR.classList.remove('bg-success', 'bg-warning');
            this.static.ELEMENTS.LIFE_BAR.classList.add('bg-danger');
        } else if(hitsPercentage <= 50) {
            this.static.ELEMENTS.LIFE_BAR.classList.remove('bg-success', 'bg-danger');
            this.static.ELEMENTS.LIFE_BAR.classList.add('bg-warning');
        } else {
            this.static.ELEMENTS.LIFE_BAR.classList.remove('bg-warning', 'bg-danger');
            this.static.ELEMENTS.LIFE_BAR.classList.add('bg-success');
        }
    }

    async #startSession() {
        const data = { platform: UserInterface.isMobile() ? 2 : 1 };
        const response = await Utilities.sendFetch('api/game/start', 'POST', data);
        if(!response.success) {
            new Notifier('', response.message, 'danger', 3000);
            return;
        }
        return response.data;
    }

    async #endSession() {
        const response = await Utilities.sendFetch('api/game/end', 'POST');
        if(!response.success) {
            new Notifier('', response.message, 'danger', 3000);
            return;
        }
        return response.data;
    }

    async #forgetSession() {
        const response = await Utilities.sendFetch('api/game/forget', 'POST');
        if(!response.success) {
            new Notifier('', response.message, 'danger', 3000);
            return;
        }
        return response.data;
    }

    /**
     * Play a sound
     * @param {Audio} sound 
     */
    #playSoundEffect(sound) {
        if(!sound || !(sound instanceof Audio)) return;
        if(!MainInstance.getSettings()?.sounds) return;
        sound.currentTime = 0;
        sound.play();
    }
}