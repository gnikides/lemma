<?php namespace Lemma;

class BaseStemmer
{
    protected $word;
    protected $lemma;
    protected $stem;
    protected $step;
    protected $part_of_speech;

    public function handle($word)    
    {
        $this->word = $this->filter($word); 
        $this->lemma = $this->runSteps();
        return $this->lemma;
    }

    public function runSteps()
    {
        return;
    }

    public function matchLemma($stem, $lemmas)
    {
        foreach ($lemmas as $key => $lemma) {
            if (str_ends_with($stem, $this->filter($key))) {
                return $this->filter($lemma);
            }
        }
        return false;
    }

    public function matchException(string $stem, array $strings, string $suffix = '')
    {
        foreach ($strings as $string) {
            if (str_ends_with($stem, $this->filter($string))) {
                return $this->append($stem, $suffix);
            }
        }
        return false;
    }

    public function stem(string $string, array $array)
    {
        foreach ($array as $token) {
            if (str_ends_with($string, $this->filter($token))) {
                return mb_substr($this->word, 0, -(mb_strlen($token)));
            } 
        }
        return false;
    }

    public function stringsEndsWith(string $string, array $array)
    {
        foreach ($array as $token) {
            if (str_ends_with($string, $this->filter($token))) {
                return true;
            } 
        }
        return false;
    }

    public function endsWithVowel(string $string)
    {
        $vowels = ['α','ε','η','ι','ο','υ','ω'];
        foreach ($vowels as $token) {
            if (str_ends_with($string, $this->filter($token))) {
                return true;
            }
        }
        return false;
    }

    public function filter($item)
    {
        if (is_array($item)) {
            $items = [];
            foreach ($item as $i) {
                $items[] = $this->filter($i);
            }
            return $items;
        }
        return trim(mb_strtolower($item));
    }

    public function sortByLength($a, $b)
    {
        return mb_strlen($b) - mb_strlen($a);
    }

    public function logStep($method)
    {
        if ($this->lemma && !$this->step) {
            $this->step = $method;
        }
    }

    public function append($stem, $suffix)
    {
        return $this->filter($stem.$suffix);
    }

    public function getStep()
    {
        return $this->step;
    }

    public function getStem()
    {
        return $this->stem;
    }

    public function getPartOfSpeech()
    {
        return $this->part_of_speech;
    }    
}