<?php
    
    function get_text_elements($text){
        $text_chars = [];
        for($x = 0; $x < strlen($text); $x++){
            array_push($text_chars, $text[$x]);
        }
        return $text_chars;
    }

    function key_generator($text){
        $chars = [
        'q', 'Q', 'w', 'W', 'e', 'E', 'r', 'R', 't', 'T',
        'y', 'Y', 'u', 'U', 'i', 'I', 'o', 'O', 'p', 'P',
        'a', 'A', 's', 'S', 'd', 'D', 'f', 'F', 'g', 'G',
        'h', 'H', 'j', 'J', 'k', 'K', 'l', 'L', 'z', 'Z',
        'x', 'X', 'c', 'C', 'v', 'V', 'b', 'B', 'n', 'N',
        'm', 'M', '1', '!', '2', '@', '3', '#', '4', '$',
        '5', '%', '6', '^', '7', '&', '8', '*', '9', '0',
        '-', '_', '=', '+', '{', '}', '[', ']', '\\', '"',
        ';', ':', ',', '.', '<', '>', '/', '?', '`', '~', ' ', '|'];
        
        $len = strlen($text);
        $te = get_text_elements($text);
        $key = '';
        for ($x = 0; $x < count($te); $x++) {
            $index = array_search($te[$x], $chars);
            $fixed_value = count($chars) - 1 - $index;
            if($fixed_value > 9){
                $random_int = random_int(1,9);
            }
            else{
                $random_int = random_int(1, $fixed_value);
            }
            $value = $random_int + $index;
            $key .= strval($random_int);
        }
        return strval($key);
    }
 
    function modify_chars($mode, $text, $key){
        $chars = [
        'q', 'Q', 'w', 'W', 'e', 'E', 'r', 'R', 't', 'T',
        'y', 'Y', 'u', 'U', 'i', 'I', 'o', 'O', 'p', 'P',
        'a', 'A', 's', 'S', 'd', 'D', 'f', 'F', 'g', 'G',
        'h', 'H', 'j', 'J', 'k', 'K', 'l', 'L', 'z', 'Z',
        'x', 'X', 'c', 'C', 'v', 'V', 'b', 'B', 'n', 'N',
        'm', 'M', '1', '!', '2', '@', '3', '#', '4', '$',
        '5', '%', '6', '^', '7', '&', '8', '*', '9', '0',
        '-', '_', '=', '+', '{', '}', '[', ']', '\\',
        '"', ';', ':', ',', '.', '<', '>', '/', '?', '`', '~', ' ', '|'];

        if($mode == 'decode'){
            $data = '';
            $key_splitted = get_text_elements(strval($key));
            $len = strlen($text);
            for ($x = 0; $x < $len; $x++) {
                $index = array_search($text[$x], $chars);
                $data .= $chars[$index-intval($key_splitted[$x])];
            }
            return $data;
        }
        elseif($mode == 'encode'){
            $data = '';
            $key_splitted = get_text_elements(strval($key));
            $len = strlen($text);
            for ($x = 0; $x < $len; $x++) {
                $index = array_search($text[$x], $chars);
                $data .= $chars[$index+intval($key_splitted[$x])];
            }
            return $data;
        }
    }

    function decode($text){
        $list_of_chars = [
        'q' => 'QA>A', 'Q' => 'Z`Tn', 'w' => 'v#ZA', 'W' => 'DJo1', 'e' => '$q%n', 'E' => '-epe',
        'r' => 's\oz', 'R' => '&Jqb', 't' => '"#`c', 'T' => 'W9@C', 'y' => '%NTv', 'Y' => 'S53]',
        'u' => 'R>$3', 'U' => 'kb"!', 'i' => ',8QN', 'I' => '<;P}', 'o' => '5e0L', 'O' => 'VbtT',
        'p' => 'nk8}', 'P' => '=OqJ', 'a' => 'tlWL', 'A' => 'L!,A', 's' => 'x_B+', 'S' => '"bC[',
        'd' => 'D}a"', 'D' => 'R$Sx', 'f' => '9mLR', 'F' => '!si4', 'g' => 'DA1k', 'G' => 'PP7-',
        'h' => 'Q2Zl', 'H' => 'lrTt', 'j' => '=2"k', 'J' => '%3H2', 'k' => 'jDP^', 'K' => '"^SQ',
        'l' => 'qN6F', 'L' => '$;Dr', 'z' => '15~c', 'Z' => 'TYsB', 'x' => 'b{#p', 'X' => '[$P9',
        'c' => '5EXq', 'C' => '&D&4', 'v' => 'gE@^', 'V' => '2Ck2', 'b' => '0~Ps', 'B' => '#8*v',
        'n' => 'gf&;', 'N' => 'M\K]', 'm' => 'H4B6', 'M' => 'l\A!', '1' => 'sF_i', '!' => 'fZti',
        '2' => '7<oZ', '@' => 'yzfM', '3' => 'R>f&', '#' => '*UU8', '4' => 'R7Vs', '$' => 'kyP"',
        '5' => 'Gv>&', '%' => 'i!H$', '6' => 'klz-', '^' => '4*qx', '7' => '~216', '&' => 'b>?8',
        '8' => '%}7O', '*' => '%Sj{', '9' => 'ud:8', '0' => '`8Q4', '-' => '^3Tm', '_' => 'X+"5',
        '=' => '>/H+', '+' => '8{\s', '{' => '~Go6', '}' => 'kgb5', '[' => 'oL%T', ']' => 'fn\e',
        '\\' => 'wttu', '"' => '?,nE', ';' => 'r^Aw', ':' => '1sS*', ',' => 'z<b[', '.' => 'EXTU',
        '<' => '&2Qa', '>' => '#]%k', '/' => '^g,z', '?' => '2!6Y', '`' => '6dIV', '~' => '8{Yz',
        ' ' => 'BhaQ', '|' => 'PPKA'];

        $data = '';
        $decoded = base64_decode($text);
        $key_salt = explode('.', $decoded)[0];
        $decoded_text = explode('.', $decoded)[1];
        $crypt = str_split($decoded_text, 4);
        for($x = 0; $x < count($crypt); $x++){
            foreach($list_of_chars as $key=>$value) {
                if($crypt[$x] == $value){
                    $data .= $key;
                }
            }
        }
        $message = modify_chars('decode', $data, strval($key_salt));
        return $message;
    }

    function encode($text){
        $list_of_chars = [
        'q' => 'QA>A', 'Q' => 'Z`Tn', 'w' => 'v#ZA', 'W' => 'DJo1', 'e' => '$q%n', 'E' => '-epe',
        'r' => 's\oz', 'R' => '&Jqb', 't' => '"#`c', 'T' => 'W9@C', 'y' => '%NTv', 'Y' => 'S53]',
        'u' => 'R>$3', 'U' => 'kb"!', 'i' => ',8QN', 'I' => '<;P}', 'o' => '5e0L', 'O' => 'VbtT',
        'p' => 'nk8}', 'P' => '=OqJ', 'a' => 'tlWL', 'A' => 'L!,A', 's' => 'x_B+', 'S' => '"bC[',
        'd' => 'D}a"', 'D' => 'R$Sx', 'f' => '9mLR', 'F' => '!si4', 'g' => 'DA1k', 'G' => 'PP7-',
        'h' => 'Q2Zl', 'H' => 'lrTt', 'j' => '=2"k', 'J' => '%3H2', 'k' => 'jDP^', 'K' => '"^SQ',
        'l' => 'qN6F', 'L' => '$;Dr', 'z' => '15~c', 'Z' => 'TYsB', 'x' => 'b{#p', 'X' => '[$P9',
        'c' => '5EXq', 'C' => '&D&4', 'v' => 'gE@^', 'V' => '2Ck2', 'b' => '0~Ps', 'B' => '#8*v',
        'n' => 'gf&;', 'N' => 'M\K]', 'm' => 'H4B6', 'M' => 'l\A!', '1' => 'sF_i', '!' => 'fZti',
        '2' => '7<oZ', '@' => 'yzfM', '3' => 'R>f&', '#' => '*UU8', '4' => 'R7Vs', '$' => 'kyP"',
        '5' => 'Gv>&', '%' => 'i!H$', '6' => 'klz-', '^' => '4*qx', '7' => '~216', '&' => 'b>?8',
        '8' => '%}7O', '*' => '%Sj{', '9' => 'ud:8', '0' => '`8Q4', '-' => '^3Tm', '_' => 'X+"5',
        '=' => '>/H+', '+' => '8{\s', '{' => '~Go6', '}' => 'kgb5', '[' => 'oL%T', ']' => 'fn\e',
        '\\' => 'wttu', '"' => '?,nE', ';' => 'r^Aw', ':' => '1sS*', ',' => 'z<b[', '.' => 'EXTU',
        '<' => '&2Qa', '>' => '#]%k', '/' => '^g,z', '?' => '2!6Y', '`' => '6dIV', '~' => '8{Yz',
        ' ' => 'BhaQ', '|' => 'PPKA'];
        
        $key = key_generator($text);
        $data = $key.'.';
        $message = modify_chars('encode', $text, strval($key));
        for($x = 0; $x < strlen($message); $x++){
            $data .= $list_of_chars[$message[$x]];
        }
        $encoded = base64_encode($data);
        return $encoded;
    }

    #$today = getdate();
    #$date = date('Y-m-d');#$today['year'].'-'.$today['mon'].'-'.$today['mday'];
    #echo date('Y-m-d', strtotime($date. ' + 1 days'));
    #echo $date;
?>