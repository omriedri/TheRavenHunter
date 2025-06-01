import { Utilities } from "./Utilities.js";
import { AuthResponse } from "./responses/AuthResponse.js";
import { BaseResponse } from "./responses/BaseResponse.js";
import { MainInstance } from "./Main.js";
import { Notifier } from "./Notifier.js";
import { UserInterface } from "./UI.js";
import { Home } from "./Home.js";

export class AuthService {

    static static = AuthService;

    /**
     * Login the user
     * 
     * @param {string} email 
     * @param {string} password 
     * @returns {Promise<AuthResponse>}
     */
    static async login(email, password) {
        const Response = new AuthResponse();
        const response = await Utilities.sendFetch('auth/login', 'POST', { email, password });
        Response.message = response?.message ?? '';
        if (response.success && response.status === 200) {
            Response.data = MainInstance.LoggedUser = response?.data ?? null;
            Response.authenticated = !!response?.data; 
        }
        return Response;
    }

    /**
     * Logout the user
     * 
     * @returns {Promise<AuthResponse>}
     */
    static async logout() {
        const Response = new AuthResponse();
        const response = await Utilities.sendFetch('auth/logout', 'POST');
        Response.message = response?.message ?? '';
        if (response.success && response.status === 200) {
            Response.authenticated = false;
            MainInstance.LoggedUser = null;
            MainInstance.SettingsInstance.setDefaultSettings();
            new Notifier('', 'You have been logged out', 'success', 3000);
            if(!document.querySelector('home.active')) {
                UserInterface.navigateTo(UserInterface.PAGES.HOME);
            }
        }
        return Response;
    }

    /**
     * Register the user
     * 
     * @param {object} data 
     * @returns {Promise<BaseResponse>}
     */
    static async register(data = {}) {
        const response = await Utilities.sendFetch('auth/register', 'POST', data);
        return response;
    }

    /**
     * Check if the user is authenticated
     * 
     * @returns {boolean}
     */
    static isAuthenticated() {
        return MainInstance.LoggedUser !== null;
    }

    /**
     * Get the logged user
     * 
     * @returns {object}
     */
    static getLoggedUser() {
        return MainInstance.LoggedUser;
    }

    /**
     * Get the verification code
     * 
     * @param {string} email 
     * @returns {Promise<BaseResponse>}
     */
    static async getVerificationCode(email) {
        const response = await Utilities.sendFetch('auth/get-verify-email', 'POST', { email });
        return response;
    }

    /**
     * Reset the password using the verification code
     * 
     * @param {string} email 
     * @param {string} password 
     * @param {string} code 
     * @returns {Promise<BaseResponse>}
     */
    static async resetPassword(email, password, password_confirm, verification_code) {
        const data = { email, password, password_confirm, verification_code };
        const response = await Utilities.sendFetch('auth/reset-password', 'POST', data);
        return response;
    }

    /**
     * Initialize the Google Sign-In button
     * This function uses the Google Identity Services library to render a sign-in button
     * 
     * @param {HTMLElement} element - The HTML element where the Google Sign-In button will be rendered
     * @returns {void}
     */
    static initGoogleSignInButton(element) {
        if (!window?.google) {
            console.warn(' The Google Identity Services library is not loaded.');
            return;
        }
        if (!window?.GOOGLE_CLIENT_ID) {
            console.warn('Google Client ID is not set. Skipping Google Sign-In button initialization.');
            return;
        }
        if(!window.google?.accounts?.id) {
            console.warn('Google Identity Services is not initialized. Skipping Google Sign-In button initialization.');
            return;
        }
        if (!element || !(element instanceof HTMLElement)) {
            console.warn('Invalid element provided for Google Sign-In button initialization.');
            return;
        }
        window.google.accounts.id.initialize({
            client_id: window.GOOGLE_CLIENT_ID ?? '',
            callback: AuthService.handleGoogleCredentialResponse,
        });
        window.google.accounts.id.renderButton(element,{ theme: "outline", size: "large" });
    }

    /**
     * Handle the Google credential response
     * @param {Object} response
     * @returns {Promise<void>}
     */
    static async handleGoogleCredentialResponse (CredentialResponse) {
        debugger;
        try {
            const credential = CredentialResponse?.credential ?? null;
            const SignInResponse = await AuthService.__loginByGoogleAccount(credential);
            if (!SignInResponse.success) {
                new Notifier('', SignInResponse.message, 'danger', 3000);
                return;
            }

            window.modals?.login?.modal?.hide()
            window.modals?.register?.modal?.hide();
            const User = MainInstance.LoggedUser = SignInResponse.data ?? {};
            Home.setUserWelcome(User?.fname, User?.image);
            Home.switchVisibilityByUserState(true);
            MainInstance.SettingsInstance.init();
        } catch (err) {
            new Notifier('', err.message, 'danger', 3000);
        }
    };


    /**
     * Handle the Google credential response
     * @param {string|null} credential
     * @returns {Promise<AuthResponse>}
     */
    static async __loginByGoogleAccount(credential) {
        const Response = new AuthResponse();
        try {
            const id_token = credential ?? null;
            if (!id_token) {
                throw new Error('Google Sign-In failed. No credential received.');
            }
            const SeverResponse = await Utilities.sendFetch('/auth/login-by-google', 'POST', { id_token });
            if (!SeverResponse.success) {
                throw new Error(SeverResponse.message || 'Google Sign-In failed.');
            }
            Response.message = SeverResponse?.message ?? 'Successfully logged in with Google';
            Response.success = !!SeverResponse?.success;
            Response.data = SeverResponse?.data ?? null;
            Response.authenticated = !!SeverResponse?.data;
        } catch (error) {
            Response.setError(error?.message ?? error);
        }
        return Response;
    }
}