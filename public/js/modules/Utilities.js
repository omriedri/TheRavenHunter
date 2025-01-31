// import { ConfirmActionModal } from "./modals/ConfirmActionModal.js";
import { BaseResponse } from "./responses/BaseResponse.js";
// import { Notifier } from "./Notifier.js";

export class Utilities {

    REQUEST_URLENCODE_HEADER = { 'Content-Type': 'application/x-www-form-urlencoded' };

    static select(selector, from) {
        from = from instanceof HTMLElement ? from : document;
        return typeof selector === 'string' ? from.querySelector(selector) : null;
    }

    static selectAll(selector, from) {
        from = from instanceof HTMLElement ? from : document;
        return typeof selector === 'string' ? from.querySelectorAll(selector) : null;
    }


    /**
     * Retrieve raw object from a given FormData object
     * @param {FormData} formData 
     * @returns {object}
     */
    static formDataToRawObject(formData) {
        const rawObject = {};
        if (formData instanceof FormData) {
            for (const pair of formData.entries()) {
                rawObject[pair[0]] = pair[1];
            }
        };
        return rawObject;
    }

    // /**
    //  * 
    //  * @param {string} path 
    //  * @param {string} method 
    //  * @param {object} data 
    //  * @returns {Promise<BaseResponse|string>}
    //  */
    // static async rest(path, method = 'GET', data = {}) {
    //     let response = new BaseResponse();
    //     try {
    //         method = method.toUpperCase();
    //         path = path.split('/');
    //         const category = path[0] ?? '';
    //         const action = path[1].toUpperCase() ?? '';
    //         const csrf = sessionStorage.getItem('user_csrf') ?? '';
    //         const headers = {
    //             'Content-Type': 'application/json',
    //             'CSRF-Token': csrf
    //         };
    //         if(method === 'GET'){
    //             let url = `controllers/?class=${category}&action=${action}`;
    //             if(data) Object.entries(data).forEach(([key, value]) => url += `&${key}=${value}`);
    //             response = await fetch(url, { method: method, headers: headers });
    //         } else { // POST
    //             const { view, limit, offset, id } = data;
    //             response = await fetch(`controllers/`, {
    //                 method: method,
    //                 headers: headers,
    //                 body: JSON.stringify({
    //                     class: category,
    //                     action: action,
    //                     data: data,
    //                     view: view ?? false,
    //                     limit: limit ?? 0,
    //                     offset: offset ?? 0,
    //                     id: id ?? 0
    //                 })
    //             });
    //         }
    //         response = await this.#returResponseByHeaderType(response);
    //     } catch (error) {
    //         response = new BaseResponse();
    //         response.status = false;
    //         response.message = error?.message ?? 'שגיאה לא ידועה';
    //     }
    //     if(response.status == 401) {
    //         const errMessage = response?.message ?? 'יתכן שהסתיים תוקפו של החיבור למערכת, יש להתחבר מחדש';
    //         new Notifier('', errMessage, 'danger');
    //         const ConfirmModal = new ConfirmActionModal();
    //         ConfirmModal.setTitle('גישה לא מורשת');
    //         ConfirmModal.setContent(errMessage);
    //         ConfirmModal.setActionButton('התחברות מחדש', 'primary');
    //         ConfirmModal._buttons[0]?.addEventListener('click', () => window.reload());
    //         ConfirmModal.apply();
    //     }
    //     return response
    // }

    /**
     * 
     * @param {Response} response 
     * @returns {BaseResponse|string}
     */
    static async #returResponseByHeaderType(Response) {
        const JSONType = /application\/json/i;
        const contentType = Response.headers.get('Content-Type');
        try {
            return JSONType.test(contentType) ?
                await Response.json() :
                await Response.text();
        } catch (error) {
            const ErrorResponse = new BaseResponse();
            ErrorResponse.status = false;
            ErrorResponse.message = error?.message ?? 'חלה שגיאה בקריאת הנתונים מהשרת';
        }
        return BaseResponse;
    }

    /**
     * 
     * @param {string} url 
     * @param {string} type 
     * @param {*} data 
     * @param {object} headers 
     * @returns {Promise<BaseResponse>}
     */
    static async sendFetch(url, type = 'GET', data = {}, headers = { 'Content-Type': 'application/json' }) {
        const RequestInit = {
            method: type,
            headers: headers,
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
        };

        if (type === 'POST') {
            if (data instanceof FormData) {
                delete RequestInit.headers['Content-Type']; // Let the browser set it automatically
                RequestInit.body = data;
            } else {
                RequestInit.body = JSON.stringify(data);
            }
        }
        return await fetch(url, RequestInit)
            .then(response => {
                switch (response.status) {
                    case 200:
                    case 400:
                    case 401:
                        return response.json();
                    case 404: return Promise.reject({ status: 404, message: 'Endpoint not found' });
                    default: return Promise.reject({ status: response.status, message: response.statusText });
                }
            })
            .then(data => {
                const Response = new BaseResponse();
                Response.success = data?.success ?? false;
                Response.status = data?.status ?? 0;
                Response.message = data?.message ?? '';
                if (data?.data) Response.data = data.data;
                return Response;
            })
            .catch(error => {
                const Response = new BaseResponse();
                Response.success = false;
                Response.status = error?.status ?? 0;
                Response.message = error.message ?? 'שגיאה לא ידועה';
                return Response;
            });
    }


    /**
     * 
     * @param {string} url 
     * @param {string} type 
     * @param {string} params 
     * @param {Function} callback 
     * @param {Function} callback2 
     * @returns 
     */
    static httpRequest(url, type, params, callback, callback2) {
        if (!callback) {
            return new Promise((reslove, reject) => {
                const http = new XMLHttpRequest();
                http.open(type, url, false);
                http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                http.onreadystatechange = function () {
                    if (http.readyState == 4 && http.status == 200) {
                        if (!Utilities.sessionIsTimeOut(http.responseText)) reslove(http.responseText);
                        else reject("The session is timed out, relogin is required");
                    } else reject(`The XML http request failed, http status is ${http.status}`);
                }
                http.send(params);
            }).catch((reason) => console.error(reason));
        } else {
            const http = new XMLHttpRequest();
            http.open(type, url, false);
            http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            http.onreadystatechange = function () {
                if (http.readyState == 4 && http.status == 200) {
                    callback(http.responseText);
                    if (callback2) callback2();
                }
            }
            http.send(params);
        }
    }

    /**
     * Function to check if a string is a valid JSON
     * @param {string} str 
     * @returns {boolean}
     */
    static isJson(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    static isNumeric(number) {
        return !isNaN(parseFloat(number)) && isFinite(number);
    }

    static formatCardNumber(cardNumber) {
        return cardNumber.replace(/\D/g, '').replace(/(\d{4})/g, '$1 ').trim();
    }

    /**
     * 
     * @param {string} number 
     * @returns {{ type: number, class: string, name: string }}
     */
    static getCreditCardTypeDetails(number) {
        const response = { type: '', class: '', name: '' };
        if (/^4[0-9](?:[0-9]{3})?$/.test(number)) {
            response.type = 1;
            response.class = 'visa';
            response.name = 'Visa';
        } else if (/^(?:5[1-5][0-9]{14})$/.test(number)) {
            response.type = 2;
            response.class = 'mastercard';
            response.name = 'Mastercard';
        } else if (/^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/.test(number)) {
            response.type = 4;
            response.class = 'discover';
            response.name = 'Discover';
        } else if (/^(?:3[47][0-9]{13})$/.test(number)) {
            response.type = 8;
            response.class = 'amex';
            response.name = 'American Express';
        } else if (/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/.test(number)) {
            response.type = 64;
            response.class = 'diners';
            response.name = 'Diners Club';
        } else {
            response.type = 0;
            response.class = 'credit-card';
            response.name = 'Credit Card';
        }
        return response;
    }

    static advancedIf(StringState, paramToCompere) {
        if (!paramToCompere) {
            switch (StringState) {
                case '1': return true;
                case '0': return false;
                case 'true': return true;
                case 'false': return false;
                case true: return true;
                case false: return false;
                default: return false;
            }
        } else return StringState === paramToCompere ? true : false;
    }


    /**
     * @param {string} tag 
     * @param {string[]} classes 
     * @param {object[]} attributes 
     * @param {string} innerHTML 
     * @returns {HTMLElement}
     */
    static createElement(tag, classes, attributes, innerHTML) {
        const element = document.createElement(tag);
        if (classes && classes.length > 0) classes.forEach(className => element.classList.add(className));
        if (attributes && attributes.length > 0) attributes.forEach(attribute => Object.entries(attribute).forEach(([key, value]) => element.setAttribute(key, value)));
        if (innerHTML) element.innerHTML = innerHTML;
        return element;
    }


    static elementCheckValidity(elem) {
        let errors = 0;
        if (elem instanceof HTMLInputElement || elem instanceof HTMLSelectElement || elem instanceof HTMLTextAreaElement) {
            const element = elem;
            element.classList.remove('valid', 'invalid');
            if (element instanceof HTMLInputElement) {
                if (!element.checkValidity()) errors++;
                if (element.required) if (element.value.length < 1) errors++
                if (element.min > 0) if (element.value < element.min) errors++;
                if (element.max > 0) if (element.value > element.max) errors++;
                if (element.minLength) if (element.value.length < element.minLength) errors++;
                if (element.maxLength) if (element.value.length > element.maxLength) errors++;
                if (element.type === 'email') if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(element.value)) errors++
                if (element.name === 'phone') if (!/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/.test(element.value)) errors++;
            }
            if (element instanceof HTMLSelectElement) if (element.required) if (element.value.length < 1) errors++
            if (errors > 0) element.classList.add('invalid');
            else (element.classList.remove('invalid'));

        } else return false;
    }

    static selectAllInputValue(inputElement) {
        if (inputElement instanceof HTMLInputElement ||
            inputElement instanceof HTMLTextAreaElement) {
            inputElement.select();
        } else return "Paramater must be the type of HTMLInputElement or HTMLTextAreaElement."
    }

    static targetParentElement(Element, ParentSelector) {
        return Element.closest(ParentSelector);
    }

    static getElementByName(wrapper, name) {
        return wrapper.querySelector(`[name=${name}]`);
    }

    static getHebrewDayName(EnglishDayname) {
        switch (EnglishDayname) {
            case 'Sun': return 'יום א';
            case 'Mon': return 'יום ב';
            case 'Tue': return 'יום ג';
            case 'Wed': return 'יום ד';
            case 'Thu': return 'יום ה';
            case 'Fri': return 'יום ו';
            case 'Sat': return 'יום ש';
        }
    }

    static getDifferentDate(date, daysDifference) {
        if (!date) date = new Date(); else date = new Date(date);
        if (typeof (daysDifference) !== "number") daysDifference = parseInt(daysDifference);
        return new Date(date.setDate(date.getDate() + daysDifference));
    }

    static reverseDateFormat(dateString, seperator) {
        let p = dateString.split(/\D/g);
        seperator ? seperator : seperator = '-';
        return [p[2], p[1], p[0]].join(seperator)
    }

    static totalOfNights(DateFrom, DateTo) {
        if (DateFrom !== Date) DateFrom = new Date(DateFrom);
        if (DateTo !== Date) DateTo = new Date(DateTo);
        return Math.round((DateTo.getTime() - DateFrom.getTime()) / (1000 * 3600 * 24));
    }

    static triggerElementEvent(element, eventAsString) {
        let changeEvent = new Event(eventAsString);
        element.dispatchEvent(changeEvent);
    }

    /**
     * Returns a loading rotated circle animation image
     * @param {array} classesArray 
     * @param {int} width 
     * @param {int} height 
     * @returns {HTMLImageElement}
     */
    static loadingCircle(classesArray, width, height) {
        const circle = document.createElement('img');
        circle.src = 'images/loading-circle.png';
        circle.classList.add('loading-circle');
        if (width) circle.width = width;
        if (height) circle.height = height;
        if (Array.isArray(classesArray)) classesArray.forEach(className => circle.classList.add(className));
        return circle;
    }

    static passwordEyeToggle(event) {
        const et = event.target.classList.contains('passwordEye') ?
            event.target : event.target.closest('.passwordEye')
        let inputElement = et.parentElement.querySelector('input[passwordEye]');
        inputElement.type = inputElement.type === 'password' ? 'text' : 'password';
        et.classList.contains('active') ? et.classList.remove('active') : et.classList.add('active');
    }

    static checkboxCounter(context, outputTarget) {
        if (context instanceof HTMLElement) {
            let counter = 0;
            context.querySelectorAll('input[type=checkbox]').forEach(checkbox => checkbox.checked ? counter++ : null);
            if (outputTarget) outputTarget instanceof HTMLInputElement ?
                outputTarget.value = counter : outputTarget.innerHTML = counter;
            else return counter;
        } else "context does not a type of HTMLElement";
    }

    static insertDropdownCheckboxs(dropdownElement, valuesArray, emptyDropdown) {
        if (dropdownElement instanceof HTMLElement) {
            if (Array.isArray(valuesArray)) {
                if (emptyDropdown) dropdownElement.innerHTML = "";
                valuesArray.forEach(curretValue => {
                    const checkbox = { body: null, input: null, label: null };
                    checkbox.body = this.createElement('li', ['form-check']);
                    checkbox.input = this.createElement('input', ['form-check-input'], [{ type: 'checkbox' }, { name: curretValue }]);
                    checkbox.label = this.createElement('label', ['form-check-label'], [{ for: checkbox.input.name }], curretValue);
                    checkbox.body.appendChild(checkbox.input);
                    checkbox.body.appendChild(checkbox.label);
                    dropdownElement.appendChild(checkbox.body);
                });
            } else return `Parameter 2 type shound be an array ${typeof valuesArray} given`;
        } else return `Parameter 1 type should be HTMLElement ${typeof valuesArray} given`;
    }

    static insertSelectOptions(selectElement, valuesArray) {
        valuesArray.forEach((optionValue, i) => {
            const option = document.createElement('option');
            if (i == 0) option.selected;
            if (typeof optionValue === 'string') {
                optionValue.length > 0 ? option.value = optionValue : null;
                option.innerText = optionValue;
            } else if (typeof optionValue === 'object') {
                Object.values(optionValue)[0].toString().length > 0 ? option.value = Object.values(optionValue)[0] : null;
                Object.values(optionValue)[1].toString().length > 0 ? option.innerText = Object.values(optionValue)[1] : '';
            }
            typeof selectElement === 'object' ?
                selectElement.appendChild(option) :
                document.querySelector(selectElement).appendChild(option);
        });
    }

    static validateSelectElement(element, property) {
        if (property) {
            for (let i = 0; i < element.options.length; i++) {
                if (element.options[i].value == property)
                    element.options[i].selected = true;
                break;
            }
        } else element.value = element.options[0].value;
    }

    /**
     * Set value to select element and trigger change event
     * @param {HTMLSelectElement} selectElement
     * @param {string} value
     */
    static setAndActivateSelectElement(selectElement, value) {
        selectElement.value = value;
        selectElement.dispatchEvent(new Event('change'));
    }

    /**
     * Get countries data from the server
     * @returns {Promise<object[]>}
     */
    static async getCountriesData() {
        return await this.rest('countries/get').then((response) => response?.data ?? []);
    }


    /**
     * @param {{status: boolean, message: string, selector: string, emptyAtStart: boolean}} response 
     * @param {boolean} emptyAtEnd 
     */
    static serverResponse(response, emptyAtEnd) {
        response = typeof response === 'string' ? JSON.parse(response) : response;
        response = typeof response === 'string' ? JSON.parse(response) : response;
        if (response.emptyAtStart) document.querySelector(response.selector).innerHTML = "";
        if (response.status) {
            let alertElement = document.createElement('div');
            alertElement.classList.add('alert', 'alert-success');
            alertElement.setAttribute('role', 'alert');
            alertElement.innerText = response.message;
            response.emptySelectorBefore ? document.querySelector(response.selector).innerHTML = "" : null;
            document.querySelector(response.selector).appendChild(alertElement);
            document.querySelector(response.selector).scrollIntoView({ block: 'center', behavior: 'smooth' });
            if (emptyAtEnd) setTimeout(() => { document.querySelector(response.selector).innerHTML = "" }, 2500);
        }
        else {
            response.emptySelectorBefore ? document.querySelector(response.selector).innerHTML = "" : null;
            let alertElement = document.createElement('div');
            alertElement.classList.add('alert', 'alert-danger', 'mb-1');
            alertElement.setAttribute('role', 'alert');
            alertElement.innerText = response.message;
            document.querySelector(response.selector).appendChild(alertElement);
            response.errors.forEach(error => {
                let alertElement = document.createElement('div');
                alertElement.classList.add('alert', 'alert-danger', 'mb-1');
                alertElement.setAttribute('role', 'alert');
                alertElement.innerText = error;
                document.querySelector(response.selector).appendChild(alertElement);
            });
            document.querySelector(response.selector).scrollIntoView({ block: 'center', behavior: 'smooth' });
        }
    }


    static sessionIsTimeOut(responseText) {
        if (/blocked/i.test(responseText) && !document.getElementById('confirm-general-modal').classList.contains('show')) {
            new ConfirmModal("תם תוקף החיבור למערכת", "יתכן שחלק מהפעולות לא יעבדו כהלכה ולכן נדרש להתחבר מחדש.<br/> נתונים לא שמורים שנרשמו לאחרונה יאבדו, האם לחדש את החיבור?", "חידוש", "window.location.reload()", null);
            const sessionModal = new bootstrap.Modal(document.getElementById('confirm-general-modal'));
            document.querySelector('#confirm-general-modal .modal-dialog').classList.replace('modal-lg', 'modal-md');
            sessionModal.show();
            return true;
        } else return false;
    }

    static dateBuilder(givenTime, daysToAdd) {
        if (!givenTime) {
            let today = new Date;
            let day = today.getDate().toString().length < 2 ? '0' + today.getDate().toString() : today.getDate().toString(),
                month = today.getMonth().toString().length < 2 ? '0' + (today.getMonth() + 1).toString() : (today.getMonth() + 1).toString();
            return today.getFullYear() + "-" + month + "-" + day;
        }
        else {
            let departure = new Date(givenTime);
            departure.setDate(departure.getDate() + daysToAdd);
            let day = departure.getDate().toString().length < 2 ? '0' + departure.getDate().toString() : departure.getDate().toString(),
                month = departure.getMonth().toString().length < 2 ? '0' + (departure.getMonth() + 1).toString() : (departure.getMonth() + 1).toString();
            return departure.getFullYear() + "-" + month + "-" + day;
        }
    }

    /**
     * Check if the mouse is over a given element
     * @param {HTMLElement} element 
     * @param {number} mouseX 
     * @param {number} mouseY 
     * @returns {boolean}
     */
    static isMouseOverElement(element, mouseX, mouseY) {
        if (!element) return false; // Ensure the element exists

        const rect = element.getBoundingClientRect();

        // Get element's boundary coordinates
        const elementLeft = rect.left;
        const elementTop = rect.top;
        const elementRight = rect.right;
        const elementBottom = rect.bottom;

        // Check if mouse coordinates are within the element boundaries
        return mouseX >= elementLeft && mouseX <= elementRight && mouseY >= elementTop && mouseY <= elementBottom;
    }
}