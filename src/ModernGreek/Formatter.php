<?php namespace Lemma\ModernGreek;

class Formatter
{   
    public static function unaccent($token, $skip = false)
    {
        if ($skip) {
            return $token;
        }        
        ini_set('default_charset', 'UTF-8');

        $accents = [
            'ς' => 'σ',

            mb_chr(940)             => mb_chr(945), // ά => a
            mb_chr(945).mb_chr(769) => mb_chr(945), // ά => a

            mb_chr(941)             => mb_chr(949), // έ => ε
            mb_chr(949).mb_chr(769) => mb_chr(949), // έ => ε

            mb_chr(942)             => mb_chr(951), // ή => η 
            mb_chr(951).mb_chr(769) => mb_chr(951), // ή => η 

            mb_chr(943)             => mb_chr(953), // ί => i 
            mb_chr(953).mb_chr(769) => mb_chr(953), // ί => i 

            mb_chr(973)             => mb_chr(965), // ΰ => u 
            mb_chr(965).mb_chr(769) => mb_chr(965), // ΰ => u 

            mb_chr(972)             => mb_chr(959), // ό => o 
            mb_chr(959).mb_chr(769) => mb_chr(959), // ό => o 

            mb_chr(974)             => mb_chr(969), // ώ => ω 
            mb_chr(969).mb_chr(769) => mb_chr(969), // ώ => ω                          
        ];
        foreach ($accents as $accent => $unaccented) {
            $token = mb_ereg_replace($accent, $unaccented, $token);
        }
        return $token;    
    }
    
    public static function normalize($token, $skip = false)
    {
        if ($skip) {
            return $token;
        }        
        ini_set('default_charset', 'UTF-8' );
    
        $accents = [
            mb_chr(945).mb_chr(769) => mb_chr(940), // ά => ά
            mb_chr(949).mb_chr(769) => mb_chr(941), // έ => έ
            mb_chr(951).mb_chr(769) => mb_chr(942), // ή => ή 
            mb_chr(953).mb_chr(769) => mb_chr(943), // ί => ί 
            mb_chr(965).mb_chr(769) => mb_chr(973), // ΰ => ΰ 
            mb_chr(959).mb_chr(769) => mb_chr(972), // ό => ό 
            mb_chr(969).mb_chr(769) => mb_chr(974), // ώ => ώ              
        ];
        foreach ($accents as $accent => $unaccented) {
            $token = mb_ereg_replace($accent, $unaccented, $token);
        }
        return $token;    
    }

    public static function encoding($token)
    {   
        return mb_detect_encoding($token);
    }

    public static function utf8($token)
    {   
        $str = '';
        $chs = mb_str_split($token);
        foreach ($chs as $c) {
            $str .= ' '.mb_ord($c, "utf8");
        }
        $str .= ' ';
        foreach ($chs as $c) {
            $str .= mb_chr(mb_ord($c, "utf8"));
        }        
        return $str;
    }   
    
    public static function format($token, $has_accents = true)
    {
        return trim(
            self::unaccent(self::normalize(mb_strtolower(trim($token))),$has_accents)
        );
    }    
}