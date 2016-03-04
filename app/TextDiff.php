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
        $content = $this->splitIntoSentences($content);

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
        $content = '';

        foreach ($diff as $k) {
            if (is_array($k)) {
                if ($newText = array_get($k, 'i')) {
                    $originalText = (empty($k['d'])) ? '[empty]' : implode(' ', $k['d']);
                    $newText = implode(' ', $newText);
                    $content .= '<span class="text new" data-original-text="' . $originalText . '">' . $newText . '</span> ';
                }
            } else {
                $content .= '<span class="text old">' . $k . '</span> ';
            }
        }

        return $content;
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

    /**
     * Разбивает текст на предложения.
     *
     * @param $content
     * @return mixed|string
     */
    protected function splitIntoSentences($content)
    {
        $content = str_replace('.</span>', '</span>.', $content);
        $array = explode('. ', $content);

        $array = array_map(function ($item) {
            return sprintf('<span class="sentence">%s</span>', $item);
        }, $array);

        $content = implode('<span class="text old">.</span> ', $array);

        return $content;
    }

    /**
     * Разбивает текст на слова
     *
     * @param $text
     * @param string $delimiter
     * @return array
     */
    protected function splitText($text, $delimiter = '\s')
    {
        $result = preg_split("/[{$delimiter}]+/", $text);

        return $result;
    }

    public static function factory($textFrom, $textTo)
    {
        return new static($textFrom, $textTo);
    }
}