import { Utilities } from "./Utilities.js";
import { Notifier } from "./Notifier.js";
import { MainInstance } from "./Main.js";

export class Settings {
    
    static = Settings;
    static ELEMENTS = {
        container: document.querySelector('settings'),
        difficulty: document.querySelector('settings #difficultySelector'),
        appearance: document.querySelector('settings #appearanceSelector'),
        full_screen: document.querySelector('settings [name="full_screen"]'),
        coordinates: document.querySelector('settings [name="coordinates"]'),
        sounds: document.querySelector('settings [name="sounds"]'),
    }


    difficulty = 1;
    appearance = 1;
    full_screen = 0;
    coordinates = 0;
    sounds = 0;

    eventsSetted = false;

    constructor() {
        this.init();
    }

    async fetchSettings() {
        return await Utilities.sendFetch('api/users/settings');
    }

    static getSettings() {
        return MainInstance.SettingsInstance;
    }

    setSettings(settings = {}) {
        this.difficulty = settings.difficulty || 1;
        this.appearance = settings.appearance || 1;
        this.full_screen = settings.full_screen || 0;
        this.coordinates = settings.coordinates || 0;
        this.sounds = settings.sounds || 0;
        this.#applyStatesOnElements();
    }

    setDefaultSettings() {
        this.difficulty = 1;
        this.appearance = 1;
        this.full_screen = 0;
        this.coordinates = 0;
        this.sounds = 0;
        this.#applyStatesOnElements();
    }

    #applyStatesOnElements() {
        Settings.ELEMENTS.difficulty.querySelector(`input[value="${this.difficulty}"`).checked = true;
        Settings.ELEMENTS.appearance.querySelector(`input[value="${this.appearance}"`).checked = true;
        Settings.ELEMENTS.full_screen.checked = this.full_screen === 1;
        Settings.ELEMENTS.coordinates.checked = this.coordinates === 1;
        Settings.ELEMENTS.sounds.checked = this.sounds === 1;
    }

    #setListeners() {
        if(this.eventsSetted) return;
        this.static.ELEMENTS.container.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', (event) => this.updateSettings(event));
        });
        this.eventsSetted = true;
    }

    getDifficultyName() {
        switch(this.difficulty) {
            case 1: return 'EASY';
            case 2: return 'MEDIUM';
            case 3: return 'HARD';
            case 4: return 'INSANE';
            default: return 'EASY';
        }
    }

    async updateSettings(e) {
        if(!(e instanceof Event)) return;
        if(!(e.target instanceof HTMLInputElement)) return;
        const data = {[e.target.name]: parseInt(e.target.type === 'checkbox' ? (e.target.checked ? 1 : 0) : e.target.value)};
        const Response = await Utilities.sendFetch('api/users/settings-update', 'POST', data);
        if(!Response.success) {
            new Notifier('', Response.message, 'danger', 3000);
            return;
        }
        new Notifier('', Response.message, 'success', 2000);
        this[e.target.name] = data[e.target.name];
    }

    init() {
        this.#setListeners();
        if(!MainInstance.LoggedUser) {
            this.setDefaultSettings();
        } else {
            this.fetchSettings().then(response => {
                if(response.success) {
                    this.setSettings(response.data);
                } else {
                    new Notifier('', response.message, 'danger');
                }
            });
        }
    }   
}