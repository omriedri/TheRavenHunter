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
        this.initServerWorker();
        return this;
    }

    /**
     * Get current home instance
     * @returns {Home}
     */
    getHome() {
        return this.HomeInstance;
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

    /**
     * Initialize the service worker
     * This method registers a service worker to enable offline capabilities and caching.
     * 
     * @returns {void}
     */
    initServerWorker() {
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", function () {
                navigator.serviceWorker.register("/service-worker.js")
                    .then(function (registration) {
                        console.log("Service Worker registered with scope:", registration.scope);
                    })
                    .catch(function (error) {
                        console.log("Service Worker registration failed:", error);
                    });
            });
        }
    }
}

const MainInstance = Main.init();
MainInstance.SettingsInstance = new Settings();
MainInstance.GameInstance = new Game();
export { MainInstance }

