import { MainInstance } from './Main.js';
import LoginModal from './modals/LoginModal.js';
import ProfileModal from './modals/ProfileModal.js';
import RegisterModal from './modals/RegisterModal.js';
import { UserInterface } from './UI.js';
import { Utilities } from './Utilities.js';
export class Home {

    static = Home;
    MODALS = {
        login: new LoginModal(),
        register: new RegisterModal(),
        profile: new ProfileModal()
    }

    static ELEMENTS = {
        loader:         document.querySelector('home .loader'),
        loggedUser:     document.querySelector('home .loggedUser'),
        loggedIn:       document.querySelector('home .logged-in-section'),
        loggedOut:      document.querySelector('home .logged-out-section'),
        startGame:      document.querySelector('home #start'),
    }
    constructor() {
        this.init();
    }

    init() {
        console.log('Login module initialized');
        window.oncontextmenu = () => false;
        Home.getUserState();
        Home.ELEMENTS.startGame.addEventListener('click', () => {
            MainInstance.GameInstance.start();
            UserInterface.navigateTo(UserInterface.PAGES.GAME);
        });
    }

    /**
     * Get the user state
     * @returns {Promise<void>}
     */
    static async getUserState() {
        const response = await Utilities.sendFetch('auth/check', 'GET');
        if(response.success && response.status === 200 && response.data) {
            const { isLogged, User } = response.data;
            if(isLogged) {
                MainInstance.LoggedUser = User;
                this.setUserWelcome(User?.fname, User?.image);
                this.switchVisibilityByUserState(true);
                MainInstance.SettingsInstance.init();
            } else {
                MainInstance.LoggedUser = null;
                this.switchVisibilityByUserState(false);
            }
        } else {
            MainInstance.LoggedUser = null;
            this.switchVisibilityByUserState(false);
        }
        this.ELEMENTS.loader.classList.add('d-none');
    }



    /**
     * Set the user welcome message
     * @param {string} fname 
     * @param {string|null} image 
     */
    static setUserWelcome(fname, image) {
        UserInterface.ELEMENTS.profile.querySelector('[data-user-name]').textContent = fname;
        this.ELEMENTS.loggedUser.querySelectorAll('[data-user-name]').forEach(el => el.textContent = fname);
        document.querySelectorAll('[data-user-image]').forEach(el => {el.src = image; el.alt = fname});
    }

    /**
     * Switch the visibility of the elements by the user state
     * @param {boolean} isLoggedIn 
     */
    static switchVisibilityByUserState(isLoggedIn) {
        this.ELEMENTS.loggedIn.classList.toggle('d-none', !isLoggedIn);
        this.ELEMENTS.loggedOut.classList.toggle('d-none', isLoggedIn);
        UserInterface.toggleProfile(isLoggedIn);
        UserInterface.toggleNav(isLoggedIn);
        document.body.classList.toggle('logged-in', isLoggedIn);
    }
}
