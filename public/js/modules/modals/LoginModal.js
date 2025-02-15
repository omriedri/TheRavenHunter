import { Utilities } from "../Utilities.js";
import { Notifier } from "../Notifier.js";
import { FormHandler } from "../FormHandler.js";
import { Home } from "../Home.js";
import { AuthService } from "../AuthService.js";
import { MainInstance } from "../Main.js";


export default class LoginModal {

    selector = '#loginModal';
    form = document.querySelector(`${this.selector} form`);
    modal = new bootstrap.Modal(document.querySelector(this.selector));
    SOUNDS = {
        SHOW: new Audio('/public/sounds/modal-show.mp3'),
    }

    ELEMENTS = {
        FORMS: {
            LOGIN: document.querySelector('#loginForm'),
            FORGOT_PASSWORD: document.querySelector('#forgetPasswordForm'),
            RESET_PASSWORD: document.querySelector('#resetPasswordForm'),
        },
        RESET_PASSWORD_BTN: document.querySelector('#resetPasswordBtn'),
    }


    constructor() {
        this.initEvents();
    }

    /**
     * Navigate between forms on the login modal
     */
    #modalFormsNavigation() {
        this.modal._element.querySelectorAll('[data-form-switch]').forEach(btn => { 
            btn.addEventListener('click', (e) => {
                const formName = e.target.getAttribute('data-form-switch').toUpperCase() ?? 'LOGIN';
                this.switchForm(this.ELEMENTS.FORMS[formName]);
            });
        });
    }

    /**
     * Switch between forms
     * @param {HTMLFormElement} form
     */
    switchForm(form) {
        Object.values(this.ELEMENTS.FORMS).forEach(f => f.classList.remove('active'));
        form.classList.add('active');
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
        Object.values(this.ELEMENTS.FORMS).forEach(f => {
            f?.reset();
            FormHandler.cleanFormInputs(f);
        });
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
     * Get the verification code for password reset
     * @param {SubmitEvent} event
     * @returns {void}
     */
    async getVerificationCode(event) {
        try {
            if(!(event instanceof SubmitEvent)) return;
            event.preventDefault();
            const email = event.target.querySelector('input[name="email"]').value;
            if (!email || !(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email))) {
                new Notifier('', 'אנא הזן כתובת אימייל תקינה', 'danger', 3000);
                return;
            }
            const response = await AuthService.getVerificationCode(email);
            if(!response.success) {
                new Notifier('', response.message, 'danger', 3000);
                return;
            }
            new Notifier('', response.message, 'success', 3000);
            this.switchForm(this.ELEMENTS.FORMS.RESET_PASSWORD);
        } catch (error) {
            new Notifier('', error?.message ?? error, 'danger', 3000);
        }
    }

    /**
     * Reset the password
     * @param {SubmitEvent} event
     * @returns {void}
     */
    async resetPassword(event) {
        try {
            if(!(event instanceof SubmitEvent)) return;
            event.preventDefault();
            const formData = Utilities.formDataToRawObject(new FormData(event.target));
            const { email, password, confirmPassword, code } = formData
            if(password !== confirmPassword) {
                new Notifier('', 'הסיסמאות אינן תואמות', 'danger', 3000);
                return;
            }
            const response = await AuthService.resetPassword(email, password, confirmPassword, code);
            if(!response.success) {
                new Notifier('', response.message, 'danger', 3000);
                return;
            }
            new Notifier('', response.message, 'success', 3000);
            this.switchForm(this.ELEMENTS.FORMS.LOGIN);
        } catch (error) {
            new Notifier('', error?.message ?? error, 'danger', 3000);
        }
    }
    
    /**
     * @returns {boolean}
     */
    initEvents() {
        this.#modalFormsNavigation();
        this.form.handler = new FormHandler(this.form);
        this.ELEMENTS.FORMS.LOGIN.addEventListener('submit', (event) => this.login(event));
        this.ELEMENTS.FORMS.FORGOT_PASSWORD.addEventListener('submit', (e) => this.getVerificationCode(e));
        this.ELEMENTS.FORMS.RESET_PASSWORD.addEventListener('submit', (e) => this.resetPassword(e));
        this.modal._element.addEventListener('show.bs.modal', () => this.#showingPreviewModal());
        this.modal._element.addEventListener('hidden.bs.modal', () => this.#hidingPreviewModal());
        return true;
    }
}