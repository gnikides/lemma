<?php namespace Lemma;

use Lemma\ModernGreek\Formatter;

class Lemmatizer
{
    protected $stemmer;
    protected $dictionary;
    protected $unaccented_dictionary;    
    protected $stopwords;
    protected $ignore_words;
    protected $first_names;
    protected $proper_names;

    protected $texts; 

    protected $lemmas;
    protected $stems;
    protected $undefined;

    protected $raw_tokens;
    protected $stopped_tokens;
    protected $unidentified_tokens;

    protected $use_stopwords = true;
    protected $messages;

    const FOUND_STOPWORD        = 'found_stopword';
    const FOUND_IN_DICTIONARY   = 'found_in_dictionary';
    const FOUND_AFTER_STEMMING  = 'found_after_stemming';
    const FOUND_NAME            = 'found_name';   
    const NOT_FOUND             = 'not_found';
            
    const ACTIVE_MESSAGES = [
        // self::FOUND_STOPWORD,
        // self::FOUND_IN_DICTIONARY,
        //  self::FOUND_NAME,
        //self::FOUND_AFTER_STEMMING,
        self::NOT_FOUND
    ];
    

    public function __construct($stemmer)
    {
        $this->stemmer = $stemmer;
    }

    public function processTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            $this->processToken($token);
        }    
    }
    
    public function processToken(string $token)
    {   
        $this->raw_tokens[] = $token;
                
        $token = Formatter::normalize($token);

        if ($this->isIgnoreWord($token)) {
            return true;
       
        } elseif (is_numeric($token) || $this->isStopword($token)) {
            return true;
        
        } else {     
           
            $token = mb_strtolower(trim($token));
            if ($this->isIgnoreWord($token)) {
                return true;
            
            } elseif (is_numeric($token) || $this->isStopword($token)) {
                return true;
            
            } else {    
                
                //  @todo - remove or adapt this test after testing a lot of texts
                $token = trim($token, ",;:.?!%");                
                if (is_numeric($token)) {
                    dump($token);
                    return true;
                }
                $cleaned = trim($token, ",;:.?!*#'&(){}[]1234567890«»><");
                
                // warn if something drastic has been done to work
                if (mb_strtolower($token) !== $cleaned) {
                    // $this->messages[] = [
                    //     'type' => 'error',
                    //     'token' => $token,
                    //     'cleaned' => $cleaned,
                    //     'label' => 'Cleaned don\'t match'
                    // ];
                }

                if ($this->isIgnoreWord($token)) {
                    return true;
                
                } elseif (is_numeric($token) || $this->isStopword($token)) {
                    return true;

                } elseif (mb_strlen($token) < 2) {
                    $this->discarded[] = $token;
                    return true;

                } else {    
                    return $this->resolveLemma($token);
                }
            }
        }
    }  

    public function resolveLemma($token)
    {
        if ($this->isTokenInDictionary(
            $this->dictionary,
            $token,
            '',
            'Found raw token in accented dictionary'
        )) {
            return true;
        
        } else {
            
            $unaccented_token = Formatter::unaccent($token);

            if (is_numeric($unaccented_token) || $this->isStopword($unaccented_token)) {
                return true;
            
            } elseif ($this->isTokenInDictionary(
                $this->unaccented_dictionary,
                $unaccented_token,
                '',
                'Found unaccented token in unaccented dictionary'
            )) {
                return true;
            
            } else {
                return $this->resolveStem($token, $unaccented_token);
            }
        }   
    }

    public function resolveStem($token, $unaccented_token)
    {                
        if ($this->applyLightStemmer($token, $unaccented_token, 13)) {
            return true;
        }
        if ($this->isProperName($token, true)) {
            return true;
        } elseif ($this->isProperName($token, false)) {
            return true;
        }
    
        if ($stem = $this->stemmer->handle($unaccented_token)) {
            if ($this->isSubstringInDictionary(
                $this->unaccented_dictionary,
                $stem,
                $token,
                $unaccented_token,
                'Found stem in dictionary',                
                $this->stemmer->getPartOfSpeech(),
                $this->stemmer->getStep()
            )) {
                return true;
            }
        }

        if ($this->isProperName($token, false, 1)) {
            return true; 

        } elseif ($this->isPlaceName($token, true)) {
            return true;
        } elseif ($this->isPlaceName($token, false)) {
            return true;
        }

        if ($this->showMessage(self::NOT_FOUND)) {
            // dump('undefined='.$token);
            $this->undefined[] = $token;
            $this->messages[] = [
                'type'      => 'line',
                'token'     => $token,
                'encoding'  => Formatter::encoding($token),
                'utf8'      => Formatter::utf8($token),
                'unaccented_token' => isset($unaccented_token) ? $unaccented_token : null,
                'stem'      => isset($stem) ? $stem : '',
                'step'      => isset($stemmer) ? $this->stemmer->getStep() : null,
                'part_of_speech' => isset($stemmer) ? $this->stemmer->getPartOfSpeech() : null,
                'label'     => 'No lemma found'
            ];
        }   
    }

    public function applylightStemmer($token, $unaccented_token, $min_length = 13)
    {
        if (mb_strlen($token) > $min_length) {
            
            $stemming = mb_strlen($token) > 15 ? -2 : -1;
            $stem = mb_substr($token, 0, $stemming);
            $unaccented_stem = mb_substr($unaccented_token, 0, $stemming);

            if ($this->isSubstringInDictionary(
                $this->dictionary,
                $stem,
                $token,
                '',
                'Found accented trimmed stem in dictionary'
            )) {
                return true;
            
            } elseif ($this->isSubstringInDictionary(
                $this->unaccented_dictionary,
                $unaccented_stem,
                $token,
                $unaccented_token,
                'Found unaccented trimmed stem in dictionary'
            )) {
                return true;
            }
        }

        return false;
    }

    public function isTokenInDictionary(
        $dictionary,
        $token,
        $part_of_speech = '',
        $label = '')
    {   
        foreach ($dictionary as $lemma => $item) { 

            if (in_array($token, $item['inflections'])) { 
                $pos = array_key_exists('part_of_speech', $item) ? $item['part_of_speech'] : null;
                if (!$part_of_speech || !$pos || ($part_of_speech == $pos)) {
                    
                    if ($this->showMessage(self::FOUND_IN_DICTIONARY)) {
                        $this->messages[] = [
                            'type' => 'info',
                            'token' => $token,
                            'lemma' => $lemma,
                            'label' => $label,
                            'part_of_speech' => $part_of_speech,
                            'encoding'  => Formatter::encoding($token),
                        ];
                    }    
                    $this->lemmas = $lemma;              
                    return $lemma;
                }   
            }   
        }
        return false;
    }

    public function isSubstringInDictionary(
        $dictionary,
        $stem,
        $token,
        $unaccented_token,        
        $label = '',        
        $part_of_speech = '',
        $step = ''
    )
    {   
        $strlen = mb_strlen($stem);

        foreach ($dictionary as $lemma => $item) { 

            $inflections = [];
            $inflections[] = mb_substr($lemma, 0, $strlen);
            foreach ($item['inflections'] as $inflection) {
                $inflections[] = mb_substr($inflection, 0, $strlen);
            }
            $pos = array_key_exists('part_of_speech', $item) ? $item['part_of_speech'] : null;

            if (in_array($stem, $inflections)) {                 
                if (!$part_of_speech || !$pos || ($part_of_speech == $pos)) {
                    
                    if ($this->showMessage(self::FOUND_AFTER_STEMMING)) {
                        $this->messages[] = [
                            'type' => 'info',
                            'token' => $token,
                            'unaccented_token' => $unaccented_token,
                            'stem' => $stem,
                            'lemma' => $lemma,
                            'step' => $step,
                            'part_of_speech' => $part_of_speech,
                            'label' => $label,
                            'encoding'  => Formatter::encoding($token),
                        ];
                    }    
                    $this->lemmas[] = $lemma;
                    $this->stems[] = $lemma;                       
                    return $lemma;
                }   
            }   
        }
        return false;
    }

    public function isStopword($token)
    {
        if ($this->use_stopwords) {
            foreach ($this->stopwords as $stopword) {
                if ($stopword[0] == $token) {

                    if ($this->showMessage(self::FOUND_STOPWORD)) {
                        $this->messages[] = [
                        'type' => 'line',
                        'token' => $token,
                        'label' => 'stopword',
                        'encoding'  => Formatter::encoding($token),
                    ];
                    }

                    $this->stopped_tokens[] = $token;       
                    return true;
                }
            }
        }
        return false;
    }

        public function isIgnoreWord($token)
    {
        foreach ($this->ignore_words as $ignore_word) {
            if ($ignore_word[0] == $token) {

                // if ($this->showMessage(self::FOUND_STOPWORD)) {
                //     $this->messages[] = [
                //     'type' => 'line',
                //     'token' => $token,
                //     'label' => 'stopword',
                //     'encoding'  => Formatter::encoding($token),
                // ];
                // }

                // $this->stopped_tokens[] = $token;       
                return true;
            }
        }
        return false;
    }

    public function isProperName($token, $has_accents = true, $stemming = 0)
    {
        $formatted_token = Formatter::format($token, $has_accents);
        if ($this->first_names) {
            foreach ($this->first_names as $name) {                
                $formatted_name = Formatter::format($name[0], $has_accents);
                if ($stemming) {
                    $formatted_name = mb_substr($formatted_name, 0, -$stemming);
                }
                if ($formatted_name == $formatted_token) {

                    if ($this->showMessage(self::FOUND_NAME)) {
                        $this->messages[] = [
                            'type' => 'info',
                            'token' => $token,
                            'lemma' => $formatted_name,
                            'unaccented_token' => $formatted_token,
                            'label' => 'Found first name',
                            'encoding'  => Formatter::encoding($token),
                        ];
                    }

                    $this->names[] = $token;     
                    return true;
                }
            }
        }
        return false;
    }

    public function isPlaceName($token, $has_accents = true)
    {
        if ($this->place_names) {
            foreach ($this->place_names as $place) {
                
                $unaccented_token = Formatter::format($token, $has_accents);
                $stem = mb_substr($unaccented_token, 0, -1);
                $strlen = mb_strlen($stem);
                
                $name = Formatter::format($place[0], $has_accents);
                $name = mb_substr($name, 0, $strlen);

                if ($stem == $name) {

                    if ($this->showMessage(self::FOUND_NAME)) {
                        $this->messages[] = [
                            'type' => 'info',
                            'token' => $token,
                            'lemma' => $place[0],
                            'unaccented_token' => $unaccented_token,
                            'stem' => $stem,
                            'label' => 'Found place name',
                            'encoding'  => Formatter::encoding($token),
                        ];
                    }

                    $this->places[] = $token;   
                    return true;
                }
            }
        }
        return false;
    }

    public function resetCounts()
    {
        $this->lemmas = [];
        $this->stems = [];
        $this->undefined = [];
        $this->raw_tokens = [];                
        $this->stopped_tokens = []; 
        $this->unidentified_tokens = [];       
    } 

    public function setDictionary(array $dictionary)
    {
        $this->dictionary = $dictionary;
    }  

    public function setUnaccentedDictionary(array $dictionary)
    {
        $this->unaccented_dictionary = $dictionary;
    }  

    public function setStopwords(array $stopwords)
    {
        $this->stopwords = $stopwords;
    }  

    public function setIgnoreWords(array $words)
    {
        $this->ignore_words = $words;
    } 

    public function setFirstNames(array $names)
    {
        $this->first_names = $names;
    } 

    public function setPlaceNames(array $names)
    {
        $this->place_names = $names;
    } 

    public function getMessages()
    {
        return $this->messages;
    }  

    public function showMessage($group)
    {
        return in_array($group, self::ACTIVE_MESSAGES);
    }  

    public function getLemmas()
    {
        return $this->lemmas;
    }

    public function getStems()
    {
        return $this->stems;
    }

    public function getUndefined()
    {
        return $this->undefined;
    }

    public function getTexts()
    {
        return $this->texts;
    }     

    public function getRawTokens()
    {
        return $this->raw_tokens;
    }   
    
    public function getUnidentifiedTokens()
    {
        return $this->unidentified_tokens;
    } 

    public function getStoppedTokens()
    {
        return $this->stopped_tokens;
    }
}