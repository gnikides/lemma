<?php namespace Lemma;

use League\Csv\Reader as CSVReader;
use Lemma\ModernGreek\Formatter;

class Data
{
    private $base_path = 'vendor/gnikides/lemma/src/data/el';
    private $dictionary = [];

    public function accentedDictionary()
    {   
        if (!$this->dictionary) {
            $this->dictionary();
        }
        return $this->formatDictionary(true);       
    }
    
    public function unaccentedDictionary()
    {
        if (!$this->dictionary) {
            $this->dictionary();
        }        
        return $this->formatDictionary(false);         
    }

    public function stopwords()
    {
        return $this->readCSV('stopwords.csv');
    } 
    
    public function ignoreWords()
    {
        return $this->readCSV('ignore_words.csv');
    } 

    public function firstNames()
    {
        return $this->readCSV('first_names.csv');    
    }

    public function greekPlaceNames()
    {
        return $this->readCSV('greek_place_names.csv');       
    }

    public function dictionary()
    {
        $csv = CSVReader::createFromPath($this->base_path .'/dictionary.csv', 'r');
        $csv->setDelimiter(';');
        //$csv->setHeaderOffset(0);
        //$header = $csv->getHeader();
        $words = $csv->getRecords();

        foreach ($words as $word) {
            $results[] = [ 
                'lemma' => $word[0],
                'inflections' => $word[1] ? explode(',',$word[1]) : [],
                'part_of_speech' => $word[2]
            ];
        }
        $this->dictionary = $results;
    } 

    public function formatDictionary(bool $has_accents = true)
    {   
        $items = [];

        foreach ($this->dictionary as $item) {

            $lemma = Formatter::normalize(mb_strtolower(trim($item['lemma'])));
            $lemma = Formatter::unaccent($lemma, $has_accents);

            $inflections = [];
            $inflections[] = $lemma;

            foreach ($item['inflections'] as $inflection) {
                $inflections[] = Formatter::unaccent(
                    Formatter::normalize(mb_strtolower(trim($inflection))),
                $has_accents);
            }                     
            
            $items[$item['lemma']]['inflections'] = $inflections;
            $items[$item['lemma']]['part_of_speech'] = $item['part_of_speech'];
        }

        return $items;
    }
         
    public function readCSV(string $file_name)
    {
        $csv = CSVReader::createFromPath($this->base_path.'/'.$file_name, 'r');
        $csv->setDelimiter(PHP_EOL);
        $records = $csv->getRecords();
        foreach ($records as $word) {
            $words[] = $word;
        }
        return $words;
    }
}