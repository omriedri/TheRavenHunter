export default class GameEndingModal {

    static = GameEndingModal;
    static selector = '#GameEndingModal';
    modal = new bootstrap.Modal(document.querySelector(this.static.selector));
    static ELEMENTS = {
        SCORE: document.querySelector(`${GameEndingModal.selector} #your-score`),
        TIME: document.querySelector(`${GameEndingModal.selector} #your-time`),
        BTN_EXIT: document.querySelector(`${GameEndingModal.selector} [data-action="exit"]`),
        BTN_TRY_AGAIN: document.querySelector(`${GameEndingModal.selector} [data-action="restart"]`),
    }

    constructor() {
        this.initEvents();
    }


    launch(score = 0) {
        this.modal.show();
        this.modal._element.querySelector('#your-score').innerText = score;
    }


    /**
     * The function applies the modal iframe attributes when modal is triggered
     */
    async #showingPreviewModal() {
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
    }

    
    /**
     * @returns {boolean}
     */
    initEvents() {
        this.modal._element.addEventListener('show.bs.modal', () => this.#showingPreviewModal());
        this.modal._element.addEventListener('hidden.bs.modal', () => this.#hidingPreviewModal());
        return true;
    }
}