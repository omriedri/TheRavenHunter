import { BaseResponse } from "./BaseResponse.js";

export class AuthResponse extends BaseResponse {

    success = false;
    status = 200;
    message = '';
    data = {};
    authenticated = false;
    
    constructor(Response, data = null, authenticated = false) {
        super();
        if(Response instanceof BaseResponse) {
            this.success = BaseResponse.success;
            this.status = BaseResponse.status;
            this.message = BaseResponse.message;
        }
        this.data = data;
        this.authenticated = authenticated;
    }
}