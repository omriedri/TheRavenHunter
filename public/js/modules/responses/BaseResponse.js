export class BaseResponse {

    success = false;
    status = 0;
    message = '';

    constructor() {
        this.success = false;
        this.status = 0;
        this.message = '';
    }
}