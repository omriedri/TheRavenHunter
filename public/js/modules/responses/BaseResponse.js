export class BaseResponse {

    success = false;
    status = 0;
    message = '';

    constructor() {
        this.success = false;
        this.status = 0;
        this.message = '';
    }

    /**
     * Set error message and status
     * 
     * @param {string} message 
     * @param {number} status 
     * @return {void}
     */
    setError(message, status = 400) {
        this.success = false;
        this.status = status;
        this.message = message;
    }
}