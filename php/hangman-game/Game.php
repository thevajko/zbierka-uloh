<?php
declare(strict_types=1);

session_start();
require 'Keyboard.php';
require 'Hangman.php';

/**
 * Class Game
 * Samotná hra
 */
class Game
{
    private $hangman;

    /**
     * Game konštruktor
     */
    public function __construct()
    {
        $this->hangman = new Hangman(isset($_GET['char']));
    }

    /**
     * Vráti inštanciu klávesnice
     * @param int $cols
     * @return Keyboard
     */
    function getKeyboard(int $cols): Keyboard
    {
        return new Keyboard($cols, $this->hangman);
    }

    /**
     * Zahrá jedno kolo hry
     * @return string - Vráti čiastočne uhádnuté slovo a pomlčky
     */
    public function play(): string
    {
        if (isset($_GET['char'])) {
            $this->hangman->testChar($_GET['char']);
        }
        return $this->hangman->getPlayedWord();
    }

    /**
     * Vráti informáciu o stave hry (počet pokusov a či je koniec hry)
     * @return string Info o hre
     */
    public function getFailedAttempts(): int
    {
        return $this->hangman->getFailedAttempts();
    }

    public function getGameResult(): string
    {
        switch ($this->hangman->gameStatus()) {
            case 'won':
                return 'Vyhral si! Gratulujem!';
                break;
            case 'lost':
                return 'Prehral si!';
                break;
            case 'in progress':
            default:
                return '';
                break;
        }
    }

}