<?php namespace Codecheck;

define("URL", "http://challenge-server.code-check.io/api/recursive/ask");
$result_cache = [];
$f_memo = [1, null, 2];
$seed = "";

function askServer($n) {
    global $seed;
    $query = "?n=" . $n . "&seed=" . urlencode($seed);
    $json = file_get_contents(URL . $query);
    $data = json_decode($json);
    return intval($data->result);
}

function f($n) {
    global $f_memo, $result_cache;
    if ($n == 0) {
        return 1;
    } 
    if ($n == 2) {
        return 2;
    }

    if ($n % 2 === 0) {
        if (!isset($f_memo[$n])) {
            $sum = 0;
            foreach ([1,2,3,4] as $i) {
                $m = $n - $i;
                if ($m < 0) {
                    break;
                }
                $sum = f($m);
            }
            $f_memo[$n] = $sum;
        }
        return $f_memo[$n];
    } else {
        if (!isset($result_cache[$n])) {
            $result_cache[$n] = askServer($n);
        }
        return $result_cache[$n];
    }
}

function showUsage() {
    echo "Usage: php Main.php seed(string) n(integer)\n";
}

function run ($argc, $argv)
{
    global $seed;

    if ($argc < 2) {
        showUsage();
        echo "too few arguments.\n";
        exit(1);
    }

    $seed = $argv[0];
    $n = $argv[1];

    if (!is_numeric($n)) {
        showUsage();
        echo "2nd argument should be integer.\n";
        exit(1);
    }
    
    echo f(intval($n)), PHP_EOL;
}

