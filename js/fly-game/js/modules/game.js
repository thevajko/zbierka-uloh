import {Timer} from "./timer.js";
import {Fly} from "./fly.js";

// Main class of the game
class Game {

    gameDuration = 30;
    gameSeconds = 0;
    numOfFlies = 5;
    score = 0;

    timer = new Timer();
    totalAttempts = 0;
    // Array of flies
    flies = [];

    constructor() {
        // This sets the game countdown timer
        this.timer.callback = () => this.gameTick();

        // If is DOM complete, set all the event handlers
        document.addEventListener("DOMContentLoaded", (event) => {

            // This sets the onClick handler of the Start button
            document.getElementById("start").onclick = () => this.start();

            // Add a new attempts to total attempts
            document.querySelector('.playground').onclick = (event) => {
                // Don't count in the click on start game button
                if (event.target.id === 'start') return;
                if (this.gameSeconds > 0) {
                    this.totalAttempts++;
                    this.redrawScore();
                }
            }

            for (let i = 1; i <= this.numOfFlies; i++) {
                // Change flies position randomly
                this. flies [i] = new Fly(758 + Math.random() * 743);
                // In this case we need the target of the event as well
                this.flies[i].onClick = () => this.flyHit();
            }
        });

    }

    // Show score on the scoreboard
    redrawScore() {
        document.getElementById("score").innerText = this.score.toString() + '/' + this.totalAttempts.toString();
    }

    // One second of the game has elapsed
    gameTick() {
        if (this.gameSeconds > 0) {
            this.gameSeconds--;
        } else {
            this.timer.stop();
            // Hide all flies
            this.flies.forEach(fly => fly.hideElement());
            // Hide fly killer
            document.querySelector('.playground').classList.remove('fly_killer');
        }
        document.getElementById("timer").innerText = this.gameSeconds.toString();
    }

    // The fly was hit by the player
    flyHit() {
        this.score++;
        this.totalAttempts++;
        this.redrawScore();
    }

    // Start a new game
    start() {
        this.gameSeconds = this.gameDuration;
        this.score = this.totalAttempts = 0;
        this.redrawScore();
        this.timer.start();
        // Show fly killer
        document.querySelector('.playground').classList.add('fly_killer');
        // Show all flies
        this.flies.forEach(fly => {
            fly.showElement();
        });
    }
}

export {Game};