<?php
declare(strict_types=1);

/**
 * Class Hangman
 * Herný engine
 */
class Hangman
{
    private $words = ['MAMA', 'OTEC', 'KEFA', 'VEDA', 'LAVICA', 'JABLKO', 'UDICA', 'DOLINA'];
    private $wantedWord; // hľadané slovo
    private $playedWord; // aktuálny stav hry
    private $failedAttempts; // počet pokusov
    private $usedChars = []; // zoznam už hadáných písmen

    /**
     * Hangman konštruktor
     * @param bool $initialized Má sa hra inicializovať a začíname odznovu?
     */
    public function __construct(bool $initialized)
    {
        if ($initialized) {
            $this->wantedWord = $_SESSION['wantedWord'];
            $this->playedWord = $_SESSION['playedWord'];
            $this->failedAttempts = $_SESSION['attempts'];
            $this->usedChars = $_SESSION['usedChars'];
        } else {
            $this->failedAttempts = $_SESSION['attempts'] = 0;
            $this->wantedWord = $_SESSION['wantedWord'] = $this->selectWord();
            $this->playedWord = $_SESSION['playedWord'] = str_repeat('-', strlen($_SESSION['wantedWord']));
            $this->usedChars = $_SESSION['usedChars'] = [];
        }
    }

    /**
     * Vyberie slovo z poľa slov, ktoré budeme hľadať
     * @return string
     */
    public function selectWord(): string
    {
        return $this->words[rand(0, count($this->words) - 1)];
    }

    /**
     * Otestuje, či sa hádané písmeno nachádza v hľadanom slovo a nahradí
     * pomlčky za písmeno na správnom, alebo správnych miestach
     * @param string $testedChar Testované písmeno
     */
    public function testChar(string $testedChar): void
    {
        if ($this->gameStatus() == 'in progress') {
            $found = false;
            for ($i = 0; $i < strlen($this->wantedWord); $i++) {
                if ($testedChar == $this->wantedWord[$i]) {
                    $this->playedWord[$i] = $testedChar;
                    $found = true;
                }
            }
            $_SESSION['playedWord'] = $this->playedWord;
            $_SESSION['attempts'] = $found ? $this->failedAttempts : ++$this->failedAttempts;
            array_push($_SESSION['usedChars'], $testedChar);
            $this->usedChars = $_SESSION['usedChars'];
        }
    }

    /**
     * Vráti, či už je koniec hry
     * @return bool Koniec hry
     */
    public function gameStatus(): string
    {
        if ($this->playedWord == $this->wantedWord) {
            return 'won';
        } else if ($this->failedAttempts >= 10) {
            return 'lost';
        } else {
            return 'in progress';
        }
    }

    /**
     * Getter pre aktuálny stav hry
     * @return string Aktuálny stav hry (čiastočne uhádnuté slovo a pomlčky)
     */
    public function getPlayedWord(): string
    {
        return $this->playedWord;
    }

    /**
     * Getter pre počet pokusov
     * @return int
     */
    public function getFailedAttempts(): int
    {
        return $this->failedAttempts;
    }

    /**
     * Getter pre už hádané znaky
     * @return array|mixed
     */
    public function getUsedChars(): array
    {
        return $this->usedChars;
    }
}