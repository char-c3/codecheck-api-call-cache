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
    if ($n % 2 === 0) {
        $sum = 0;
        foreach ([1,2,3,4] as $i) {
            $m = $n - $i;
            if ($m < 0) {
                break;
            }
            if (!isset($f_memo[$m])) {
                $f_memo[$m] = f($m);
            }
            $sum = $f_memo[$m];
        }
        return $sum;
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
        exit;
    }

    $seed = $argv[0];
    $n = $argv[1];

    if (!is_numeric($n)) {
        showUsage();
        echo "2nd argument should be integer.\n";
        exit;
    }
    
    echo f(intval($n)), PHP_EOL;
}

