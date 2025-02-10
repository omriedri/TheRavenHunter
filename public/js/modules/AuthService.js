import { Utilities } from "./Utilities.js";
import { AuthResponse } from "./responses/AuthResponse.js";
import { BaseResponse } from "./responses/BaseResponse.js";
import { MainInstance } from "./Main.js";
import { Notifier } from "./Notifier.js";
import { UserInterface } from "./UI.js";

export class AuthService {

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
}