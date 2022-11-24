<!DOCTYPE html>
<html>
<head>
    <title>Write <tt>unbounded_unproven</tt></title>
    <link rel="stylesheet" type="text/css" href="cc.css"/>
</head>
<body>
    <h1>Write <tt>unbounded_unproven</tt></h1>
    <?php
    set_time_limit(36000);
    // ini_set('memory_limit', '2048M');
    include("cc_functions.php");
    include("connection_blank.php");
    $aMax = $_POST['aMax'];
    $bMax = $_POST['bMax'];
    $xMax = $_POST['xMax'];

    $mysqli = new mysqli($server, $user, $pw, "cc");
    if ($mysqli->connect_error)
        exit('Error connecting to database');
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
    $mysqli->set_charset("utf8mb4");
    $stmt = $mysqli->prepare("SET AUTOCOMMIT=0;");
    $stmt->execute();
    $evenDivisor = 2;
    for ($a = 1; $a <= $aMax; $a++)
    {
        for ($b = 1; $b <= $bMax; $b++)
        {
            for ($x = 1; $x <= $xMax; $x++)
            {
                $totalStoppingTime = getTotalStoppingTime($x, $x, $oddCoefficient, $oddAddend, $evenDivisor);
                // if unbounded
                if ($totalStoppingTime < 0 && !unbounded_proven($x, $oddCoefficient, $oddAddend, $evenDivisor))
                    write_unbounded_unproven($x, $oddCoefficient, $oddAddend, $evenDivisor);
            }
        }
    }
    $mysqli->close();
    ?>
    <h1><tt>unbounded_unproven</tt> Written</h1>
    <a href="cc.html"><button>Back to Main Page</button></a>
</body>
</html>