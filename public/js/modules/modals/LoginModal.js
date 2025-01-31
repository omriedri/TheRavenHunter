import { Utilities } from "../Utilities.js";
import { Notifier } from "../Notifier.js";
import { FormHandler } from "../FormHandler.js";
import { Home } from "../Home.js";
import { AuthService } from "../AuthService.js";
import { UserInterface } from "../UI.js";
import { Settings } from "../Settings.js";
import { MainInstance } from "../Main.js";


export default class LoginModal {

    selector = '#loginModal';
    form = document.querySelector(`${this.selector} form`);
    modal = new bootstrap.Modal(document.querySelector(this.selector));
    SOUNDS = {
        SHOW: new Audio('/public/sounds/modal-show.mp3'),
    }

    constructor() {
        this.initEvents();
    }


    /**
     * The function applies the modal iframe attributes when modal is triggered
     */
    async #showingPreviewModal() {
        this.SOUNDS.SHOW.play();
    }


    /**
     * The function shows the modal iframe after its done to load the url
     */
    #doneShowingPreviewModal() {
    }


    /**
     * The function sets default values while the preview modal is closing
     */
    #hidingPreviewModal() {
        this.form.reset();
        FormHandler.cleanFormInputs(this.form);
    }

    /**
     * Login the user
     * @param {SubmitEvent} event 
     * @returns {void}
     */
    async login(event) {
        try {
            event.preventDefault();
            event.stopPropagation();
            event.submitter.disabled = true
            if(!FormHandler.checkFormValidity(this.form)) {
                event.submitter.disabled = false;
                new Notifier('', 'אנא מלא את כל השדות המודגשים בצורה תקינה', 'danger', 3000);
                return;
            }

            // new Notifier('', 'Logging in...', 'info', 1500);
            const {email, password} = Utilities.formDataToRawObject(new FormData(this.form))
            const response = await AuthService.login(email, password);

            if(!response.authenticated) {
                new Notifier('', response.message, 'danger', 3000);
                event.submitter.disabled = false;
                return;
            }
            event.submitter.disabled = false;
            this.modal.hide();
            const User = response?.data ?? {};
            Home.setUserWelcome(User?.fname, User?.image);
            Home.switchVisibilityByUserState(true);
            MainInstance.SettingsInstance.init();
        } catch (error) {
            new Notifier('', error?.message ?? error, 'danger', 3000);
            event.submitter.disabled = false;
        }
    }
    
    /**
     * @returns {boolean}
     */
    initEvents() {
        this.form.handler = new FormHandler(this.form);
        this.form.addEventListener('submit', (event) => this.login(event));
        this.modal._element.addEventListener('show.bs.modal', () => this.#showingPreviewModal());
        this.modal._element.addEventListener('hidden.bs.modal', () => this.#hidingPreviewModal());
        return true;
    }
}