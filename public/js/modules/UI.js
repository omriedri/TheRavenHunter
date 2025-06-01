import { AuthService } from "./AuthService.js";
import { Home } from "./Home.js";
import { MainInstance } from "./Main.js";
import { Records } from "./Records.js";

export class UserInterface {

    static ELEMENTS = {
        logo : document.querySelector('header .cover'),
        nav: document.querySelector('nav'),
        navbar: document.querySelector('nav .nav-container'),
        profile: document.querySelector('profile'),
        pages: document.querySelectorAll('.page'),
        sidebar_btn: document.querySelector('nav .trigger-button'),
        logout_btns: document.querySelectorAll('[data-action="logout"]')
    }

    static PAGES = {
        HOME: document.querySelector('body > home.page'),
        GAME: document.querySelector('body > game.page'),
        RECORDS: document.querySelector('body > records.page'),
        SETTINGS: document.querySelector('body > settings.page'),
        ABOUT: document.querySelector('body > about.page'),
    }

    player = {
        id: 0,
        name: '',
        avatar: ''
    }


    static = UserInterface;

    static LOADER_HTML = `<div id="registerLoading" class="loadingio-spinner-spinner-t4i2yjwkfx active modal-loader"><div class="ldio-bsei7j58pwk"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>`;


    constructor() {
        this.init();
    }

    init() {
        this.#setBodyView();
        this.#menuNavigation();
        this.#hidNavbarOnClick();
        this.#implementLogoutButtons();
        Records.ELEMENTS.select.addEventListener('change', () => Records.print());
        this.initEvents();
    }

    /**
     * Initialize events for the user interface
     */
    initEvents() {
        window.addEventListener('resize', () => this.#setBodyView());
        this.static.ELEMENTS.sidebar_btn?.addEventListener('click', () => this.revealSidebar());
    }

    /**
     * Reveal the sidebar
     */
    revealSidebar() {
        if (this.static.ELEMENTS.navbar.classList.contains('hide')) {
            this.static.ELEMENTS.navbar.classList.remove('hide');
            this.static.ELEMENTS.nav.classList.add('active');
        }
    }

    /**
     * Set the visibility of the navbar
     * @param {boolean|null} isVisible 
     */
    static toggleNav(isVisible = null) {
        isVisible === null ?
            this.ELEMENTS.nav.classList.toggle('active') :
            this.ELEMENTS.nav.classList.toggle('active', isVisible);
    }

    /**
     * Set the visibility of the profile
     * @param {boolean|null} isVisible 
     */
    static toggleProfile(isVisible = null) {
        isVisible === null ?
            this.ELEMENTS.profile.classList.toggle('d-none') :
            this.ELEMENTS.profile.classList.toggle('d-none', !isVisible);
    }

    /**
     * Navigate to the page
     * @param {HTMLElement} Page
     */
    static navigateTo(Page) {
        const { GameInstance } = MainInstance;
        this.ELEMENTS.pages.forEach(item => item.classList.remove('active'));
        Page?.classList.add('active');
        if(Page !== this.PAGES.GAME && GameInstance.isRunning()) {
            GameInstance.abort();
        }
        if(Page !== this.PAGES.HOME) {
            this.ELEMENTS.logo.classList.add('minimized');
        } else {
            this.ELEMENTS.logo.classList.remove('minimized');
        }

    }


    #menuNavigation() {
        UserInterface.ELEMENTS.nav.querySelectorAll('.menu-item-list button').forEach(item => {
            item.addEventListener('click', (e) => {
                const target = e.target instanceof HTMLButtonElement ? e.target : e?.target.closest('button');
                const selected = target.getAttribute('data-target');
                const Page = this.static.PAGES[selected.toUpperCase()];
                if (!Page) return;
                this.static.navigateTo(Page);
                if(Page === this.static.PAGES.RECORDS) {
                    Records.print();
                }
            });
        });
    }

    /**
     * Hide the navbar when a menu item is clicked
     */
    #hidNavbarOnClick() {
        if (!this.static.isMobile()) return;
        this.static.ELEMENTS.nav.querySelectorAll('.menu-item button').forEach(MenuItem => {
            MenuItem.addEventListener('click', () => {
                this.static.ELEMENTS.nav.classList.remove('active');
                this.static.ELEMENTS.navbar.classList.add('hide');
            }); 
        });
    }

    #implementLogoutButtons() {
        this.static.ELEMENTS.logout_btns.forEach(item =>
            item?.addEventListener('click', () =>
                AuthService.logout().finally(() =>
                    Home.switchVisibilityByUserState(false))
            )
        );
    }

    /**
     * Choose image before upload, trigger the input[type="file"] click event while image button is clicked
     * @param {MouseEvent} e
     */
    static chooseImageBeforeUpload(e) {
        e.preventDefault();
        const wrapper = e.target.closest('.image-wrapper');
        const input = wrapper?.querySelector('input[type="file"]'); 
        input?.click();
    }

    /**
     * Preview image before upload
     * @param {Event} e
     */
    static previewImageBeforeUpload(e) {
        e.preventDefault();
        const input = e.target;
        const wrapper = input.closest('.image-wrapper');
        const image = wrapper.querySelector('img');
        if (!e.target.files.length) {
            image.src = '/public/images/imageUpload.jpg';
            input.value = '';
            return;
        }
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            image.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }

    #setBodyView() {
        if (this.static.isMobile()) {
            document.body.classList.add('mobile-view');
            document.body.classList.remove('desktop-view');
        } else {
            document.body.classList.remove('mobile');
            document.body.classList.add('desktop-view');
        }
    }


    static isMobile() {
        return window.innerWidth <= 768;
    }
}