<?php
//declare(strict_types=1);

/**
 * Class Keyboard
 * Klávenica
 */
class Keyboard
{
    /**
     * @var int
     */
    private $cols;
    private $hangman;
    const KEYS_NUMBER = 26;

    /**
     * Keyboard konštruktor
     * @param int $cols Počet stĺpcov, v ktorých sa klávesnica zobrazí
     * @param Hangman $hangman Odkaz na inštanciu triedu Hangman
     */
    public function __construct(int $cols, Hangman $hangman)
    {
        $this->cols = $cols;
        $this->hangman = $hangman;
    }

    /**
     * Vráti klávesnicu maticového tvaru
     * @return string - HTML tabuľka s klávesnicou
     */
    public function getKeyboardLayout(): string
    {
        $rows = ceil(self::KEYS_NUMBER / $this->cols);
        $counter = 0;
        $result = '<table class="keyboard">' . PHP_EOL;
        for ($i = 1; $i <= $rows; $i++) {
            $result .= '<tr>' . PHP_EOL;
            for ($j = 1; $j <= $this->cols; $j++) {
                $char = chr(65 + $counter++);
                if ($counter > self::KEYS_NUMBER || in_array($char, $this->hangman->getUsedChars())) {
                    $result .= '<td>&nbsp;</td>';
                } else {
                    $result .= '<td><a href="?char=' . $char . '">' . $char . '</a></td>';
                }
            }
            $result .= PHP_EOL . '</tr>' . PHP_EOL;
        }
        $result .= '</table>' . PHP_EOL;
        return $result;
    }
}