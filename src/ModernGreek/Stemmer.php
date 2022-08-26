<?php namespace Lemma\ModernGreek;

use Lemma\BaseStemmer;

/* Based on
https://snowballstem.org/algorithms/greek/stemmer.html

*/

class Stemmer extends BaseStemmer
{
    public function steps1()
    {
        if (!$this->lemma) {
            $suffixes = [ 
                'ιζα', 'ιζεσ', 'ιζε', 'ιζαμε', 'ιζατε', 'ιζαν', 'ιζανε', 
                'ιζω', 'ιζεισ', 'ιζει', 'ιζουμε', 'ιζετε', 'ιζουν', 'ιζουνε'
            ];
            $exceptions1 = [
                'αναμπα', 'εμπα', 'επα', 'ξαναπα', 'πα', 'περιπα', 'αθρο',
                'συναθρο', 'δανε'
            ];
            $exceptions2 = ['μαρκ', 'κορν', 'αμπαρ', 'αρρ', 'βαθυρι', 'βαρκ',
                'β', 'βολβορ', 'γκρ', 'γλυκορ', 'γλυκυρ', 'ιμπ', 'λ', 'λου',
                'μαρ', 'μ', 'πρ', 'μπρ', 'πολυρ', 'π', 'ρ', 'πιπερορ'
            ];
            if ($this->stem = $this->stem($this->word, $suffixes)) { 
                if (!($this->lemma = $this->matchException($this->stem, $exceptions1, 'ι'))) {
                    if (!($this->lemma = $this->matchException($this->stem, $exceptions2, 'ιζ'))) {
                        $this->lemma = $this->filter($this->stem);
                    }
                }
                $this->part_of_speech = 'verb';    
            }
        }
        $this->logStep(__METHOD__);
        return $this;
    }

    public function steps2()
    {
        if (!$this->lemma) {
            $suffixes = [ 'ωθηκα', 'ωθηκεσ', 'ωθηκε', 'ωθηκαμε', 'ωθηκατε', 'ωθηκαν', 'ωθηκανε'];
            $exceptions = [ 'αλ', 'βι', 'εν', 'υψ', 'λι', 'ζω', 'σ', 'χ'];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (!($this->lemma = $this->matchException($this->stem, $exceptions, 'ων'))) {
                    $this->lemma = $this->filter($this->stem);
                }
                $this->part_of_speech = 'verb'; 
            }   
        }
        $this->logStep(__METHOD__);
        return $this;
    }

    public function steps3()
    {
        if (!$this->lemma) {
            $suffixes = [ 'ισα', 'ισεσ', 'ισε', 'ισαμε', 'ισατε', 'ισαν','ισανε'];
            $exceptions1 = [ 'αναμπα', 'αθρο', 'εμπα', 'εσε', 'εσωκλε', 'επα', 'ξαναπα',
                'επε', 'περιπα','συναθρο', 'δανε', 'κλε', 'χαρτοπα', 'εξαρχα', 'μετεπε',
                'αποκλε', 'απεκλε', 'εκλε', 'πε' 
            ];
            $exceptions2 = [ 'αν', 'αφ', 'γε', 'γιγαντοαφ', 'γκε', 'δημοκρατ', 'κομ',
                'γκ', 'μ', 'π', 'πουκαμ', 'ολο', 'λαρ'
            ];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (in_array($this->stem, $this->filter(['ισα']))) { // ??? not sure
                    $this->lemma = $this->append($this->stem, 'ισ');
                } elseif (!($this->lemma = $this->matchException($this->stem, $exceptions1, 'ι'))) {
                    if (!($this->lemma = $this->matchException($this->stem, $exceptions2, 'ισ'))) {
                        $this->lemma = $this->filter($this->stem);
                    }    
                }
            }        
        }
        $this->logStep(__METHOD__);
        return $this;
    }

    public function steps4()
    {
        if (!$this->lemma) {
            $suffixes = [ 'ισω', 'ισεισ', 'ισει', 'ισουμε', 'ισετε', 'ισουν', 'ισουνε'];
            $exceptions = [ 
                'αναμπα', 'εμπα', 'εσε', 'εσωκλε', 'επα', 'ξαναπα', 'επε',
                'περιπα', 'αθρο', 'συναθρο', 'δανε', 'κλε', 'χαρτοπα', 'εξαρχα',
                'μετεπε', 'αποκλε', 'απεκλε', 'εκλε', 'πε' 
            ];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (!($this->lemma = $this->matchException($this->stem, $exceptions, 'ι'))) {
                    $this->lemma = $this->filter($this->stem);
                }                
                $this->part_of_speech = 'verb'; 
            }        
        }
        $this->logStep(__METHOD__);
        return $this;
    }
        
    public function steps5()
    {
        if (!$this->lemma) {
            $suffixes = [
                'ιστοσ','ιστου','ιστο','ιστε','ιστοι','ιστων','ιστουσ','ιστη',
                'ιστησ','ιστα', 'ιστεσ'
            ];
            $exceptions1 = [ 
                'δανε', 'συναθρο', 'κλε', 'σε', 'εσωκλε', 'ασε', 'πλε', 
            ];
            $exceptions2 = [ 
                'μ', 'π', 'απ', 'αρ', 'ηδ', 'κτ', 'σκ', 'σχ', 'υψ', 'φα',
                'χρ', 'χτ', 'ακτ', 'αορ', 'ασχ', 'ατα', 'αχν', 'αχτ', 'γεμ',
                'γυρ', 'εμπ', 'ευπ', 'εχθ', 'ηφα', 'καθ', 'κακ', 'κυλ', 'λυγ',
                'μακ', 'μεγ', 'ταχ', 'φιλ', 'χωρ', 
            ];            
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (!($this->lemma = $this->matchException($this->stem, $exceptions1, 'ι'))) {
                    if (!($this->lemma = $this->matchException($this->stem, $exceptions2, 'ιστ'))) {
                        $this->lemma = $this->filter($this->stem);
                    }    
                }
            }             
        }
        $this->logStep(__METHOD__);
        return $this;
    }

    public function steps6()
    {
        if (!$this->lemma) {
            $suffixes = [
                'ισμο', 'ισμοι', 'ισμοσ', 'ισμου', 'ισμουσ', 'ισμων'
            ];
            $exceptions1 = [
                'σε', 'μετασε', 'μικροσε', 'εγκλε', 'αποκλε',
            ];
            $exceptions2 = [
                'δανε', 'αντιδανε'
            ];                        
            if ($this->stem = $this->stem($this->word, $suffixes)) { 
                if (!($this->lemma = $this->matchException($this->stem, $exceptions1, 'ισμ'))) {
                    if (!($this->lemma = $this->matchException($this->stem, $exceptions2, 'ι'))) {
                        $this->lemma = $this->filter($this->stem);
                    }    
                }
            }        
        }    
        $this->logStep(__METHOD__);
        return $this;
    }

    public function steps7()
    {
        if (!$this->lemma) {
            $suffixes = [
                'αρακι', 'αρακια', 'ουδακι', 'ουδακια',
            ];
            $exceptions = [
                'σ', 'χ',
            ];                      
            if ($this->stem = $this->stem($this->word, $suffixes)) { 
                if (!($this->lemma = $this->matchException($this->stem, $exceptions, 'αρακ'))) {
                    $this->lemma = $this->filter($this->stem);    
                }
            }        
        }    
        $this->logStep(__METHOD__);
        return $this;
    }

    public function steps8()
    {
        if (!$this->lemma) {
            $suffixes = [
                'ακι', 'ακια', 'ιτσα', 'ιτσασ', 'ιτσεσ', 'ιτσων', 'αρακι', 'αρακια'                
            ];
            $exceptions1 = [
                'βαμβ', 'βρ', 'καιμ', 'κον', 'κορ', 'λαβρ', 'λουλ', 'μερ', 'μουστ',
                'ναγκασ', 'πλ', 'ρ', 'ρυ', 'σ', 'σκ', 'σοκ', 'σπαν', 'τζ', 'φαρμ',
                'χ', 'καπακ', 'αλισφ', 'αμβρ', 'ανθρ', 'κ', 'φυλ', 'κατραπ', 'κλιμ',
                'μαλ', 'σλοβ', 'φ', 'σφ', 'τσεχοσλοβ'
            ];          
            $exceptions2 = [
                'νυφ', 'πατερ', 'π', 'τοσ', 'τριπολ',
            ];          
            $exceptions3 = [ 'κορ'];

            if ($this->stem = $this->stem($this->word, $suffixes)) { 
                if (!($this->lemma = $this->matchException($this->stem, $exceptions1, 'ακ'))) {
                    if (!($this->lemma = $this->matchException($this->stem, $exceptions2, 'ιτσ'))) {
                        if (!($this->lemma = $this->matchException($this->stem, $exceptions3, 'ιτσ'))) {
                            $this->lemma = $this->filter($this->stem);
                        }    
                    }                         
                }
            }        
        }    
        $this->logStep(__METHOD__);
        return $this;
    }

    public function steps9()
    {
        if (!$this->lemma) {
            $suffixes = [ 'ιδιο', 'ιδια', 'ιδιων'];
            $exceptions1 = [ 'αιφν', 'ιρ', 'ολο', 'ψαλ'];                   
            $exceptions2 = [ 'ε', 'παιχν'];   
            if ($this->stem = $this->stem($this->word, $suffixes)) { 
                if (!($this->lemma = $this->matchException($this->stem, $exceptions1, 'ιδ'))) {
                    if (!($this->lemma = $this->matchException($this->stem, $exceptions2, 'ιδ'))) {
                        $this->lemma = $this->filter($this->stem);
                    }    
                }
            }        
        }    
        $this->logStep(__METHOD__);
        return $this;
    }

    public function steps10()
    {
        if (!$this->lemma) {
            $suffixes = [ 'ισκοσ', 'ισκου', 'ισκο', 'ισκε'];
            $exceptions = [ 'δ', 'ιβ', 'μην', 'ρ', 'φραγκ', 'λυκ','οβελ'];                     
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (!($this->lemma = $this->matchException($this->stem, $exceptions, 'ισκ'))) {
                    $this->lemma = $this->filter($this->stem);
                }    
            }        
        }    
        $this->logStep(__METHOD__);
        return $this;
    }

    public function step2a()
    {   
        if (!$this->lemma) {
            $suffixes = ['άδεσ','άδες','άδων','αδες','αδεσ','αδων'];
            $lemmas = [
                'οκ' => 'οκά',
                'μαμ' => 'μαμά',
                'μαν' => 'μάνα',
                'μπαμπ' => 'μπαμπάς',
                'πατερ' => 'πατέρας',
                'γιαγι' => 'γιαγιά',
                'νταντ' => 'νταντά',
                'κυρ' => 'κυρά',
                'θει' => 'θεια',
                'πεθερ' => 'πεθερά',
                'νονά' => 'νονά',
                'νονα' => 'νονά'
            ];
            if ($this->stem = $this->stem($this->word, $suffixes)) { 
                if (!($this->lemma = $this->matchLemma($this->stem, $lemmas))) {  
                    $this->lemma = $this->append($this->stem, 'αδ');
                }
                $this->part_of_speech = 'noun';
            }
        }
        $this->logStep(__METHOD__);
        return $this;
    }

    public function step2b()
    {   
        if (!$this->lemma) {
            $suffixes = ['έδες','έδεσ','έδων','εδες','εδεσ','εδων'];
            $lemmas = [
                'οπ' => 'οπα',  //  ??? not sure of these
                'ιπ' => 'ιπ',
                'εμπ' => 'έμπα',
                'υπ' => 'υπ',
                'γηπ' => 'γήπεδο',
                'δαπ' => 'δαπεδο',
                'κρασπ' => 'κράσπεδο',
                'κυρ' => 'κυρά',
                'μιλ' => 'μίλι'
            ];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if ($this->lemma = $this->matchLemma($this->stem, $lemmas)) {
                } else {    
                    $this->lemma = $this->append($this->stem, 'εδ');
                }
                $this->part_of_speech = 'noun';
            }
        }    
        $this->logStep(__METHOD__);        
        return $this;
    }

    public function step2c()
    {   
        if (!$this->lemma) {
            $suffixes = ['ουδες','ούδες','ουδεσ','ούδεσ','ουδων','ούδων'];
            $lemmas = [
                'αρκ' => 'αρκούδα',
                'καλιακ' => 'καλιακούδα',
                'πεταλ' => 'πεταλούδα',
                'λιχ' => 'λιχούδα',
                'πλεξ' => 'πλεξούδα',
                'σκ' => 'σκούδο',
                'σ' => 'σούδα',
                'φλ' => 'φλούδα',
                'φρ' => 'φρούδος', // ??? @todo
                'βελ' => 'βελούδο',
                'λουλ' => 'λουλούδα',
                'χν' => 'χνούδι',
                'σπ' => 'σπουδή',
                'τραγ' => 'τραγούδι',
                'φε' => 'φέουδο'
            ];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (!($this->lemma = $this->matchLemma($this->stem, $lemmas))) {
                    $this->lemma = $this->append($this->stem, 'ουδ');
                }
                $this->part_of_speech = 'noun';
            }
        }   
        $this->logStep(__METHOD__);         
        return $this;
    }

    public function step2d()
    {
        if (!$this->lemma) {
            $suffixes = ['εωσ','εως','εων'];
            $exceptions = ['θ','δ','ελ','γαλ','ν','π','ιδ','παρ'];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (!($this->lemma = $this->matchException($this->stem, $exceptions, 'ε'))) {
                    $this->lemma = $this->filter($this->stem);
                }
            }
        }   
        $this->logStep(__METHOD__);         
        return $this;
    }

    public function step3()
    {
        if (!$this->lemma) {
            $suffixes = ['ια','ιου','ιων'];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if ($this->endsWithVowel($this->stem)) {
                    $this->lemma = $this->append($this->stem, 'ι');
                } else {
                    $this->lemma = $this->filter($this->stem);
                }    
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }

    public function step4()
    {   
        if (!$this->lemma) {
            $suffixes = ['ικα','ικο','ικου','ικων'];
            $exceptions = [
                'αλ', 'αδ', 'ενδ', 'αμαν', 'αμμοχαλ', 'ηθ', 'ανηθ', 'αντιδ',
                'φυσ', 'βρωμ', 'γερ', 'εξωδ', 'καλπ', 'καλλιν', 'καταδ',
                'μουλ', 'μπαν', 'μπαγιατ', 'μπολ', 'μποσ', 'νιτ', 'ξικ',
                'συνομηλ', 'πετσ', 'πιτσ', 'πικαντ', 'πλιατσ', 'ποστελν',
                'πρωτοδ', 'σερτ', 'συναδ', 'τσαμ', 'υποδ', 'φιλον', 'φυλοδ',
                'χασ'
            ];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if ($this->endsWithVowel($this->stem)) {
                    $this->lemma = $this->append($this->stem, 'ικ');
                } else {
                    if (!($this->lemma = $this->matchException($this->stem, $exceptions, 'ικ'))) {
                        $this->lemma = $this->filter($this->stem);
                    }    
                }
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }

    public function step5a()
    {  
        if (!$this->lemma) {
            $suffixes = ['αγαμε','ησαμε','ουσαμε','ηκαμε','ηθηκαμε'];
            $exceptions = [
                'αναπ', 'αποθ', 'αποκ', 'αποστ', 'βουβ', 'ξεθ', 'ουλ',
                'πεθ', 'πικρ', 'ποτ', 'σιχ', 'χ',
            ];
            if ($this->word == $this->filter('αγαμε')) {
                $this->lemma = $this->filter('αγαμ');
            
            } elseif ($this->stem = $this->stem($this->word, $suffixes)) {
                $this->lemma = $this->filter($this->stem);
                $this->part_of_speech = 'verb';
            
            } elseif ($this->stem = $this->stem($this->word, ['αμε'])) {
                if (in_array($this->stem, $this->filter($exceptions))) {
                    $this->lemma = $this->append($this->stem, 'αμ');
                } else {
                    $this->lemma = $this->filter($this->stem);
                }
                $this->part_of_speech = 'verb';    
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }

    public function step5b()
    {
        if (!$this->lemma) {
            $suffixes = [
                'αγανε', 'ησανε', 'ουσανε', 'ιοντανε', 'ιοτανε', 'ιουντανε',
                'οντανε', 'οτανε', 'ουντανε', 'ηκανε', 'ηθηκανε'
            ];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (in_array($this->stem, $this->filter(['τρ','τσ']))) {
                    $this->lemma = $this->append($this->stem, 'αγαν');
                } else {
                    $this->lemma = $this->filter($this->stem);
                }
                $this->part_of_speech = 'verb';
            }
        } 
        $this->logStep(__METHOD__);           
        return $this;
    }

    public function step5b2()
    {
        if (!$this->lemma) {
            $stems = [
                'βετερ', 'βουλκ', 'βραχμ', 'γ', 'δραδουμ', 'καλπουζ',
                'καστελ', 'κορμορ', 'λαοπλ', 'μωαμεθ', 'μ', 'μουσουλμ',
                'ν', 'ουλ', 'π', 'πελεκ', 'πλ', 'πολισ', 'πορτολ',
                'σαρακατσ', 'σουλτ', 'τσαρλατ', 'ορφ', 'τσιγγ', 'τσοπ',
                'φωτοστεφ', 'χ', 'ψυχοπλ', 'αγ', 'γαλ', 'γερ', 'δεκ',
                'διπλ', 'αμερικαν', 'ουρ', 'πιθ', 'πουριτ', 'σ', 'ζωντ',
                'ικ', 'καστ', 'κοπ', 'λιχ', 'λουθηρ', 'μαιντ', 'μελ',
                'σιγ', 'σπ', 'στεγ', 'τραγ', 'τσαγ', 'φ', 'ερ', 'αδαπ',
                'αθιγγ', 'αμηχ', 'ανικ', 'ανοργ', 'απηγ', 'απιθ', 'ατσιγγ',
                'βασ', 'βασκ', 'βαθυγαλ', 'βιομηχ', 'βραχυκ', 'διατ', 'διαφ',
                'ενοργ', 'θυσ', 'καπνοβιομηχ', 'καταγαλ', 'κλιβ', 'κοιλαρφ',
                'λιβ', 'μεγλοβιομηχ', 'μικροβιομηχ', 'νταβ', 'ξηροκλιβ',
                'ολιγοδαμ', 'ολογαλ', 'πενταρφ', 'περηφ', 'περιτρ', 'πλατ',
                'πολυδαπ', 'πολυμηχ', 'στεφ', 'ταβ', 'τετ', 'υπερηφ',
                'υποκοπ', 'χαμηλοδαπ', 'ψηλοταβ'
            ];
            if ($this->stem = $this->stem($this->word, $this->filter(['ανε']))) {
                if (in_array($this->stem, $this->filter($stems)) || $this->endsWithVowel($this->stem)) {
                    $this->lemma = $this->append($this->stem, 'αν');
                } else {
                    $this->lemma = $this->filter($this->stem);
                }
            }
        }    
        $this->logStep(__METHOD__);      
        return $this;
    }

    public function step5c()
    {
        if (!$this->lemma) {
            $exceptions = [
                'οδ', 'αιρ', 'φορ', 'ταθ', 'διαθ', 'σχ', 'ενδ', 'ευρ', 'τιθ',
                'υπερθ', 'ραθ', 'ενθ', 'ροθ', 'σθ', 'πυρ', 'αιν', 'συνδ',
                'συν', 'συνθ', 'χωρ', 'πον', 'βρ', 'καθ', 'ευθ', 'εκθ',
                'νετ', 'ρον', 'αρκ', 'βαρ', 'βολ', 'ωφελ'
            ];
            $suffixes = [
                'αβαρ', 'βεν', 'εναρ', 'αβρ', 'αδ', 'αθ', 'αν', 'απλ',
                'βαρον', 'ντρ', 'σκ', 'κοπ', 'μπορ', 'νιφ', 'παγ', 'παρακαλ',
                'σερπ', 'σκελ', 'συρφ', 'τοκ', 'υ', 'δ', 'εμ', 'θαρρ',
            ];
            if ($this->stem = $this->stem($this->word, $this->filter(['ησετε']))) {
                $this->lemma = $this->filter($this->stem);
                $this->part_of_speech = 'verb';
            }
            if ($this->stem = $this->stem($this->word, $this->filter(['ετε']))) {
                if (in_array($this->stem, $this->filter($exceptions))
                    || $this->endsWithVowel($this->stem)
                    || $this->stringsEndsWith($this->stem, $suffixes)) {
                    $this->lemma = $this->append($this->stem, 'ετ');
                } else {
                    $this->part_of_speech = 'verb';
                    $this->lemma = $this->filter($this->stem);
                }
            }
        }  
        $this->logStep(__METHOD__);                  
        return $this;
    }

    public function step5d()
    {
        if (!$this->lemma) {
            $suffixes = ['οντασ','ωντασ','οντας','ωντας'];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (in_array($this->stem, $this->filter(['αρχ']))) {
                    $this->lemma = $this->append($this->stem, 'οντ');
                } elseif (in_array($this->stem, $this->filter(['κρε']))) {
                    $this->lemma = $this->append($this->stem, 'ωντ');
                } else {
                    $this->lemma = $this->filter($this->stem);
                }
                $this->part_of_speech = 'verb';  
            }
        }   
        $this->logStep(__METHOD__);         
        return $this;
    }

    public function step5e()
    {
        if (!$this->lemma) {
            $suffixes = ['ομαστε','ιομαστε'];
            if ($this->stem = $this->stem($this->word, $suffixes)) {
                if (in_array($this->stem, $this->filter(['ον']))) {
                    $this->lemma = $this->append($this->stem, 'ομαστ');
                } else {
                    $this->lemma = $this->filter($this->stem);
                } 
                $this->part_of_speech = 'verb';   
            }
        }   
        $this->logStep(__METHOD__);         
        return $this;
    }

    public function step5f()
    {
        if (!$this->lemma) {
            $stems = [
                'π', 'απ', 'συμπ', 'ασυμπ', 'ακαταπ', 'αμεταμφ',
            ];
            if ($this->stem = $this->stem($this->word, $this->filter(['ιεστε']))) {
                if (in_array($this->stem, $this->filter($stems))) {
                    $this->lemma = $this->append($this->stem, 'ιεστ');
                } else {
                    $this->lemma = $this->filter($this->stem);
                }
            }
        } 
        $this->logStep(__METHOD__);                 
        return $this;
    }

    public function step5f2()
    {
        if (!$this->lemma) {
            $stems = [
                'αλ', 'αρ', 'εκτελ', 'ζ', 'μ', 'ξ', 'παρακαλ', 'προ', 'νισ'
            ];
            if ($this->stem = $this->stem($this->word, $this->filter(['εστε']))) {
                if (in_array($this->stem, $this->filter($stems))) {
                    $this->lemma = $this->append($this->stem, 'ιεστ'); // or $this->stem.'εστ'; check!! @todo
                } else {
                    $this->lemma = $this->filter($this->stem);
                }    
            } 
        } 
        $this->logStep(__METHOD__);                 
        return $this;
    }

    public function step5g()
    {
        if (!$this->lemma) {
            if ($this->stem = $this->stem($this->word, $this->filter(['ηθηκα','ηθηκεσ','ηθηκες','ηθηκε']))) {
                $this->lemma = $this->stem;
            }
            if (!$this->lemma) {
                if ($this->stem = $this->stem($this->word, $this->filter(['ηκα','ηκεσ','ηκες','ηκε']))) {
                    $this->stems = [
                        'σκωλ', 'σκουλ', 'ναρθ', 'σφ', 'οθ', 'πιθ',
                    ];
                    $suffixes = [
                        'ηκ', 'διαθ', 'θ', 'παρακαταθ', 'προσθ', 'συνθ'
                    ];
                    if (in_array($this->stem, $this->filter($this->stems)) || $this->stringsEndsWith($this->stem, $suffixes)) {
                        $this->lemma = $this->append($this->stem, 'ηκ');
                    } else {
                        $this->lemma = $this->stem;
                    }    
                }
            }    
        }     
        $this->logStep(__METHOD__);                  
        return $this;
    }

    public function step5h()
    {
        if (!$this->lemma) {
            $suffixes = ['ουσα','ουσεσ','ουσες','ουσε'];
            $stems = [
                'φαρμακ', 'χαδ', 'αγκ', 'αναρρ', 'βρομ', 'εκλιπ', 'λαμπιδ',
                'λεχ', 'μ', 'πατ', 'ρ', 'λ', 'μεδ', 'μεσαζ', 'υποτειν',
                'αμ', 'αιθ', 'ανηκ', 'δεσποζ', 'ενδιαφερ', 'δε',
                'δευτερευ', 'καθαρευ', 'πλε', 'τσα'
            ];
            $exceptions = [
                'ποδαρ', 'βλεπ', 'πανταχ', 'φρυδ', 'μαντιλ', 'μαλλ',
                'κυματ', 'λαχ', 'ληγ', 'φαγ', 'ομ', 'πρωτ'
            ];
            if ($this->stem = $this->stem($this->word, $this->filter($suffixes))) {
                if (in_array($this->stem, $this->filter($stems))
                    || $this->endsWithVowel($this->stem)
                    || $this->stringsEndsWith($this->stem, $exceptions)) {
                    $this->lemma = $this->append($this->stem, 'ουσ'); //@todo 'ουs????
                } else {
                    $this->lemma = $this->filter($this->stem);
                }
                $this->part_of_speech = 'verb'; 
            }
        }
        $this->logStep(__METHOD__);              
        return $this;
    }

    public function step5i()
    {
        if (!$this->lemma) {
            $stems = [
                'αβαστ', 'πολυφ', 'αδηφ', 'παμφ', 'ρ', 'ασπ', 'αφ', 'αμαλ',
                'αμαλλι', 'ανυστ', 'απερ', 'ασπαρ', 'αχαρ', 'δερβεν', 'δροσοπ',
                'ξεφ', 'νεοπ', 'νομοτ', 'ολοπ', 'ομοτ', 'προστ', 'προσωποπ',
                'συμπ', 'συντ', 'τ', 'υποτ', 'χαρ', 'αειπ', 'αιμοστ',
                'ανυπ', 'αποτ', 'αρτιπ', 'διατ', 'εν', 'επιτ', 'κροκαλοπ',
                'σιδηροπ', 'λ', 'ναυ', 'ουλαμ', 'ουρ', 'π', 'τρ', 'μ'
            ];
            $suffixes = [
                'οφ', 'πελ', 'χορτ', 'λλ', 'σφ', 'ρπ', 'φρ', 'πρ', 'λοχ', 'σμην'
            ];
            if ($this->stem = $this->stem($this->word, $this->filter(['αγα','αγεσ','αγες','αγε']))) {
                
                $has = in_array($this->stem, $this->filter($stems))
                    || $this->stringsEndsWith($this->stem, $suffixes);
                
                $hasNot = in_array($this->stem, $this->filter(['ψοφ','ναυλοχ']))
                    || $this->stringsEndsWith($this->stem, $this->filter(['κολλ']));

                if ($has && !$hasNot) {
                    $this->lemma = $this->append($this->stem, 'αγ');
                } else {
                    $this->lemma = $this->stem;
                }    
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }

    public function step5j()
    {
        if (!$this->lemma) {
            $suffixes = ['ησε','ησου','ησα'];
            $stems = [
                'ν', 'χερσον', 'δωδεκαν', 'ερημον', 'μεγαλον', 'επταν'
            ];
            if ($this->stem = $this->stem($this->word, $this->filter($suffixes))) {
                if (in_array($this->stem, $stems)) {
                    $this->lemma = $this->append($this->stem, 'ησ');
                } else {
                    $this->lemma = $this->stem;
                }    
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }

    public function step5k()
    {
        if (!$this->lemma) {
            $stems = [
                'ασβ', 'σβ', 'αχρ', 'χρ', 'απλ', 'αειμν', 'δυσχρ', 'ευχρ', 'κοινοχρ', 'παλιμψ'
            ];
            if ($this->stem = $this->stem($this->word, $this->filter(['ηστε']))) {
                if (in_array($this->stem, $this->filter($stems))) {
                    $this->lemma = $this->append($this->stem, 'ηστ');
                } else {
                    $this->lemma = $this->stem;
                }    
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }


    public function step5l()
    {
        if (!$this->lemma) {
            $suffixes = ['ουνε','ησουνε','ηθουνε'];
            $stems = [
                'ν', 'ρ', 'σπι', 'στραβομουτσ', 'κακομουτσ', 'εξων'
            ];
            if ($this->stem = $this->stem($this->word, $this->filter($suffixes))) {
                if (in_array($this->stem, $this->filter($stems))) {
                    $this->lemma = $this->append($this->stem, 'ουν');
                } else {
                    $this->lemma = $this->stem;
                }
                $this->part_of_speech = 'verb';    
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }

    public function step5m()
    {
        if (!$this->lemma) {
            $suffixes = ['ουμε','ησουμε','ηθουμε'];
            $stems = [
                'παρασουσ', 'φ', 'χ', 'ωριοπλ', 'αζ', 'αλλοσουσ', 'ασουσ', 'ουμ'
            ];
            if ($this->stem = $this->stem($this->word, $this->filter($suffixes))) {
                if (in_array($this->stem, $this->filter($stems))) {
                    $this->lemma = $this->append($this->stem, 'ουμ');
                } else {
                    $this->lemma = $this->stem;
                }
                $this->part_of_speech = 'verb';    
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }

    public function step6()
    {
        if (!$this->lemma) {
            $suffixes = [
                'α',
                'αγατε',
                'αγαν',
                'αει',
                'αμαι',
                'αν',
                'ασ',
                'ας',
                'ασαι',
                'αται',
                'αω',
                'ε',
                'ει',
                'εισ',
                'ειτε',
                'εσαι',
                'εσ',
                'ες',
                'εται',
                'ι',
                'ιεμαι',
                'ιεμαστε',
                'ιεται',
                'ιεσαι',
                'ιεσαστε',
                'ιομασταν',
                'ιομουν',
                'ιομουνα',
                'ιονταν',
                'ιοντουσαν',
                'ιοσασταν',
                'ιοσαστε',
                'ιοσουν',
                'ιοσουνα',
                'ιοταν',
                'ιουμα',
                'ιουμαστε',
                'ιουνται',
                'ιουνταν',
                'η',
                'ηδεσ',
                'ηδων',
                'ηθει',
                'ηθεισ',
                'ηθειτε',
                'ηθηκατε',
                'ηθηκαν',
                'ηθουν',
                'ηθω',
                'ηκατε',
                'ηκαν',
                'ησ',
                'ης',
                'ησαν',
                'ησατε',
                'ησει',
                'ησεσ',
                'ησουν',
                'ησω',
                'ο',
                'οι',
                'ομαι',
                'ομασταν',
                'ομουν',
                'ομουνα',
                'ονται',
                'ονταν',
                'οντουσαν',
                'οσ',
                'ος',
                'οσασταν',
                'οσαστε',
                'οσουν',
                'οσουνα',
                'οταν',
                'ου',
                'ουμαι',
                'ουμαστε',
                'ουν',
                'ουνται',
                'ουνταν',
                'ουσ',
                'ους',
                'ουσαν',
                'ουσατε',
                'υ',
                'υσ',
                'υς',
                'ω',
                'ων',
                'ωσεισ',
                'ασεισ'
            ];
            $verbs = [
                'αει',
                'αμαι',
                'εται',
                'ιεμαι',
                'ιεμαστε',
                'ιεται',
                'ιεσαι',
                'ιεσαστε',
                'ιομασταν',
                'ιομουν',
                'ιομουνα',
                'ιονταν',
                'ιοντουσαν',
                'ιοσασταν',
                'ιοσαστε',
                'ιοσουν',
                'ιοσουνα',
                'ιοταν',
                'ιουμα',
                'ιουμαστε',
                'ιουνται',
                'ιουνταν',
                'ηδων',
                'ηθει',
                'ηθεισ',
                'ηθειτε',
                'ηθηκατε',
                'ηθηκαν',
                'ηθουν',
                'ηθω',
                'ηκατε',
                'ηκαν',
                'ησαν',
                'ησατε',
                'ησει',
                'ησεσ',
                'ησουν',
                'ησω',
                'ομαι',
                'ομασταν',
                'ομουν',
                'ομουνα',
                'ονται',
                'ονταν',
                'οντουσαν',
                'οσασταν',
                'οσαστε',
                'οσουν',
                'οσουνα',
                'οταν',
                'ουμαι',
                'ουμαστε',
                'ουνται',
                'ουν',                
                'ουνταν',
                'ουσαν',
                'ουσατε',
                'ει',
                'ειτε',
                'εσαι', 
                'ωσεισ',
                'ασεισ'                               
            ];
            usort($suffixes, [$this, 'sortByLength']);
            foreach ($suffixes as $suffix) {
                if (str_ends_with($this->word, $this->filter($suffix))) {
                    $this->lemma = mb_substr($this->word, 0, -(mb_strlen($suffix)));
                    if (in_array($suffix, $verbs)) {
                        $this->part_of_speech = 'verb';
                    }
                    break;   
                }
            }
        }
        $this->logStep(__METHOD__);        
        return $this;
    }
    
    public function runSteps()
    {
        $this->lemma = '';
        $this->stem = '';
        $this->step = '';
        $this->part_of_speech = '';

        $this->steps1()
        ->steps2()
        ->steps3()
        ->steps4()
        ->steps5()
        ->steps6() 
        ->steps7()  
        ->steps8()
        ->steps9()
        ->steps10()                 
        ->step2a()
        ->step2b()
        ->step2c()
        ->step2d()
        ->step3()
        ->step4()
        ->step5a()
        ->step5b()
        ->step5b2()
        ->step5c()
        ->step5d()
        ->step5e()
        ->step5f()
        ->step5f2()
        ->step5g()
        ->step5h()
        ->step5i()
        ->step5j()
        ->step5k()
        ->step5l()
        ->step5m()
        ->step6();

        return $this->lemma;
    }

    public function logStep($method)
    {
        if ($this->lemma && !$this->step) {
            $this->step = str_replace('App\Console\Commands\GreekStemmer::', '', $method);
        }
    }  

}