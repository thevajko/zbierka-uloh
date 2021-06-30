// Hra Pexeso (2 hráči)
// Patrik Hrkút, 2021
// Katedra softvérových technológií
// Fakulta riadenia a informatiky 
// Žilinská univerzita v Žiline

let reversedCards = 0;
let player1GuessedCount = 0;
let player2GuessedCount = 0;
let card1 = null;
let card2 = null;
let cardSymbols = ['', 'tractor', 'truck-monster', 'truck', 'truck-pickup', 'truck-moving', 'ambulance', 'bicycle', 'bus',
    'car', 'caravan', 'motorcycle', 'shuttle-van', 'tram', 'plane', 'subway'];

function gid(elementId) {
    return document.getElementById(elementId);
}

function changePlayer(newPlayer) {
    if (newPlayer == "player1") {
        gid("player1").style.color = "green";
        gid("player2").style.color = "black";
    } else {
        gid("player1").style.color = "black";
        gid("player2").style.color = "green";
    }
}

function showScore(player, guessed) {
    gid(player + "_guessed").innerHTML = guessed;
}

// initial setup of game
function initializeGame() {
    changePlayer("player1");
    player1GuessedCount = 0;
    showScore("player1", 0);
    player2GuessedCount = 0;
    showScore("player2", 0);

    distributeCards();
}

function shuffleCards() {
    let cards = gid('board').querySelectorAll('td');
    for (i = 0; i < 100; i++) {
        let from = Math.floor(Math.random() * 30);
        let to = Math.floor(Math.random() * 30);
        [cards[from].innerHTML, cards[to].innerHTML] = [cards[to].innerHTML, cards[from].innerHTML]
    }
}

function distributeCards() {
    let board = gid("board");
    board.innerHTML = "";
    let n = 1;
    for (let i = 1; i <= 6; i++) {
        let row = document.createElement("tr");
        board.appendChild(row);
        for (let j = 1; j <= 5; j++) {
            let cell = document.createElement("td");
            cell.onclick = turnCard;
            cell.innerHTML = '<i class="fas fa-' + cardSymbols[(n > 15 ? n - 15 : n)] + ' fa-3x"></i>';
            row.appendChild(cell);
            n++;
        }
    }
    shuffleCards();
}

function hideCards(card1, card2) {
    card1.style.backgroundColor = "blueviolet";
    card1.firstChild.style.opacity = 0;
    card2.style.backgroundColor = "blueviolet";
    card2.firstChild.style.opacity = 0;
}

function turnCard() {
    if (reversedCards == 2) return;
    if (this.style.backgroundColor == "plum" || this.style.backgroundColor == "white") return;

    this.style.backgroundColor = "white";
    this.firstChild.style.opacity = 1;
    reversedCards++;

    if (reversedCards == 1) {
        card1 = this;
        return;
    }
    if (reversedCards == 2) {
        card2 = this;
        if (card1.innerHTML == card2.innerHTML) {
            card1.style.backgroundColor = "plum";
            card2.style.backgroundColor = "plum";
            if (gid("player1").style.color == 'green') {
                player1GuessedCount++;
                showScore("player1", player1GuessedCount);
            } else {
                player2GuessedCount++;
                showScore("player2", player2GuessedCount);
            }
            reversedCards = 0;
        } else {
            if (gid("player1").style.color == 'green') {
                changePlayer("player2");
            } else {
                changePlayer("player1");
            }
            setTimeout(function () {
                hideCards(card1, card2);
                reversedCards = 0;
            }, 1000);
        }
    }
}

function initialize() {
    gid("start").onclick = initializeGame;
}

window.onload = initialize;