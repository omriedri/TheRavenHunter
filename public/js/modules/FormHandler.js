export class FormHandler {

    constructor(Form) {
        if(!(Form instanceof HTMLFormElement)) {
            throw new Error('Form parameter is required');
        }
        this.Form = Form;
        this.#initFormInputsEvents();
    }

    /**
    * The function cleans the form inputs from the dirty, valid and invalid classes
    * @param {HTMLFormElement} Form
    */
    static cleanFormInputs(Form) {
        Form?.querySelectorAll('input, select').forEach((element) => {
            element.classList.remove('dirty', 'is-invalid', 'is-valid');
            element.setAttribute('initial-value', element.value);
        });
    }

    /**
     * The function returns the dirty inputs of the form
     * @param {HTMLFormElement} Form
     * @returns {NodeListOf<HTMLInputElement|HTMLSelectElement>}
     */
    static getDirtyInputs(Form) {
        return Form?.querySelectorAll('input.dirty, select.dirty');
    }

    /**
     * The function initializes the form inputs events
     * @returns {void}
     */
    #initFormInputsEvents() {
        this.Form?.querySelectorAll('input, select').forEach((element) => {
            if (element instanceof HTMLInputElement) {
                element.addEventListener('input', () => {
                    this.#handleInputDirty(element);
                    if (element.type == 'checkbox') return;
                    this.#handleInputValidity(element);
                });
            } else if (element instanceof HTMLSelectElement) {
                element.addEventListener('change', () => {
                    this.#handleInputDirty(element);
                    this.#handleInputValidity(element);
                });
            }
        });
    }

    /**
     * Handle if the input is dirty or not
     * @param {HTMLInputElement|HTMLSelectElement} element 
     * @returns {void}
     * @throws {Error} If element is not provided
     */
    #handleInputDirty(element) {
        if (!element) throw new Error('Element parameter is required');
        let elementValue = null;
        switch (element.type) {
            case 'checkbox':
                elementValue = element.checked;
                break;
            case 'select-one':
                elementValue = element.options[element.selectedIndex].value;
                break;
            default:
                elementValue = element.value;
                break;
        }
        elementValue = elementValue.toString().trim();
        if (element?.getAttribute('initial-value') == elementValue) {
            element.classList.remove('dirty');
        } else {
            element.classList.add('dirty');
        }
    }

    /**
     * Handle if the input is valid or not
     * @param {HTMLInputElement|HTMLSelectElement} element 
     */
    #handleInputValidity(element) {
        if (element.checkValidity()) {
            element.classList.remove('is-invalid');
            element.classList.add('is-valid');
        } else {
            element.classList.remove('is-valid');
            element.classList.add('is-invalid');
        }
    }

    /**
     * Checks the form validity before sending the data
     * @param {HTMLFormElement} Form
     * @returns {boolean}
     */
    static checkFormValidity(Form) {
        let invalidCases = 0;
        Form.querySelectorAll('input, select').forEach((input) => {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                invalidCases++;
            }
        });
        return invalidCases === 0;
    }
}