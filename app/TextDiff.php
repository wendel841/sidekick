<?php

namespace App;

use Illuminate\Contracts\Support\Renderable;

class TextDiff implements Renderable
{
    /**
     * Текст до изменения
     *
     * @var string
     */
    protected $textFrom;

    /**
     * Текст после изменения
     *
     * @var string
     */
    protected $textTo;

    public function __construct($textFrom, $textTo)
    {
        $this->textFrom = trim($textFrom);
        $this->textTo = trim($textTo);
    }

    public function getTextFrom()
    {
        return $this->textFrom;
    }

    public function getTextTo()
    {
        return $this->textTo;
    }

    /**
     * Отображение результата
     *
     * @return mixed|string
     */
    public function render()
    {
        $diff = $this->doDiff(
            $this->splitText($this->getTextFrom()),
            $this->splitText($this->getTextTo())
        );

        $content = $this->renderHTML($diff);

        return $content;
    }

    /**
     * Преобразует результат работы алгоримта в HTML
     *
     * @param array $diff
     * @return string
     */
    protected function renderHTML(array $diff = [])
    {
        $content = [];

        foreach ($diff as $item) {
            if (is_array($item)) {
                $deleted = array_get($item, 'd', []);
                $inserted = array_get($item, 'i', []);

                $countDeleted = count($deleted);
                $countInsrted = count($inserted);

                if ($countDeleted && $countInsrted) {
                    $content[] = '<span class="sentence changed" data-original-text="' . $this->implode($deleted) . '">' . $this->implode($inserted) . '</span>';
                } elseif ($countDeleted) {
                    $content[] = '<span class="sentence deleted">' . $this->implode($deleted) . '</span>';
                } elseif ($countInsrted) {
                    $content[] = '<span class="sentence new">' . $this->implode($inserted) . '</span>';
                }
            } else {
                $content[] = '<span class="sentence">' . $item . '</span>';
            }
        }

        return implode(' ', $content);
    }

    /**
     * https://github.com/paulgb/simplediff
     *
     * @param $textFrom
     * @param $textTo
     * @return array
     */
    protected function doDiff($textFrom, $textTo)
    {
        $matrix = array();
        $maxlen = 0;
        foreach ($textFrom as $oindex => $ovalue) {
            $nkeys = array_keys($textTo, $ovalue);

            foreach ($nkeys as $nindex) {
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                    $matrix[$oindex - 1][$nindex - 1] + 1 : 1;

                if ($matrix[$oindex][$nindex] > $maxlen) {
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }
        }

        if ($maxlen == 0) {
            return array(array('d' => $textFrom, 'i' => $textTo));
        }

        $result = array_merge(
            $this->doDiff(array_slice($textFrom, 0, $omax), array_slice($textTo, 0, $nmax)),
            array_slice($textTo, $nmax, $maxlen),
            $this->doDiff(array_slice($textFrom, $omax + $maxlen), array_slice($textTo, $nmax + $maxlen))
        );

        return $result;
    }

    protected function splitText($text, $delimiter = "/(?<=[.?!])\s+(?=[a-z])/i")
    {
        $result = preg_split($delimiter, $text);

        return $result;
    }

    protected function implode(array $content, $delimiter = ' ')
    {
        return implode($delimiter, $content);
    }

    public static function factory($textFrom, $textTo)
    {
        return new static($textFrom, $textTo);
    }
}