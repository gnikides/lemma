<?php namespace Lemma;

class TextScrubber
{
    private $text = '';
    private $tokens = [];
    private $original_text;

    public function __construct($text)
    {
        $this->text = $text;
        $this->original_text = $text;
    }

    public function findTokens()
    {
        $this->scrubText();
        $this->processTokens();        
        return $this->tokens;
    }

    public function scrubText()
    {   
        $this->text = str_replace(
            [
                1,2,3,4,5,6,7,8,9,0,';',':','΄',"'",
                '</i>','<i>','.','“','–','’','·','~',
                '--','-','_','(',')','{','}','²','΄',
                "'",'1','2','3','4','5','6','7','8','9',
                '0','#',',','>','<','»','','[',']','%',
                '*','^','&','  ','«','/','-','....'               
            ],
            ' ',
            $this->text
        );
        $this->text = trim($this->text);
        $this->text = str_replace('  ', ' ', $this->text);
        $this->text = str_replace(
            [ 
                'a','b','c','d','e','f','g','h','i',
                'j','k','l','m','n','o','p','q','r',
                's','t','u','v','w','x','y','z',
                'а','б','в','г','д','е','ё','ж','з','и',
                'й','к','л','м','н','о','п','с','т','у',
                'ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я'
            ],
            '',
            $this->text
        );                 
        $this->text = str_ireplace(
            ['ΜΙΑ ΠΡΩΤΟΤΥΠΗ ΣΕΙΡΑ ΤΟΥ NETFLIX'], ' ', $this->text
        );
        $this->text = trim($this->text);
    }

    public function processTokens()
    {        
        $tokens = preg_split('/\s+/', $this->text);

        foreach ($tokens as $key => $token) {
            $token = $this->scrubToken($token);

            $more_tokens = preg_split('/\s+/', $token);

            foreach ($more_tokens as $key => $token) {
                if (false !== mb_strpos($token, '-')) {
                    unset($tokens[$key]);
                    $pieces = explode('-', $token);                    
                    foreach ($pieces as $piece) {
                        $tokens[] = $piece;
                    }
                }
            }
        }
        foreach ($tokens as $token) {
            if ('' != $token 
                && !empty($token) 
                && mb_strlen($token) > 1) {
                $this->tokens[] = $this->scrubToken($token);
            }   
        }            
    }

    public function scrubToken(string $string)
    {       
        $token = str_replace(
            [
                ',',';'.':','.','?','!','%','·',
                '…','..','...','....','.....',"'''",
                "''","/",'<i>','</i>','<b>','</b>','<',
                '>','"','=',']','[','/','«','-','....'                                                  
            ],
            ' ',
            $string
        );
        $token = str_ireplace(
            [
                'a','b','c','d','e','f','g','h','i','j','k','l','m','n',
                'o','p','q','r','s','t','u','v','w','x','y','z'                                
            ],
            ' ',
            $token
        );

        return $token;
    }  
    
    public function getText()
    {
        return $this->text;
    }

    public function getOriginalText()
    {
        return $this->text;
    }    
}