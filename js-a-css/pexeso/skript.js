// Hra Pexeso (2 hráči)
// Patrik Hrkút, 2021
// Katedra softvérových technológií
// Fakulta riadenia a informatiky 
// Žilinská univerzita v Žiline

let pocetObratenych = 0;
let pocetUhadnutychHrac1 = 0;
let pocetUhadnutychHrac2 = 0;
let karta1 = null;
let karta2 = null;
let obrazky = ['', 'tractor', 'truck-monster', 'truck', 'truck-pickup', 'truck-moving', 'ambulance', 'bicycle', 'bus',
    'car', 'caravan', 'motorcycle', 'shuttle-van', 'tram', 'plane', 'subway'];

function gid(elementId) {
    return document.getElementById(elementId);
}

function zmenaHraca(novyHrac) {
    if (novyHrac == "hrac1") {
        gid("hrac1").style.color = "green";
        gid("hrac2").style.color = "black";
    } else {
        gid("hrac1").style.color = "black";
        gid("hrac2").style.color = "green";
    }
}

function zobrazBody(hrac, uhadnute) {
    gid(hrac + "_uhadnute").innerHTML = uhadnute;
}

// úvodné vynulovanie hry
function inicializujHru() {
    zmenaHraca("hrac1");
    pocetUhadnutychHrac1 = 0;
    zobrazBody("hrac1", 0);
    pocetUhadnutychHrac2 = 0;
    zobrazBody("hrac2", 0);

    rozdajKarty();
}

// zamiešaj karty
function zamiesajKarty() {
    let karty = gid('plocha').querySelectorAll('td');
    for (i = 0; i < 100; i++) {
        z = Math.floor(Math.random() * 30);
        na = Math.floor(Math.random() * 30);
        [karty[z].innerHTML, karty[na].innerHTML] = [karty[na].innerHTML, karty[z].innerHTML]
    }
}

// rozdaj karty
function rozdajKarty() {
    let tabulka = gid("plocha");
    tabulka.innerHTML = "";
    let cislo = 1;
    for (let i = 1; i <= 6; i++) {
        let riadok = document.createElement("tr");
        tabulka.appendChild(riadok);
        for (let j = 1; j <= 5; j++) {
            let bunka = document.createElement("td");
            bunka.onclick = obrat;
            bunka.innerHTML = '<i class="fas fa-' + obrazky[(cislo > 15 ? cislo - 15 : cislo)] + ' fa-3x"></i>';
            riadok.appendChild(bunka);
            cislo++;
        }
    }
    zamiesajKarty();
}

function skryKarty(karta1, karta2) {
    karta1.style.backgroundColor = "blueviolet";
    karta1.firstChild.style.opacity = 0;
    karta2.style.backgroundColor = "blueviolet";
    karta2.firstChild.style.opacity = 0;
}

function obrat() {
    if (pocetObratenych == 2) return;
    if (this.style.backgroundColor == "plum" || this.style.backgroundColor == "white") return;

    this.style.backgroundColor = "white";
    this.firstChild.style.opacity = 1;
    pocetObratenych++;

    if (pocetObratenych == 1) {
        karta1 = this;
        return;
    }
    if (pocetObratenych == 2) {
        karta2 = this;
        if (karta1.innerHTML == karta2.innerHTML) {
            karta1.style.backgroundColor = "plum";
            karta2.style.backgroundColor = "plum";
            if (gid("hrac1").style.color == 'green') {
                pocetUhadnutychHrac1++;
                zobrazBody("hrac1", pocetUhadnutychHrac1);
            } else {
                pocetUhadnutychHrac2++;
                zobrazBody("hrac2", pocetUhadnutychHrac2);
            }
            pocetObratenych = 0;
        } else {
            if (gid("hrac1").style.color == 'green') {
                zmenaHraca("hrac2");
            } else {
                zmenaHraca("hrac1");
            }
            setTimeout(function () {
                skryKarty(karta1, karta2);
                pocetObratenych = 0;
            }, 1000);
        }
    }
}

function initialize() {
    gid("start").onclick = inicializujHru;
}

window.onload = initialize;