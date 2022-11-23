<!DOCTYPE html>
<html>
    <head>
        <title>Search DB</title>
        <link rel="stylesheet" type="text/css" href="../cc.css"/>
    </head>
    <body style="background:black; color:white">
        <a href="search.html"><button>Make Another Search</button></a>
        <a href="index.html"><button>Back to Main Page</button></a>
        </br>
        <?php
                $overflowColor = "red";
                $proven['3'] = "yellow";
                $proven['4'] = "darkorange";
                $proven['5'] = "#b36200";
                $unproven = "blue";
                $oddNegativeColor = "lawngreen";
                $evenNegativeColor = "green";
                $stabilizesColor = "aqua";
                print("
                <p>
                <table>
                    <tr>
                        <td><font color=\"".$overflowColor."\">Red</font></td>
                        <td>Overflow</td>
                    </tr>
                    <tr>
                        <td>
                            <font color=\"".$proven['3']."\">Yellow</font>/<font color=\"".$proven['4']."\">Orange</font>/<font color=\"".$proven['5']."\">Dark Orange</font>
                        </td>
                        <td>Proved Unbounded Sequence</td>
                    </tr>
                    <tr>
                        <td><font color=\"".$unproven."\">Blue</font></td>
                        <td>Unproved Unbounded Sequence</td>
                    </tr>
                    <tr>
                        <td><font color=\"".$oddNegativeColor."\">Lawn Green</font></td>
                        <td>Odd, No Stop/Instability</td>
                    </tr>
                    <tr>
                        <td><font color=\"".$evenNegativeColor."\">Green</font></td>
                        <td>Even, No Stop/Instability</td>
                    </tr>
                </table>
                </p>
                ");
                include("cc_functions.php");
                include("connection_cc.php");

                $color = $_POST['color'];
                $provenUnbounded = $_POST['provenUnbounded'];
                $unprovenUnbounded = $_POST['unprovenUnbounded'];
                if ($_POST['primeSequences'] == "yes")
                    $primeSequences = True;
                else
                    $primeSequences = False;
                $primesOrComposites = $_POST['primesOrComposites'];
                $oddCoefficient = $_POST['a'];
                $aMin = $_POST['aMin'];
                $aMax = $_POST['aMax'];
                $oddAddend = $_POST['b'];
                $bMin = $_POST['bMin'];
                $bMax = $_POST['bMax'];
                $evenDivisor = $_POST['c'];
                $cMin = $_POST['cMin'];
                $cMax = $_POST['cMax'];
                $x = $_POST['x'];
                $xMin = $_POST['xMin'];
                $xMax = $_POST['xMax'];
                $stabilizes = $_POST['stabilizes'];
                $clRatio = $_POST['clRatio'];
                $clRatioMin = $_POST['clRatioMin'];
                $clRatioMax = $_POST['clRatioMax'];
                $chainLength = $_POST['chainLength'];
                $chainLengthMin = $_POST['chainLengthMin'];
                $chainLengthMax = $_POST['chainLengthMax'];
                $subChain = $_POST['subChain'];
                $subChainConsecutive = $_POST['subChainConsecutive'];
                if ($subChainConsecutive == "no");
                    $subChainDebris = explode(", ", $subChain);
                $loopLength = $_POST['loopLength'];
                $loopLengthMin = $_POST['loopLengthMin'];
                $loopLengthMax = $_POST['loopLengthMax'];
                $loopSign = $_POST['loopSign'];
                $subLoop = $_POST['subLoop'];
                $subLoopConsecutive = $_POST['subLoopConsecutive'];
                if ($subLoopConsecutive)
                    $subLoopDebris = explode(", ", $subLoop);
                
                $stoppingTime = $_POST['stoppingTime'];
                $stoppingTimeMin = $_POST['stoppingTimeMin'];
                $stoppingTimeMax = $_POST['stoppingTimeMax'];
                $stoppingTimeSign = $_POST['stoppingTimeSign'];

                $totalStoppingTIme = $_POST['totalStoppingTime'];
                $totalStoppingTimeMin = $_POST['totalStoppingTimeMin'];
                $totalStoppingTimeMax = $_POST['totalStoppingTimeMax'];
                $totalStoppingTimeSign = $_POST['totalStoppingTimeSign'];

                // dynamic query
                $query = "SELECT * FROM `function` f, evaluation e
                WHERE f.odd_coefficient = e.odd_coefficient
                AND f.odd_addend = e.odd_addend
                AND f.even_divisor = e.even_divisor";
                
                if (!empty($provenUnbounded) && $provenUnbounded == "no")
                {
                    if ($oddCoefficient == 6 && $oddAddend % 6 != 0 && $evenDivisor == 2)
                    {
                        $addend = $oddAddend;
                        $sixk = 0;
                        while ($addend > 6)
                        {
                            $addend = $addend - 6;
                            $sixk = $sixk + 6;
                        }
                    }

                    $query = $query." AND 
                    (-- // ex + o = o, ox + e = ...o
                        (e.odd_coefficient % 2 = 1 OR e.odd_addend % 2 = 0)
                        AND 
                        (e.odd_coefficient % 2 = 0 OR e.odd_addend % 2 = 1)
                    )
                    AND 
                    (-- (e, eh, 2), (eh, e, 2), h % 2 = 0
                        (
                            e.odd_coefficient % 2 = 1
                            OR e.odd_addend % e.odd_coefficient != 0
                            OR (e.odd_addend / e.odd_coefficient) % 2 = 1
                            OR e.even_divisor != 2
                        )
                        AND
                        (
                            e.odd_coefficient % e.odd_addend != 0
                            OR e.odd_addend % 2 = 1
                            OR (e.odd_coefficient / e.odd_addend) % 2 = 1
                            OR e.even_divisor != 2
                        )
                    )
                    AND
                    (-- (4, 4k + 2, 2), (4k + 2, 4, 2)
                        (
                            e.odd_coefficient != 4
                            OR (e.odd_addend - 2) % 4 != 0
                            OR e.even_divisor != 2
                        )
                        AND
                        (
                            (e.odd_coefficient - 2) % 4 != 0
                            OR e.odd_addend != 4
                            OR e.even_divisor
                        )
                    )";
                    if (!empty($sixk) && !empty($addend))
                    {
                        $query = $query."
                        AND 
                        (-- (6, 6k + 2^(((k - 1) % 2) + 1), 2), k = 1, 2,...
                            (e.odd_coefficient != 6 && 
                            (
                                e.odd_addend != ".$sixk." + ".$addend." OR 
                                e.odd_addend % 6 == 0 OR 
                                ".$sixk." % 6 != 0 OR 
                                (".$addend." != 2 AND ".$addend." != 4)
                            ) OR 
                            e.even_divisor != 2
                            )
                        )";
                    }
                }

                // FIX: only fetches 76 rows of 1000 when unproven unbounded sequences not included? 
                $unprovenUnboundedVal = -6;
                if (!empty($unprovenUnbounded) && $unprovenUnbounded == "no")
                {
                    $query = $query." AND 
                        e.chain NOT LIKE '%".$unprovenUnboundedVal."' AND
                        e.loop NOT LIKE '%".$unprovenUnboundedVal."' AND
                        e.stopping_time != ".$unprovenUnboundedVal." AND 
                        e.total_stopping_time != ".$unprovenUnboundedVal." AND 
                        e.stabilization_time != ".$unprovenUnboundedVal;
                }

                if ($primesOrComposites == "primes")
                    $query = $query." AND e.prime_chain NOT LIKE '%0%'
                    AND e.chain NOT LIKE '-%'";
                else if ($primesOrComposites == "composites")
                    $query = $query." AND e.prime_chain NOT LIKE '%1%'
                    AND e.chain NOT LIKE '-%'";

                if (!empty($oddCoefficient))
                    $query = $query." AND e.odd_coefficient = ".$oddCoefficient;
                if (!empty($aMin))
                    $query = $query." AND e.odd_coefficient >= ".$aMin;
                if (!empty($aMax))
                    $query = $query." AND e.odd_coefficient <= ".$aMax;

                if (!empty($oddAddend))
                    $query = $query." AND e.odd_addend = ".$oddAddend;
                if (!empty($bMin))
                    $query = $query." AND e.odd_addend >= ".$bMin;
                if (!empty($bMax))
                    $query = $query." AND e.odd_addend <= ".$bMax;

                if (!empty($evenDivisor))
                    $query = $query." AND e.even_divisor = ".$evenDivisor;
                if (!empty($cMin))
                    $query = $query." AND e.even_divisor >= ".$cMin;
                if (!empty($cMax))
                    $query = $query." AND e.even_divisor <= ".$cMax;
                
                if (!empty($x))
                    $query = $query." AND e.value = ".$x;
                if (!empty($xMin))
                    $query = $query." AND e.value >= ".$xMin;
                if (!empty($xMax))
                    $query = $query." AND e.value <= ".$xMax;

                if ($stabilizes == "yes")
                    $query = $query." AND f.stabilizes = 1";
                else if ($stabilizes == "no")
                    $query = $query." AND f.stabilizes = 0";

                if (!empty($clRatio))
                    $query = $query." AND e.cl_ratio = ".$clRatio;

                if (!empty($clRatioMin))
                    $query = $query." AND e.cl_ratio >= ".$clRatioMin;
                
                if (!empty($clRatioMax))
                    $query = $query." AND e.cl_ratio >= ".$clRatioMax;

                if (!empty($chainLength))
                    $query = $query." AND c.length = '".$chainLength."'";

                if (!empty($chainLengthMin))
                    $query = $query." AND c.length >= ".$chainLengthMin;
                if (!empty($chainLengthMin) && !empty($chainLengthMax))
                    $query = $query." AND c.length <= ".$chainLengthMax;
                
                if (!empty($subChain))
                {
                    if ($subChainConsecutive == "yes")
                    {
                        if (!empty($subChain))
                            $query = $query." AND e.chain LIKE '%".$subChain."%'";
                    }
                    else
                    {
                        for ($i = 0; $i < sizeof($subChainDebris); $i++)
                        {
                            $query = $query." AND (
                                e.chain = '".$subChainDebris[$i]."' OR
                                e.chain LIKE '".$subChainDebris[$i].", %' OR
                                e.chain LIKE '%, ".$subChainDebris[$i].", %' OR
                                e.chain LIKE '%, ".$subChainDebris[$i]."'
                            )";
                        }
                    }
                }

                if (!empty($loopLength))
                    $query = $query." AND l.length = '".$loopLength."'";

                if (!empty($loopLengthMin))
                    $query = $query." AND l.length >= ".$loopLengthMin;
                if (!empty($loopLengthMin) && !empty($loopLengthMax))
                    $query = $query." AND l.length <= ".$loopLengthMax;

                if (!empty($subLoop))
                {
                    if ($subLoopConsecutive == "yes")
                    {
                        if (!empty($subLoop))
                            $query = $query." AND e.loop LIKE '%".$subLoop."%'";
                    }
                    else
                    {
                        for ($i = 0; $i < sizeof($subLoopDebris); $i++)
                        {
                            $query = $query." AND (
                                e.loop = '".$subLoopDebris[$i]."' OR
                                e.loop LIKE '".$subLoopDebris[$i].", %' OR
                                e.loop LIKE '%, ".$subLoopDebris[$i].", %' OR
                                e.loop LIKE '%, ".$subLoopDebris[$i]."'
                            )";
                        }
                    }
                }

                if ($loopSign == "negative")
                    $query = $query." AND e.loop IS LIKE '%-%'";
                else if ($loopSign == "positive")
                    $query = $query." AND e.loop IS NOT LIKE '%-%'";
                
                if (!empty($stoppingTime))
                    $query = $query." AND e.stopping_time = ".$stoppingTime;
                if (!empty($stoppingTimeMin))
                    $query = $query." AND e.stopping_time >= ".$stoppingTimeMin;
                if (!empty($stoppingTimeMax))
                    $query = $query." AND e.stopping_time <= ".$stoppingTimeMax;
                if ($stoppingTimeSign == "negative")
                    $query = $query." AND e.stopping_time < 0";
                else if ($stoppingTimeSign == "positive")
                    $query = $query." AND e.stopping_time > 0";

                if (!empty($totalStoppingTime))
                    $query = $query." AND e.total_stopping_time = ".$totalStoppingTime;
                if (!empty($totalStoppingTimeMin))
                    $query = $query." AND e.total_stopping_time >= ".$totalStoppingTimeMin;
                if (!empty($totalStoppingTimeMax))
                    $query = $query." AND e.total_stopping_time <= ".$totalStoppingTimeMax;
                if ($totalStoppingTimeSign == "negative")
                    $query = $query." AND e.total_stopping_time < 0";
                else if ($totalStoppingTimeSign == "positive")
                    $query = $query." AND e.total_stopping_time > 0";

                $query = $query." ORDER BY e.odd_coefficient, e.odd_addend, e.even_divisor, e.value";

                $mysqli = new mysqli($server, $user, $pw, $db);
                if ($mysqli->connect_error)
                    exit('Error connecting to database');
                mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
                $mysqli->set_charset("utf8mb4");

                $stmt = $mysqli->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows === 0)
                    exit('Error: no rows');
                
                print("<p>Rows Fetched: ".$result->num_rows."</p>");

                print("<table style=\"border:1px solid white\">");
                    print("<tr>");
                        print("<th>Evaluation</th>");
                        print("<th>Chain</th>");
                        // if ($primeSequences)
                        //     print("<th>Prime Chain</th>");
                        print("<th>Loop</th>");
                        // if ($primeSequences)
                        //     print("<th>Prime Loop</th>");
                        print("<th>CL-ratio</th>");
                        print("<th>Stopping Time</th>");
                        print("<th>Total Stopping Time</th>");
                    print("</tr>");
                
                // style=\"white-space: nowrap; overflow: hidden; text-overflow:ellipsis\"
                while ($row = $result->fetch_assoc())
                {
                    print("<tr>");

                    if ($color == "text")
                    {
                        if ($row['stabilizes'] == 1)
                            print("<td style=\"color:".$stabilizesColor."\">");
                        else
                            print("<td>");
                    }
                    else
                    {
                        if ($row['stabilizes'] == 1)
                            print("<td style=\"background-color:".$stabilizesColor."; color:black\">");
                        else
                            print("<td>");
                    }
                    print("(".
                    $row['odd_coefficient'].", ".
                    $row['odd_addend'].", ".
                    $row['even_divisor'].")(".
                    $row['value']
                    .")</td>");

                    if ($row['chain'][0] == "-")
                        print_color($row['chain'], $color);
                    else
                    {
                        if ($primeSequences)
                            print_color(colorPrimes($row['chain']), $color);
                        else
                            print_color($row['chain'], $color);
                    }

                    if ($row['loop'][0] == "-")
                        print_color($row['loop'], $color);
                    else
                    {
                        if ($primeSequences)
                            print_color(colorPrimes($row['loop']), $color);
                        else
                            print_color($row['loop'], $color);
                    }
                    print("<td>".$row['cl_ratio']."</td>");
                    print_color($row['stopping_time'], $color, True);
                    print_color($row['total_stopping_time'], $color, True);
                    print("</tr>");
                }
                print("</table>");
            ?>
        <a href="search.html"><button>Make Another Search</button></a>
        <a href="index.html"><button>Back to Main Page</button></a>
    </body>
</html>