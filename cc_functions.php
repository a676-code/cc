<?php
// ini_set('memory_limit', '1G');
function consecutivePairExists($x)
{
    $found = False;
    f($x);

    $i = 0;
    while (!$found && $i < sizeof($chain))
    {
        if (
            $chain[$i] > 2 && 
            (array_search($chain[$i] - 1, $chain) !== False)
            || 
            (array_search($chain[$i] + 1, $chain) !== False)
        )
            $found = True;

        $i++;
    }
    return $found;
}

function create_unbounded_unproven_no_check($mysqli, $x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    $eval = getEval($x, $x, $oddCoefficient, $oddAddend, $evenDivisor, $primeSequences);
    // if unbounded
    if ($eval['total_stopping_time'] < 0 && !unbounded_proven($x, $oddCoefficient, $oddAddend, $evenDivisor))
        write_unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor);
}

function colorPrimes($string)
{
    $stringDebris = explode(", ", $string);
    $newString = "";
    $size = sizeof($stringDebris);
    for ($i = 0; $i < $size; $i++)
    {
        if ($stringDebris[$i] > 0 && prime($stringDebris[$i]))
            $newString = $newString."<font color=\"mediumorchid\">".$stringDebris[$i]."</font>";
        else
            $newString = $newString.$stringDebris[$i];
        if ($i < $size - 1)
            $newString = $newString.", ";
    }
    return $newString;
}

function display($array)
{
    print("<pre>");
    print_r($array);
    print("</pre>");
}

// FIX: figure out and generalize
function f_inv($x)
{
    $values;

    if ($x % 6 == 0 || $x % 6 == 1 || $x % 6 == 2 || $x % 6 == 3 || $x % 6 == 5)
        $values[] = 2 * $x;
    
    if ($x % 6 == 4)
    {
        $values[] = 2 * $x;
        $values[] = ($x - 1) / 3;
    }
    return $values;
}

function getEval($x, $x0, $oddCoefficient, $oddAddend, $evenDivisor, $data = array(), $chain = array(), $loop = array(), $call = 1, $stoppingTimeFound = False, $stoppingTime = -40, $unboundedUnproven = -6, $oddNegative = -7, $evenNegative = -8)
{
    if (!$stoppingTimeFound && $x < $x0)
    {
        $stoppingTimeFound = True;
        $stoppingTime = $call;
    }

    if ($x > 0)
    {
        if ($x % 2 == 1)
        {
            if (overflowMultiply($oddCoefficient, $x))
            {
                $chain[] = -1;
                $loop[] = -1;
                $stoppingTime = -1;
                $totalStoppingTime = -1;
            }
            else if (overflowAdd($oddCoefficient * $x, $oddAddend))
            {
                $chain[] = -2;
                $loop[] = -2;
                $stoppingTime = -2;
                $totalStoppingTime = -2;
            }
            else if (unbounded_proven($x, $oddCoefficient, $oddAddend, $evenDivisor))
            {
                $value = unbounded_proven_case($x, $oddCoefficient, $oddAddend, $evenDivisor);
                $chain[] = $value;
                $loop[] = $value;
                $stoppingTime = $value;
                $totalStoppingTime = $value;
            }
            else if (unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor))
            {
                $chain[] = $unboundedUnproven; // -6
                $loop[] = $unboundedUnproven;
                $stoppingTime = $unboundedUnproven;
                $totalStoppingTime = $unboundedUnproven;
            }
            else
            {
                $chain[] = $x;
                $index = array_search($oddCoefficient * $x + $oddAddend, $chain);
                if ($index !== False)
                {
                    $loop = array_slice($chain, $index);
                    $data['loop'] = $loop;
                    if (!$stoppingTimeFound)
                        $stoppingTime = $oddNegative; // loop found but not < x0
                    $totalStoppingTime = $index + 1;
                }
                else
                {
                    return getEval($oddCoefficient * $x + $oddAddend, $x0, $oddCoefficient, $oddAddend, $evenDivisor, $data, $chain, $loop, $call + 1, $stoppingTimeFound, $stoppingTime);
                }
            }
        }
        else if ($x % 2 == 0)
        {
            if (unbounded_proven($x, $oddCoefficient, $oddAddend, $evenDivisor))
            {
                $value = unbounded_proven_case($x, $oddCoefficient, $oddAddend, $evenDivisor);
                $chain[] = $value;
                $loop[] = $value;
                $stoppingTime = $value;
                $totalStoppingTime = $value;
            }
            else if (unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor))
            {
                $chain[] = $unboundedUnproven;
                $loop[] = $unboundedUnproven;
                $stoppingTime = $unboundedUnproven;
                $totalStoppingTime = $unboundedUnproven;
            }
            else
            {
                $chain[] = $x;
                $index = array_search($x / $evenDivisor, $chain);
                if ($index !== False)
                {
                    $loop = array_slice($chain, $index);
                    $data['loop'] = $loop;
                    if (!$stoppingTimeFound)
                        $stoppingTime = $evenNegative; // loop found but not < x0
                    $totalStoppingTime = $index + 1;
                }
                else
                    return getEval($x / $evenDivisor, $x0, $oddCoefficient, $oddAddend, $evenDivisor, $data, $chain, $loop, $call + 1, $stoppingTimeFound, $stoppingTime);
            }
        }
    }

    $data['chain'] = $chain;
    $data['prime_chain'] = getPrimeArray($chain);
    if (!array_key_exists('loop', $data))
        $data['loop'] = $loop;
    $data['prime_loop'] = getPrimeArray($loop);
    $data['stopping_time'] = $stoppingTime;
    $data['total_stopping_time'] = $totalStoppingTime;
    return $data;
}

function getFileArray($fileName)
{
    $i = 0;
    $functionFound = False;
    $saveValues = False;
    $fileArray = array();
    $functionNum = 0;
    $handle = fopen($fileName, "r");
    while (!feof($handle))
    {
        // $line = str_replace(array("\n", "\r"), "", fgets($handle));
        $line = fgets($handle);
        $lines[] = $line;

        if (trim($line) == "return True;" && !$functionFound && empty($functionIndex))
            $returnTrueIndex = $i;

        // if a function is found
        $trimmedLine = str_replace(array("\n", "\r", " "), "", $line);
        if (substr($trimmedLine, 0, 3) == "//(")
        {
            // set $curFunction
            $curFunction = $trimmedLine[3];
            $m = 1;
            while ($trimmedLine[3 + $m] != ')')
            {
                $curFunction = $curFunction.$trimmedLine[3 + $m];
                $m++;
            }
            $curFunctionDebris = explode(",", $curFunction);

            // save line number and function name
            $fileArray[] = array();
            $functionLine = $i;
            $fileArray[sizeof($fileArray) - 1][] = $i;
            $fileArray[sizeof($fileArray) - 1][] = $curFunction;
            $fileArray[sizeof($fileArray) - 1][] = array();
            $saveValues = True;
            $k = 0;
            $functionNum++;
        }

        // save values
        if ($saveValues && $i >= $functionLine + 3)
        {
            if (substr(trim($lines[$i]), 0, 2) == "\$x")
            {
                $trimmed = trim($lines[$i]);
                $num = $trimmed[6];
                $m = 1;
                while (6 + $m < strlen($trimmed) && ctype_digit($trimmed[6 + $m]))
                {
                    $num = $num.$trimmed[6 + $m];
                    $m++;
                }

                $fileArray[sizeof($fileArray) - 1][2][] = $num;
                $k++;
            }
            else // done saving x's until next function
                $saveValues = False;
        }
        $i++;
    }
    fclose($handle);
    return $fileArray;
}

function getLayer($n)
{
    $layers = getLayers($n);
    return $layers[$n];
}

function getLayers($n)
{
    $start[] = 1;
    $two[] = 2;
    $layers[] = $start;
    $layers[] = $two;

    for ($i = 1; $i < $n; $i++)
    {
        $layers[] = $newVec;
        for ($j = 0; $j < sizeof($layers[$i]); $j++)
        {
            $toBeInserted = f_inv($layers[$i][j]);
            $layers[$i + 1].$insert
            (
                $layers[$i + 1].end(), 
                $toBeInserted.begin(), 
                $toBeInserted.end()
            );
        }
    }
    return $layers;
}

// FIX: inconsistent loop output for sequences that go to infinity (?)
function getLoop($x, $oddCoefficient, $oddAddend, $evenDivisor, $chain = array(), $loop = array(), $call = 1)
{
    if ($x > 0)
    {
        if ($x % 2 == 1)
        {
            if (overflowMultiply($oddCoefficient, $x))
                $loop[] = -1;
            else if (overflowAdd($oddCoefficient * $x, $oddAddend))
                $loop[] = -2;
            else if (unbounded_proven($x, $oddCoefficient, $oddAddend, $evenDivisor))
                $loop[] = unbounded_proven_case($x, $oddCoefficient, $oddAddend, $evenDivisor);
            else if (unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor))
                $loop[] = -6;
            else
            {
                $chain[] = $x;
                $index = array_search($oddCoefficient * $x + $oddAddend, $chain);
                if ($index !== False)
                {
                    $loop = array_slice($chain, $index);
                    return $loop;
                }
                else
                {
                    return getLoop($oddCoefficient * $x + $oddAddend,$oddCoefficient, $oddAddend, $evenDivisor, $chain, $loop, $call + 1);
                }
            }
        }
        else if ($x % 2 == 0)
        {
            $chain[] = $x;
            $index = array_search($x / $evenDivisor, $chain);
            if ($index !== False)
            {
                $loop = array_slice($chain, $index);
                return $loop;
            }
            else
            {
                return getLoop($x / $evenDivisor, $oddCoefficient, $oddAddend, $evenDivisor, $chain, $loop, $call + 1);
            }
        }
    }
    return $loop;
}

function getLoops($max)
{
    for ($x = 1; $x < $max; $x++)
    {
        $loop = getLoop($x, $oddCoefficient, $oddAddend, $evenDivisor);
        if (array_search($loop, $loops) === False)
            $loops[] = $loop;
    }
    return $loops;
}

function getNumDrops($x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    $numDrops = 0;
    $chain = getChain($x, $oddCoefficient, $oddAddend, $evenDivisor);
    for ($i = 0; $i < sizeof($chain); $i++)
    {
        if ($chain[$i] % 2 == 0)
            $numDrops++;
    }
    return $numDrops;
}

function getNumInLayer($n) { return sizeof(getLayer(n)); }

function getNumJumps($x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    $numJumps = 0;
    $chain = getChain($x, $oddCoefficient, $oddAddend, $evenDivisor);
    for ($i = 0; $i < sizeof($chain); $i++)
    {
        if ($chain[$i] % 2 == 1)
            $numJumps++;
    }
    return $numJumps;
}

function getPrimeArray($array)
{
    for ($i = 0; $i < sizeof($array); $i++)
    {
        if ($array[$i] > 0)
        {
            if (prime($array[$i]))
                $array[$i] = 1;
            else
                $array[$i] = 0;
        }
    }
    return $array;
}

function getTotalStoppingTime($x, $oddCoefficient, $oddAddend, $evenDivisor, $chain = array(), $call = 1)
{
    $chain[] = $x;

    if ($x > 0)
    {
        if ($x % 2 == 1)
        {
            if (overflowMultiply($oddCoefficient, $x))
            {
                return -1;
            }
            else if (overflowAdd($oddCoefficient * $x, $oddAddend))
            {
                return -2;
            }
            else if (unbounded_proven($x, $oddCoefficient, $oddAddend, $evenDivisor))
            {
                return unbounded_proven_case($x, $oddCoefficient, $oddAddend, $evenDivisor);
            }
            else if (unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor))
            {
                return unbounded_proven_case($x, $oddCoefficient, $oddAddend, $evenDivisor);
            }
            else
            {
                $index = array_search($oddCoefficient * $x + $oddAddend, $chain);
                if ($index !== False)
                    return $index + 1;
                else
                    return getTotalStoppingTime($oddCoefficient * $x + $oddAddend, $oddCoefficient, $oddAddend, $evenDivisor, $chain, $call + 1);
            }
        }
        else if ($x % 2 == 0)
        {
            $index = array_search($x / $evenDivisor, $chain);
            if ($index !== False)
                return $index + 1;
            else
                return getTotalStoppingTime($x / $evenDivisor, $oddCoefficient, $oddAddend, $evenDivisor, $chain, $call + 1);
        }
    }
    return -10; // shouldn't get to here
}

function instability($oddCoefficient, $oddAddend, $evenDivisor)
{
    $eval1 = getEval(1, 1, $oddCoefficient, $oddAddend, $evenDivisor);
    $loop1 = $eval1['loop'];
    $emptyArray = array();

    $instabilityFound = False;
    $x = 2;
    while (!$instabilityFound && $x < 1000)
    {
        $curLoop = getLoop($x, $oddCoefficient, $oddAddend, $evenDivisor);

        // checking if there's anything in the current loop that isn't in the first loop
        if (sizeof(array_diff($curLoop, $loop1)) != 0)
        {
            $instabilityFound = True;
            return $x;
        }
        $x++;
    }
    return -1;
}

function unbounded_proven($x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    if (
        unbounded_proven_1($x, $oddCoefficient, $oddAddend, $evenDivisor)
        ||
        unbounded_proven_2($x, $oddCoefficient, $oddAddend, $evenDivisor)
        ||
        unbounded_proven_3($x, $oddCoefficient, $oddAddend, $evenDivisor)
    )
        return True;
    // if ($oddCoefficient == 6 && $oddAddend % 6 != 0 && $evenDivisor == 2)
    // {
    //     $addend = $oddAddend;
    //     $sixk = 0;
    //     while ($addend > 6)
    //     {
    //         $addend = $addend - 6;
    //         $sixk = $sixk + 6;
    //     }
    //     // (6, 6k + 2^(((k - 1) % 2) + 1), 2), k = 1, 2, ...
    //     if (
    //         !empty($sixk) && !empty($addend) &&
    //         ($oddCoefficient == 6 && 
    //         (
    //             $oddAddend == $sixk + $addend && 
    //             $oddAddend % 6 != 0 && 
    //             $sixk % 6 == 0 && 
    //             ($addend == 2 || $addend == 4)
    //         ) && 
    //         $evenDivisor == 2
    //         )
    //     )
    //         return True;
    // }
    return False;
}

function unbounded_proven_case($x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    $num = -3;
    if (// ex + o = o, ox + e = ...o
    unbounded_proven_1($x, $oddCoefficient, $oddAddend, $evenDivisor)
    )
        return $num; // -3
        $num--;

    // (e, eh, 2), (eh, e, 2), h % 2 = 0
    if (unbounded_proven_2($x, $oddCoefficient, $oddAddend, $evenDivisor))
        return $num; // -4
        $num--;
    
    // (2^k, 2^km + 2^k - \ell, 2), 2 <= \ell <= 2^k - 2
    // (2^km + 2^k - \ell, 2^k, 2), 2 <= \ell <= 2^k - 2
    if (unbounded_proven_3($x, $oddCoefficient, $oddAddend, $evenDivisor))
        return $num; // -5
    
    return -100;
}

function unbounded_proven_1($x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    if (// ex + o = o, ox + e = ...o
        ($oddCoefficient % 2 == 0 && $oddAddend % 2 == 1)
        || 
        ($oddCoefficient % 2 == 1 && $oddAddend % 2 == 0)
    )
    return True;
}

function unbounded_proven_2($x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    if (// (e, eh, 2), (eh, e, 2), h % 2 = 0
        (
            $oddCoefficient % 2 == 0 
            && $oddAddend % $oddCoefficient == 0 
            && ($oddAddend / $oddCoefficient) % 2 == 0
            && $evenDivisor == 2
        )
        || 
        (
            $oddCoefficient % $oddAddend == 0
            && $oddAddend % 2 == 0
            && ($oddCoefficient / $oddAddend) % 2 == 0
            && $evenDivisor == 2
        )
    )
    return True;
}

function unbounded_proven_3($x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    // (2^k, 2^km + 2^k - \ell, 2), 2 <= \ell <= 2^k - 2
    if (powerOf2($oddCoefficient) && $oddAddend % $oddCoefficient != 0 && $evenDivisor == 2)
    {
        $addend = $oddAddend;
        $twotothek = 0;
        while ($addend > $oddCoefficient)
        {
            $addend = $addend - $oddCoefficient;
            $twotothek = $twotothek + $oddCoefficient;
        }
        if (
            !empty($twotothek) && !empty($addend) &&
            (powerOf2($oddCoefficient) && 
            (
                $oddAddend == $twotothek + $addend && 
                $oddAddend % $oddCoefficient != 0 && 
                $twotothek % $oddCoefficient == 0 &&
                ($addend % 2 == 0 && $addend >= 2 && $addend <= $oddCoefficient - 2)
            ) && 
            $evenDivisor == 2
            )
        )
            return True;
    }
    
    // (2^km + 2^k - \ell, 2^k, 2), 2 <= \ell <= 2^k - 2
    if (powerOf2($oddAddend) && $oddCoefficient % $oddAddend != 0 && $evenDivisor == 2)
    {
        $addend = $oddCoefficient;
        $twotothek = 0;
        while ($addend > $oddAddend)
        {
            $addend = $addend - $oddAddend;
            $twotothek = $twotothek + $oddAddend;
        }
        if (
            !empty($twotothek) && !empty($addend) &&
            (powerOf2($oddAddend) && 
            (
                $oddCoefficient == $twotothek + $addend && 
                $oddCoefficient % $oddAddend != 0 && 
                $twotothek % $oddAddend == 0 &&
                ($addend % 2 == 0 && $addend >= 2 && $addend <= $oddAddend - 2)
            ) && 
            $evenDivisor == 2
            )
        )
            return True; // -9
    }
    return False;
}

function insert($mysqli, $x, $oddCoefficient, $oddAddend, $evenDivisor, $unboundedUnproven = False, &$oneAdded = False)
{
    if (!unbounded_proven($x, $oddCoefficient, $oddAddend, $evenDivisor))
    {
        $checkFunctionExistsStmt = $mysqli->prepare("SELECT * FROM `function` f WHERE f.odd_coefficient = '".$oddCoefficient."' AND
        f.odd_addend = '".$oddAddend."' AND 
        f.even_divisor = '".$evenDivisor."'");
        $checkFunctionExistsStmt->execute();
        $result = $checkFunctionExistsStmt->get_result();
        if ($result->num_rows === 0)
            $functionExists = False;
        else
            $functionExists = True;

        if (!$functionExists)
        {
            if (stabilizes($oddCoefficient, $oddAddend, $evenDivisor) === True)
                $stabilizes = 1;
            else
                $stabilizes = 0;
            $functionStmt = $mysqli->prepare("INSERT INTO cc.function VALUES (?, ?, ?, ?)");
            $functionStmt->bind_param("iiii", $oddCoefficient, $oddAddend, $evenDivisor, $stabilizes);
            $functionStmt->execute();
            if ($functionStmt->affected_rows === 0)
                exit("Error: no rows affected");
            $functionStmt->close();
            $commit = $mysqli->prepare("COMMIT;");
            $commit->execute();
            $commit->close();
        }

        $checkEvalExistsStmt = $mysqli->prepare("SELECT * FROM evaluation e 
        WHERE e.odd_coefficient = '".$oddCoefficient."' AND
        e.odd_addend = '".$oddAddend."' AND 
        e.even_divisor = '".$evenDivisor."' AND
        e.value = '".$value."'");
        $checkEvalExistsStmt->execute();
        $result = $checkEvalExistsStmt->get_result();
        if ($result->num_rows === 0)
            $evalExists = False;
        else
            $evalExists = True;

        if (!$evalExists)
        {
            $eval = getEval($x, $x, $oddCoefficient, $oddAddend, $evenDivisor);
            if ($eval['chain'][sizeof($eval['chain']) - 1] < 0)
                $chainLength = -1;
            else
                $chainLength = sizeof($eval['chain']);
            if ($eval['loop'][sizeof($eval['loop']) - 1] < 0)
                $loopLength = NULL;
            else
                $loopLength = sizeof($eval['loop']);
            if ($chainLength == -1 || $loopLength == NULL)
                $clRatio = NULL;
            else
                $clRatio = $chainLength / $loopLength;

            // if unbounded
            if ($unboundedUnproven)
            {
                if ($eval['total_stopping_time'] < 0)
                    write_unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor);
            }
            $chain = implode(", ", $eval['chain']);
            $primeChain = implode(", ", $eval['prime_chain']);
            $loop = implode(", ", $eval['loop']);
            $primeLoop = implode(", ", $eval['prime_loop']);
            $evalStmt = $mysqli->prepare("INSERT INTO cc.evaluation VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $evalStmt->bind_param("iiiississidii", $oddCoefficient, $oddAddend, $evenDivisor, $x, $chain, $primeChain, $chainLength, $loop, $primeLoop, $loopLength, $clRatio, $eval['stopping_time'], $eval['total_stopping_time']);
            $evalStmt->execute();
            if ($evalStmt->affected_rows === 0)
                exit('ERROR: no rows affected');
            $evalStmt->close();
            $commit = $mysqli->prepare("COMMIT;");
            $commit->execute();
            $commit->close();

            $oneAdded = True;
        }
    }
}

function insert_no_check($mysqli, $x, $oddCoefficient, $oddAddend, $evenDivisor, $unboundedUnproven = False, &$oneAdded = False)
{
    $checkFunctionExistsStmt = $mysqli->prepare("SELECT * FROM `function` f WHERE f.odd_coefficient = '".$oddCoefficient."' AND
    f.odd_addend = '".$oddAddend."' AND 
    f.even_divisor = '".$evenDivisor."'");
    $checkFunctionExistsStmt->execute();
    $result = $checkFunctionExistsStmt->get_result();
    if ($result->num_rows === 0)
        $functionExists = False;
    else
        $functionExists = True;

    if (!$functionExists)
    {
        if (stabilizes($oddCoefficient, $oddAddend, $evenDivisor) === True)
            $stabilizes = 1;
        else
            $stabilizes = 0;
        $functionStmt = $mysqli->prepare("INSERT INTO cc.function VALUES (?, ?, ?, ?)");
        $functionStmt->bind_param("iiii", $oddCoefficient, $oddAddend, $evenDivisor, $stabilizes);
        $functionStmt->execute();
        if ($functionStmt->affected_rows === 0)
            exit("Error: no rows affected");
        $functionStmt->close();
        $commit = $mysqli->prepare("COMMIT;");
        $commit->execute();
        $commit->close();
    }

    $checkEvalExistsStmt = $mysqli->prepare("SELECT * FROM evaluation e WHERE e.odd_coefficient = '".$oddCoefficient."' AND
    e.odd_addend = '".$oddAddend."' AND 
    e.even_divisor = '".$evenDivisor."' AND
    e.value = '".$x."'");
    $checkEvalExistsStmt->execute();
    $result = $checkEvalExistsStmt->get_result();
    if ($result->num_rows === 0)
        $evalExists = False;
    else
        $evalExists = True;

    if (!$evalExists)
    {
        $eval = getEval($x, $x, $oddCoefficient, $oddAddend, $evenDivisor);
        if ($eval['chain'][sizeof($eval['chain']) - 1] < 0)
            $chainLength = -1;
        else
            $chainLength = sizeof($eval['chain']);
        if ($eval['loop'][sizeof($eval['loop']) - 1] < 0)
            $loopLength = NULL;
        else
            $loopLength = sizeof($eval['loop']);
        if ($chainLength == -1 || $loopLength == NULL)
            $clRatio = NULL;
        else
            $clRatio = $chainLength / $loopLength;

        // if unbounded
        if ($unboundedUnproven)
        {
            if ($eval['total_stopping_time'] < 0)
                write_unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor);
        }
        $chain = implode(", ", $eval['chain']);
        $primeChain = implode(", ", $eval['prime_chain']);
        $loop = implode(", ", $eval['loop']);
        $primeLoop = implode(", ", $eval['prime_loop']);
        $evalStmt = $mysqli->prepare("INSERT INTO cc.evaluation VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $evalStmt->bind_param("iiiississidii", $oddCoefficient, $oddAddend, $evenDivisor, $x, $chain, $primeChain, $chainLength, $loop, $primeLoop, $loopLength, $clRatio, $eval['stopping_time'], $eval['total_stopping_time']);
        $evalStmt->execute();
        if ($evalStmt->affected_rows === 0)
            exit('ERROR: no rows affected');
        $evalStmt->close();
        $commit = $mysqli->prepare("COMMIT;");
        $commit->execute();

        $oneAdded = True;
    }
}

function intersectOffMainChannel($x, $y, $oddCoefficient, $oddAddend, $evenDivisor)
{
    $xChain = $getChain($x, $oddCoefficient, $oddAddend, $evenDivisor);
    $yChain = $getChain($y, $oddCoefficient, $oddAddend, $evenDivisor);;

    for ($i = sizeof($xChain) - 1; $i !== False; $i--)
    {
        if (!powerOf2($xChain[$i]) && isInChain($xChain[$i], $yChain))
            return True;
    }
    return False;
}

function intersectv($v1, $v2)
{
    $newVec = array();
    if (sizeof($v1) < sizeof($v2))
    {
        for ($i = 0; $i < sizeof($v1); $i++)
        {
            if (array_search($v1[$i], $v2) !== False)
                $newVec[] = $v1[$i];
        }
    }
    else
    {
        for ($i = 0; $i < sizeof($v2); $i++)
        {
            if (array_search($v2[$i], $v1) !== False)
                $newVec[] = $v2[$i];
        }
    }
    return $newVec;
}

function powerOf2($x)
{
    while ($x % 2 == 0) { $x = $x / 2; }
    if ($x != 1)
        return False;
    else
        return True;
}

function isInChain($x, $y)
{
    f($x);

    if (array_search($y, $chain) !== False)
        return True;
    else
        return False;
}

function isInChainArray($x, $chain)
{
    if (array_search($x, $chain) !== False)
        return True;
    else
        return False;
}

function overflowAdd($x, $y)
{
    if (gettype($x + $y) != "integer")
        return True;
    return False;
}

function overflowMultiply($x, $y)
{
    if (gettype($x * $y) != "integer")
        return True;
    return False;
}

function loopsAt($x)
{
    $v = f($x);
    if (array_search($x, $v) !== False)
        return True;
    return False;
}

function posInChain($x, $y)
{
    f($x);

    if (isInChain($x, $y))
        return array_search($y, $chain);
    else
        return -1;
}

// Need longer strings!
// Fatal error: Uncaught TypeError: sqrt(): Argument #1 ($num) must be of type float, string given in C:\xampp\htdocs\cc\cc_functions.php:1089 Stack trace: #0 C:\xampp\htdocs\cc\cc_functions.php(1089): sqrt('817448,') #1 C:\xampp\htdocs\cc\cc_functions.php(41): prime('817448,') #2 C:\xampp\htdocs\cc\search.php(371): colorPrimes('31, 168, 84, 42...') #3 {main} thrown in C:\xampp\htdocs\cc\cc_functions.php on line 1089
function prime($x)
{
    if ($x == 1)
        return False;
    
    for ($i = 2; $i <= sqrt($x); $i++)
    {
        if ($x % $i == 0)
            return False;
    }
    return True;
}

function printv($v)
{
    for ($i = 0; $i < sizeof($v); $i++)
    {
        print($v[$i]);
        if ($i < sizeof($v) - 1)
            print(", ");
    }
    print("\n");
}

function printv2d($v)
{
    for ($i = 0; $i < sizeof($v); $i++)
    {
        for ($j = 0; $j < sizeof($v[$i]); $j++)
        {
            print($v[$i][$j]);
            if ($j < sizeof($v[$i]) - 1)
                print(", ");
        }
        if ($i < sizeof($v) - 1)
            print("\n");
    }
    print("\n");
}

function print_color($arrayElt, $color, $align = False)
{
    $overflowColor = "red";
    $proven['3'] = "yellow";
    $proven['4'] = "darkorange";
    $proven['5'] = "#b36200";
    $unproven = "blue";
    $oddNegativeColor = "lawngreen";
    $evenNegativeColor = "green";

    $unboundedUnproven = -6;
    if ($unboundedUnproven % 2 == 0)
    {
        $oddNegative = $unboundedUnproven - 1; // -5
        $evenNegative = $oddNegative - 1; // -4
    }
    else
    {
        $evenNegative = $unboundedUnproven - 1;
        $oddNegative = $evenNegative - 1;
    }
    
    if ($color == "text")
    {
        if (
            $arrayElt == "-1" || 
            $arrayElt == "-2"
            )
        {
            print("<td style=\"color:".$overflowColor."\"");
            if ($align)
                print(" align=\"right\"");
            
            print(">");
        }
        else if ($arrayElt == -3)
        {
            print("<td style=\"color:".$proven['3']."\"");
            if ($align)
                print(" align=\"right\"");
            else
                print("\"");
            
            print(">");
        }
        else if ($arrayElt == -4)
        {
            print("<td style=\"color:".$proven['4']."\"");
            if ($align)
                print(" align=\"right\"");
            else
                print("\"");
            
            print(">");
        }
        else if ($arrayElt == -5) // last unbounded_proven case
        {
            print("<td style=\"color:".$proven['5']."\"");
            if ($align)
                print(" align=\"right\"");
            
            print(">");
        }
        else if ($arrayElt == $unboundedUnproven)
        {
            print("<td style=\"color:".$unproven."\"");
            if ($align)
                print(" align=\"right\"");
            
            print(">");
        }
        else if ($arrayElt == $oddNegative)
        {
            print("<td style=\"color:".$oddNegativeColor."\"");
            if ($align)
                print(" align=\"right\"");
            
            print(">");
        }
        else if ($arrayElt == $evenNegative)
        {
            print("<td style=\"color:".$evenNegativeColor."\"");
            if ($align)
                print(" align=\"right\"");
            
            print(">");
        }
        else if ($arrayElt == 0)
        {
            print("<td style=\"color:".$stabilityColor);
            if ($align)
                print(" align=\"right\"");
            
            print(">");
        }
        else if ($align)
            print("<td align=\"right\">");
        else
            print("<td>");
    }
    else
    {
        if (
            $arrayElt == "-1" ||
            $arrayElt == "-2"
        )
        {
            print("<td style=\"background-color:".$overflowColor."; color:black\"");
            if ($align)
                print(" align=\"right\"");
            print(">");
        }
        else if ($arrayElt == -3)
        {
            print("<td style=\"background-color:".$proven['3']."; color:black\"");
            if ($align)
                print(" align=\"right\"");
            print(">");
        }
        else if ($arrayElt == -4)
        {
            print("<td style=\"background-color:".$proven['4']."; color:black\"");
            if ($align)
                print(" align=\"right\"");
            print(">");
        }
        else if ($arrayElt == -5)
        {
            print("<td style=\"background-color:".$proven['5']."; color:black\"");
            if ($align)
                print(" align=\"right\"");
            print(">");
        }
        else if ($arrayElt == $unboundedUnproven)
        {
            print("<td style=\"background-color:".$unproven."; color:black\"");
            if ($align)
                print(" align=\"right\"");
            print(">");
        }
        else if ($arrayElt == $oddNegative)
        {
            print("<td style=\"background-color:".$oddNegativeColor."; color:black\"");
            if ($align)
                print(" align=\"right\"");
            print(">");
        }
        else if ($arrayElt == $evenNegative)
        {
            print("<td style=\"background-color:".$evenNegativeColor."; color:black\"");
            if ($align)
                print(" align=\"right\"");
            print(">");
        }
        else if ($arrayElt == 0)
        {
            print("<td style=\"background-color:".$stabilityColor."; color:black\"");
            if ($align)
                print(" align=\"right\"");
            print(">");
        }
        else if ($align)
            print("<td align=\"right\">");
        else
            print("<td>");
    }
    print($arrayElt);
    print("</td>");
}

function separatedPairExists($x, $s)
{
    $found = False;
    f($x);
    $i = 0;
    while (!$found && $i < sizeof($chain))
    {
        if (
            $chain[$i] > 2 && 
            (array_search($chain[$i] - $s, $chain) !== False
            || 
            array_search($chain[$i] + $s, $chain) !== False)
        )
            $found = True;

        $i++;
    }
    return $found;
}

// is getLoop(x) = getLoop(1) for x = 1,...,2000
function stabilizes($oddCoefficient, $oddAddend, $evenDivisor) : bool|int
{
    $instabilityFound = False;
    $loop1 = getLoop(1, $oddCoefficient, $oddAddend, $evenDivisor);
    if (sizeof($loop1) > 1) // if (a, b, c)(1) not unbounded
    {
        $x = 2;
        while (!$instabilityFound && $x < 2000)
        {
            $curLoop = getLoop($x, $oddCoefficient, $oddAddend, $evenDivisor);

            // check if there's anything in the current loop that isn't in the first loop
            // if curLoop unbounded, array_diff will contain a negative number
            if (sizeof(array_diff($curLoop, $loop1)) != 0)
            {
                $instabilityFound = True;
                $instability = $x;
            }
            $x++;
        }
    }
    else
    {
        $instabilityFound = True;
        $instability = 1;
    }

    if (!$instabilityFound)
        return True;
    else
        return $instability;
}

// FIX: unbounded_unproven_50-50-50.php is too long!
include("C:/xampp/htdocs/cc/unbounded_unproven_50.php");

function dump_cc()
{
    $fileName = "insert_cc.sql";
    $handle = fopen($fileName, "w");

    fputs($handle, "USE cc;\n\n");

    fputs($handle, "SET AUTOCOMMIT=0;\n");
    fputs($handle, "INSERT INTO cc.function VALUES\n");

    $mysqli = new mysqli("localhost", "root", "", "cc");
    if ($mysqli->connect_error)
        exit('Error connecting to database');
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
    $mysqli->set_charset("utf8mb4");

    $functionCountStmt = $mysqli->prepare("SELECT COUNT(*) AS numFunctions FROM `function` f");
    $functionCountStmt->execute();
    $result = $functionCountStmt->get_result();
    $row = $result->fetch_assoc();
    $numFunctions = $row['numFunctions'];

    $functionStmt = $mysqli->prepare("SELECT * FROM `function` f
    ORDER BY f.odd_coefficient, f.odd_addend, f.even_divisor");
    $evalStmt->execute();
    $result = $evalStmt->get_result();
    $i = 0;
    while ($row = $result->fetch_assoc())
    {
        fputs($handle, "('".$row['odd_coefficient'].", ".$row['odd_addend'].", ".$row['even_divisor'].", ".$row['stabilizes'].")");
        if ($i < $numEvals - 1)
            fputs($handle, ",\n");
        else
            fputs($handle, ";\n");
        $i++;
    }
    $functionStmt->close();
    fputs($handle, "COMMIT;\n\n");

    $evalCountStmt = $mysqli->prepare("SELECT COUNT(*) AS numEvals FROM `eval` e");
    $evalCountStmt->execute();
    $result = $evalCountStmt->get_result();
    $row = $result->fetch_assoc();
    $numEvals = $row['numEvals'];

    fputs($handle, "SET AUTOCOMMIT=0;\n");
    fputs($handle, "INSERT INTO cc.evaluation VALUES\n");
    $evalStmt = $mysqli->prepare("SELECT * FROM evaluation e
    ORDER BY e.odd_coefficient, e.odd_addend, e.even_divisor, e.value");
    $evalStmt->execute();
    $result = $evalStmt->get_result();
    $i = 0;
    while ($row = $result->fetch_assoc())
    {
        fputs($handle, "('".$row['odd_coefficient'].", ".$row['odd_addend'].", ".$row['even_divisor'].", ".$row['value'].", ".$row['chain'].", ".$row['prime_chain'].", ".$row['chain_length'].", ".$row['loop'].", ".$row['prime_loop'].", ".$row['loop_length'].", ".$row['cl_ratio'].", ".$row['stopping_time'].", ".$row['total_stopping_time'].")");
        if ($i < $numEvals - 1)
            fputs($handle, ",\n");
        else
            fputs($handle, ";\n");
        $i++;
    }
    $evalStmt->close();
    fputs($handle, "COMMIT;\n\n");
    fclose($handle);
}

function writev($v)
{
    for ($i = 0; $i < sizeof($v); $i++)
    {
        print($v[$i]);
        if ($i < sizeof($v) - 1)
            print(", ");
    }
    print("\n");
}

/* FIX: screws up the file when create_cc runs twice 
FIX:
Warning: Undefined variable $insertIndex in C:\xampp\htdocs\cc\cc_functions.php on line 1425
insertIndex, line 0
listEndIndex, line 0

Warning: Undefined variable $listEndIndex in C:\xampp\htdocs\cc\cc_functions.php on line 1342
Warning: Undefined variable $listEndIndex in C:\xampp\htdocs\cc\cc_functions.php on line 1377
*/
function write_unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor)
{
    $fileName = "ANEWFILE.php";
    if (!file_exists($fileName))
    {
        $handle = fopen($fileName, "w");
            fputs($handle, "<?php\n");
            fputs($handle, "    function unbounded_unproven(\$x, \$oddCoefficient, \$oddAddend, \$evenDivisor)\n");
            fputs($handle, "    {\n");
            fputs($handle, "        if (\n");

            fputs($handle, "        )\n");
            fputs($handle, "            return True;\n");
            fputs($handle, "        return False;\n");
            fputs($handle, "    }\n");
            fputs($handle, "?>");
        fclose($handle);
    }
    
    $handle = fopen($fileName, "r");
    $i = 0;
    $functionFound = False;
    $listBeginFound = False;
    $valueFound = False;
    $saveValues = False;
    $fileArray = array();
    $functionNum = 0;
    while (!$valueFound && !feof($handle))
    {
        // $line = str_replace(array("\n", "\r"), "", fgets($handle));
        $line = fgets($handle);
        $lines[] = $line;

        if (trim($line) == "return True;" && !$functionFound && empty($functionIndex))
            $returnTrueIndex = $i;

        // if a function is found
        $trimmedLine = str_replace(array("\n", "\r", " "), "", $line);
        if (substr($trimmedLine, 0, 3) == "//(")
        {
            // set $curFunction
            $curFunction = $trimmedLine[3];
            $m = 1;
            while ($trimmedLine[3 + $m] != ')')
            {
                $curFunction = $curFunction.$trimmedLine[3 + $m];
                $m++;
            }
            $curFunctionDebris = explode(",", $curFunction);

            // check if key function already there
            if ($trimmedLine == "//(".$oddCoefficient.",".$oddAddend.",".$evenDivisor.")" && !$functionFound)
            {
                $functionFound = True;
                $functionIndex = $i;
                $listBeginFound = False;
                $fileArrayIndex = $functionNum;
            }

            // Compare the input to each function, determining before or after
            // At the first "before," take the index
            if (!$functionFound && empty($functionIndex))
            {
                /* 
                curFunction: (a, b, c)
                oc < before    [1, -, -] < (9, -, -)
                oc > after  [9, -, -] > (1, -, -)
                oc = -> check oa
                    -> oa < before  [1, 2, -] < (1, 9, -)
                    -> oa > after   [1, 9, -] > (1, 2, -)
                    oa = -> check ed
                        -> ed < before  [1, 2, 2] < (1, 2, 3)
                        -> ed > after   [1, 2, 3] > (1, 2, 2)
                        -> ed = [[shouldn't happen]]
                */

                if ($oddCoefficient < $curFunctionDebris[0])
                    $functionIndex = $i;
                else if ($oddCoefficient > $curFunctionDebris[0])
                    1 == 1;
                else if ($oddCoefficient == $curFunctionDebris[0])
                {
                    if ($oddAddend < $curFunctionDebris[1])
                        $functionIndex = $i;
                    else if ($oddAddend > $curFunctionDebris[1])
                        1 == 1;
                    else if ($oddAddend == $curFunctionDebris[1])
                    {
                        if ($oddCoefficient < $curFunctionDebris[2])
                            $functionIndex = $i;
                        else if ($oddCoefficient > $curFunctionDebris[2])
                        1 == 1;
                        else if ($oddCoefficient == $curFunctionDebris[2])
                        {
                            print("ERROR: Equivalent functions encountered</br>");
                        }
                    }
                }
            }

            // save line number and function name
            $fileArray[] = array();
            $functionLine = $i;
            $fileArray[sizeof($fileArray) - 1][] = $i;
            $fileArray[sizeof($fileArray) - 1][] = $curFunction;
            $fileArray[sizeof($fileArray) - 1][] = array();
            $saveValues = True;
            $k = 0;
            $functionNum++;
        }

        // save values
        if ($saveValues && $i >= $functionLine + 3)
        {
            if (substr(trim($lines[$i]), 0, 2) == "\$x")
            {
                $trimmed = trim($lines[$i]);
                $num = $trimmed[6];
                $m = 1;
                while (6 + $m < strlen($trimmed) && ctype_digit($trimmed[6 + $m]))
                {
                    $num = $num.$trimmed[6 + $m];
                    $m++;
                }

                // if key function found and currently saving its x's
                if ($functionFound && $functionLine == $functionIndex)
                {
                    // if key value found
                    if ($num == $x)
                        $valueFound = True;
                }

                $fileArray[sizeof($fileArray) - 1][2][] = $num;
                $k++;
            }
            else // done saving x's until next function
                $saveValues = False;
        }

        // only need beginning and end of list if key value isn't there
        if (!$valueFound)
        {
            // if key function already there, get index of first line of the list of x's
            if ($functionFound && !$listBeginFound)
            {
                if (trim($line) == "&& (")
                {
                    $listBeginFound = True;
                    $listBeginIndex = $i;
                    $listEndFound = False;
                }
            }

            // if ley function already there, get index of last line of the list of x's
            if ($functionFound && $listBeginFound && !$listEndFound)
            {
                if (trim($line) == ")")
                {
                    $listEndFound = True;
                    $listEndIndex = $i;
                }
            }
        }
        $i++;
    }
    fclose($handle);

    // if key value already exists, don't need to insert, don't need to rewrite file
    if (!$valueFound)
    {
        // get line to insert in list of x's
        if ($functionFound)
        {
            if (sizeof($fileArray[$fileArrayIndex][2]) > 0)
            {
                $placeFound = False;
                $j = $listBeginIndex + 1;
                while (!$placeFound && $j <= $listEndIndex - 1)
                {
                    // set num and prevNum
                    if ($j == $listBeginIndex + 1)
                    {
                        $num = $lines[$j][18];
                        $k = 1;
                        while(ctype_digit($lines[$j][18 + $k]))
                        {
                            $num = $num.$lines[$j][18 + $k];
                            $k++;
                        }
                    }

                    // at the first "before," take the index
                    if ($x < $num)
                    {
                        $placeFound = True;
                        $insertIndex = $j - 1;
                    }

                    if ($j < $listEndIndex - 1)
                    {
                        $prevNum = $num;
                        $num = $lines[$j + 1][18];
                        $k = 1;
                        while(ctype_digit($lines[$j + 1][18 + $k]))
                        {
                            $num = $num.$lines[$j + 1][18 + $k];
                            $k++;
                        }
                    }
                    $j++;
                }
                if (!$placeFound)
                    $insertIndex = $listEndIndex - 1;
            }
            else
                $insertIndex = $listEndIndex - 1;
        }
    }

    $handle = fopen($fileName, "w");
    if (!$functionFound)
    {
        for ($i = 0; $i < sizeof($lines); $i++)
        {
            if (!empty($functionIndex) && $i == $functionIndex)
            {
                fputs($handle, "        // (".$oddCoefficient.", ".$oddAddend.", ".$evenDivisor.")\n");
                fputs($handle, "        ");
                if (sizeof($fileArray) > 0 && $functionIndex != $fileArray[0][0])
                    fputs($handle, "|| ");
                fputs($handle, "(\$oddCoefficient == ".$oddCoefficient." && \$oddAddend == ".$oddAddend." && \$evenDivisor == ".$evenDivisor."\n");
                // FIX: figure out whether it goes to infinity for all x's or not
                // For now, just list all x's, it only lists the ones that are entered (Maple?)
                fputs($handle, "        && (\n");
                fputs($handle, "            \$x == ".$x."\n");
                fputs($handle, "        )\n");
                fputs($handle, "        )\n");
            }
            // else if inserting at the end
            else if (empty($functionIndex) && !empty($returnTrueIndex) && $i == $returnTrueIndex - 1)
            {
                fputs($handle, "        // (".$oddCoefficient.", ".$oddAddend.", ".$evenDivisor.")\n");
                fputs($handle, "        ");
                if (sizeof($fileArray) > 0)
                    fputs($handle, "|| ");
                fputs($handle, "(\$oddCoefficient == ".$oddCoefficient." && \$oddAddend == ".$oddAddend." && \$evenDivisor == ".$evenDivisor."\n");
                // FIX: figure out whether it goes to infinity for all x's or not
                // For now, just list all x's, it only lists the ones that are entered (Maple?)
                fputs($handle, "        && (\n");
                fputs($handle, "            \$x == ".$x."\n");
                fputs($handle, "        )\n");
                fputs($handle, "        )\n");
            }
            fputs($handle, $lines[$i]);
        }
    }
    else // function found
    {
        for ($i = 0; $i < sizeof($lines); $i++)
        {
            if ($i == $insertIndex)
            {
                if (!empty($insertIndex) && !empty($listEndIndex))
                {
                    if ($insertIndex == $listEndIndex - 1)
                    {
                        fputs($handle, str_replace(array("\n", "\r"), "", $lines[$i]));
                        if (sizeof($fileArray[$fileArrayIndex][2]) > 0)
                            fputs($handle, " ||\n");
                        else
                            fputs($handle, "\n");
                        fputs($handle, "            \$x == ".$x."\n");
                    }
                    else
                    {
                        fputs($handle, $lines[$i]);
                        fputs($handle, "            \$x == ".$x." ||\n");
                    }
                }
                else
                {
                    if (empty($insertIndex))
                        print("insertIndex, line ".$i."</br>");
                    if (empty($listEndIndex))
                        print("listEndIndex, line ".$i."</br>");
                }
            }
            else
                fputs($handle, $lines[$i]);
        }
    }
}
?>