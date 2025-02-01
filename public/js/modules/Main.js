import { Home } from "./Home.js";
import { Game } from "./Game.js";
import { UserInterface } from "./UI.js";
import { Records } from "./Records.js";
import { Settings } from "./Settings.js";

export class Main {

    LoggedUser = null;
    HomeInstance = null;
    UIInstance = null;
    SettingsInstance = null;
    GameInstance = null;

    static init() {
        return new Main().init();
    }

    init() {
        console.log('Main module initialized');
        this.HomeInstance = new Home();
        this.UIInstance = new UserInterface();
        return this;
    }

    /**
     * Get current settings 
     * @returns {Settings}
     */
    getSettings() {
        return this.SettingsInstance;
    }

    /**
     * Get current game instance
     * @returns {Game}
     */
    getGame() {
        return this.GameInstance;
    }

    /**
     * Get current user interface
     * @returns {UserInterface}
     */
    getUI() {
        return this.UIInstance;
    }
}

const MainInstance = Main.init();
MainInstance.SettingsInstance = new Settings();
MainInstance.GameInstance = new Game();
export { MainInstance }

