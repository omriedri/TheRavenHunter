export class Notifier {

    body;
    title;
    message;
    closeBtn;

    /**
     * 
     * @param {string} title your message | default: none;
     * @param {string} message your message | default: empty;
     * @param {string} theme from list, see docs | default: default.
     * @param {number} duration in milliseconds | defualt: 2500.
     * @param {string} x_index start/end | defualt: 'start'
     * @param {string} y_index start/end | defualt: 'end'
     * @return {void} void
     */
    constructor(title, message, theme, duration, x_index, y_index) {
        this.setElements(title, message);
        this.setPosition(x_index, y_index);
        this.setStyle(theme);
        this.setDuration(duration);
        this.setDirection();
        this.setAnimation();
        this.setOrder();
        this.run();
    }
        
    setElements(title, message) {
        let id = Math.random().toString(36).substring(2, 15);
        this.body = this.createElement('div', [`notifier`]);
        this.body.setAttribute('id', id);
        this.title = typeof title === 'string' ? this.createElement('strong', ['title'], null, title) : null;
        this.message = this.createElement('span', ['message'], null, typeof message === 'string' ? message : "");
        if (this.title) this.body.appendChild(this.title);
        this.closeBtn = this.createElement('span', ['x', 'float-end'], null, 'X')
        this.closeBtn.addEventListener('click', ()=> this.body.remove());
        this.body.appendChild(this.closeBtn);
        this.body.appendChild(this.message);
    }

    setPosition(X, Y) {
        switch (X) {
            case 'start': this.body.classList.add('px-' + X); break;
            case 'end': this.body.classList.add('px-' + X); break;
            default: this.body.classList.add('px-start'); break;
        }
        switch (Y) {
            case 'start': this.body.classList.add('py-' + Y); break;
            case 'end': this.body.classList.add('py-' + Y); break;
            default: this.body.classList.add('py-end'); break;
        }
    }

    setStyle(styleName) {
        switch (styleName) {
            case 'primary': this.body.classList.add('s-primary'); break;
            case 'secondary': this.body.classList.add('s-secondary'); break;
            case 'success': this.body.classList.add('s-success'); break;
            case 'danger': this.body.classList.add('s-danger'); break;
            case 'warning': this.body.classList.add('s-warning'); break;
            case 'info': this.body.classList.add('s-info'); break;
            case 'light': this.body.classList.add('s-light'); break;
            case 'dark': this.body.classList.add('s-dark'); break;
            default: this.body.classList.add('s-default'); break;
        }
    }

    setOrder(){
        const notifiers = document.querySelectorAll('div.notifier');
        if(notifiers.length > 0){
            notifiers.forEach((notifier, i) => {
                if(notifier.style.bottom === "") notifier.style.bottom = (notifier.offsetHeight + 40).toString() + "px";
                else notifier.style.bottom = parseInt(notifier.style.bottom.replace('px', '')) + (notifier.offsetHeight + 10) + "px";
            });
        }
    }

    setDuration(time) {
         this.duration = /^\d+$/.test(time) ? parseInt(time) : 3000;
    }

    setDirection(dir = 'ltr') {
        this.body.setAttribute('dir', dir);
    }

    setAnimation() { 
        this.body.classList.add(/rtl/.test(document.dir) ? 'animate-right' : 'animate-left');
    }

    createElement(tag, classes, attributes, innerHTML) {
        const element = document.createElement(tag); 
        if (Array.isArray(classes)) try { classes.forEach(className => element.classList.add(className)); } catch (error) { console.error(error) }
        if (Array.isArray(attributes)) try { attributes.forEach((attr, i) => element.setAttribute(Object.keys(attr)[0], Object.values(attr)[0])) } catch (error) { console.error(error) }
        element.innerHTML = typeof innerHTML === 'string' ? innerHTML : "";
        return element;
    }

    run(){
        if(document.querySelector('link[href*="Notifier.css"]')){
            document.body.appendChild(this.body);
            setTimeout(() => this.body.classList.add('fadeout'), (this.duration - 300));
            setTimeout(() => { if(document.querySelector('div.notifier')) document.querySelector('div.notifier').remove(); }, this.duration);    
        }else console.error("Notifier CSS file is missing, link to it by the Notifier documention");
    }

    hide(){
        this.body.classList.add('fadeout');
        setTimeout(() => this.body.remove(), 500);
    }
}