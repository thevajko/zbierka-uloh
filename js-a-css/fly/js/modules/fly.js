import {Timer} from "./timer.js";

class Fly {

    element = null;

    constructor(interval = 1000) {
        this.timer = new Timer(interval);
        this.createElement();
        this.timer.callback = () => this.changePosition();
    }

    // Change and rotate the position of the fly (and show a bloody stain)
    changePosition() {
        this.element.style.left = Math.random() * (window.innerWidth - this.element.offsetWidth) + "px";
        this.element.style.top = Math.random() * (window.innerHeight - this.element.offsetHeight) + "px";
        this.element.style.transform = 'rotate(' + (Math.random() * 360) + 'deg)';
        this.element.classList.remove("fly_killed");
        //this.timer.start();
    }

    // Show the fly and start the timer
    showElement() {
        this.timer.start();
        this.element.classList.remove("fly_killed");
        //this.element.classList.add("fly_killer");
        this.element.style.display = "block";
    }

    // Hide the fly and stop the timer
    hideElement() {
        this.timer.stop();
        this.element.classList.remove("fly_killer");
        this.element.style.display = "none";
    }

    // Create a DOM element of the fly (visual appearance)
    createElement() {
        this.element = document.createElement('div');
        this.element.className = 'fly';
        this.changePosition();
        this.hideElement();
        document.body.appendChild(this.element);
    }

    // Set the onClick callback to a function from outside (setter method) and change image of the fly to a bloody stain
    set onClick(callback) {
        this.element.onclick = () => {
            if (!this.element.classList.contains(' fly_killed')) {
                this.element.classList.add("fly_killer");
                this.element.classList.add("fly_killed");
                this.timer.start();
                callback();
            }
        };
    }

}

export {Fly};