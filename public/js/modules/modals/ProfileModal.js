import { Utilities } from "../Utilities.js";
import { Notifier } from "../Notifier.js";
import { FormHandler } from "../FormHandler.js";
import { UserInterface } from "../UI.js";
import { AuthService } from "../AuthService.js";


export default class ProfileModal {

    selector = '#profileModal';
    details = document.querySelector(`${this.selector} #profilePreview`);
    form = document.querySelector(`${this.selector} form#editUserForm`);
    modal = new bootstrap.Modal(document.querySelector(this.selector));
    SOUNDS = {
        SHOW: new Audio('/public/sounds/modal-show.mp3'),
    }


    constructor() {
        this.initEvents();
    }


    /**
     * The function applies the modal iframe attributes when modal is triggered
     * @param {MouseEvent} e
     */
    async #showingPreviewModal(e) {
        this.SOUNDS.SHOW.play();
        const altTarget = e?.relatedTarget.closest('button') ?? e.relatedTarget.closest('a') ?? e.relatedTarget;
        const target = e.relatedTarget instanceof HTMLButtonElement ? e.relatedTarget : altTarget;
        const player = target.getAttribute('player') ?? null;
        this.details.querySelectorAll('.user-info-col p.details').forEach(el => el.innerHTML = UserInterface.LOADER_HTML);
        const response = await Utilities.sendFetch('api/users/profile' + (player ? `/${player}` : ''));
        if(!(response.success && response.status === 200 && response.data)) {
            new Notifier('', data.message, 'danger', 3000, 'end', 'end');
            return;
        }
        const { user, rank } = response.data;
        this.details.querySelector('.full-name p.details').innerHTML = `${user.fname} ${user.lname}`;
        this.details.querySelector('.email p.details').innerHTML = user.email;
        this.details.querySelector('.gender p.details').innerHTML = !user?.gender ? 'Unknown' : (user.gender == 1 ? 'Male' : 'Female');
        this.details.querySelector('.rank p.details').innerHTML = !rank?.rank ? 'Unknown' : rank.rank;
        this.details.querySelector('.score p.details').innerHTML = rank?.score;
        this.details.querySelector('.registred p.details').innerHTML = user.since;
        this.details.querySelector('img').alt = `${user.fname} ${user.lname}`;
        this.details.querySelector('.img').style.backgroundImage = `url(${user?.image ?? 'public/images/user.png'})`;

        this.form.querySelector('input[name="first_name"]')?.setAttribute('value', user.fname);
        this.form.querySelector('input[name="last_name"]')?.setAttribute('value', user.lname);
        this.form.querySelector('select[name=gender]')?.setAttribute('value', user.gender);
        if(user.image) this.form.querySelector('img')?.setAttribute('src', user.image);

        const isLoggedUser = AuthService.getLoggedUser()?.id == user.id;
        this.details.querySelector('button.edit').classList.toggle('d-none', !isLoggedUser);
        this.details.querySelector('#RegistrationModalLongTitleYou').classList.toggle('d-none', !isLoggedUser);
        this.details.querySelector('#RegistrationModalLongTitleOther').classList.toggle('d-none', isLoggedUser);
    }


    /**
     * The function sets default values while the preview modal is closing
     */
    #hidingPreviewModal() {
        this.form.reset();
        FormHandler.cleanFormInputs(this.form);
        this.details.querySelectorAll('.user-info-col p.details').forEach(el => el.innerHTML = '');
    }

    /**
     * Login the user
     * @param {SubmitEvent} event 
     * @returns {void}
     */
    async update(event) {
        try {
            event.preventDefault();
            event.submitter.disabled = true
            if(!FormHandler.checkFormValidity(this.form)) {
                event.submitter.disabled = false;
                new Notifier('', 'Please fill all the required fields properly', 'danger', 3000);
                return;
            }

            new Notifier('', 'Updating user...', 'info', 1500);
            const formData = new FormData(this.form);
            const headers = { 'Content-Type': 'multipart/form-data' };
            const response = await Utilities.sendFetch('api/users/update', 'POST', formData, headers);

            if(!(response.success && response.status === 200)) { 
                new Notifier('', response.message, 'danger', 3000);
                event.submitter.disabled = false;
                return;
            }
            new Notifier('', response.message, 'success');
            event.submitter.disabled = false;
            setTimeout(this.#switchModes(), 500);;
            this.modal.hide();
        } catch (error) {
            new Notifier('', error?.message ?? error, 'danger', 3000);
            event.submitter.disabled = false;
        }
    }

    /**
     * switch modes between edit and view
     */
    #switchModes(e) {
        if(e instanceof Event) e.preventDefault();
        this.details.classList.toggle('active');
        this.form.classList.toggle('active');
    }

    /**
     * This function triggers the password fields
     * @param {MouseEvent} event
     */
    #editPasswordTrigger(event) {
        if (event.target.value == "0") {
            this.form.querySelector('fieldset.passwords').setAttribute('disabled', 'disabled');
            this.form.querySelector('.passwords-row').setAttribute('expanded', 'false');
        } else {
            this.form.querySelector('fieldset.passwords').removeAttribute('disabled');
            this.form.querySelector('.passwords-row').setAttribute('expanded', 'true');
            this.form.querySelector('input[name="password"]').focus();
        }
    }
    
    /**
     * @returns {boolean}
     */
    initEvents() {
        this.form.handler = new FormHandler(this.form);
        this.form.addEventListener('submit', (event) => this.update(event));
        this.modal._element.addEventListener('show.bs.modal', (e) => this.#showingPreviewModal(e));
        this.modal._element.addEventListener('hidden.bs.modal', () => this.#hidingPreviewModal());
        this.details.querySelector('button.edit').addEventListener('click', (e) => this.#switchModes(e));
        this.form.querySelector('button.cancel').addEventListener('click', (e) => this.#switchModes(e));
        this.form.querySelector('select[name="change_password"]')?.addEventListener('change', (e) => this.#editPasswordTrigger(e));
        this.form.querySelector("button.image-button")?.addEventListener("click", (e) => UserInterface.chooseImageBeforeUpload(e));
        this.form.querySelector("input[name=image]")?.addEventListener("change", (e) => UserInterface.previewImageBeforeUpload(e));

        return true;
    }
}