<!DOCTYPE html>
<html>
<head>
    <title>Create DB</title>
    <link rel="stylesheet" type="text/css" href="cc.css"/>
</head>
<body>
    <?php
    $time_start = microtime(true);
    set_time_limit(86400);
    // ini_set('memory_limit', '2048M');
    include("cc_functions.php");
    include("connection_blank.php");
    $aMax = $_POST['aMax'];
    $bMax = $_POST['bMax'];
    $xMax = $_POST['xMax'];
    if ($_POST['unboundedSequences'] == "yes")
        $unboundedSequences = True;
    else
        $unboundedSequences = False;
    
    if ($_POST['unboundedUnproven'] == "yes")
        $unboundedUnproven = True;
    else
        $unboundedUnproven = False;
    $mysqli = new mysqli($server, $user, $pw, $db);
    if ($mysqli->connect_error)
        exit('Error connecting to database');
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
    $mysqli->set_charset("utf8mb4");

    $stmt = $mysqli->prepare("DROP SCHEMA IF EXISTS cc");
    $stmt->execute();
    $stmt->close();
    $stmt = $mysqli->prepare("CREATE SCHEMA cc");
    $stmt->execute();
    $stmt->close();
    
    $stmt = $mysqli->prepare("CREATE TABLE cc.function (
        odd_coefficient INT NOT NULL,
        odd_addend INT NOT NULL,
        even_divisor INT NOT NULL,
        stabilizes TINYINT NOT NULL,
        CONSTRAINT function_pk PRIMARY KEY (odd_coefficient, odd_addend, even_divisor)
    )
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_general_ci;");
    $stmt->execute();
    $stmt->close();
    
    $stmt = $mysqli->prepare("CREATE TABLE cc.evaluation (
        odd_coefficient INT NOT NULL,
        odd_addend INT NOT NULL,
        even_divisor INT NOT NULL,
        `value` INT NOT NULL,
        `chain` varchar(1000) NOT NULL,
        prime_chain varchar(750) NOT NULL,
        chain_length INT NOT NULL,
        `loop` varchar(750) NULL,
        prime_loop varchar(750) NOT NULL,
        loop_length INT NULL,
        cl_ratio DECIMAL(6, 3) NULL,
        stopping_time INT NULL,
        total_stopping_time INT NULL,
        CONSTRAINT evaluation_pk PRIMARY KEY (odd_coefficient, odd_addend, even_divisor, `value`),
        CONSTRAINT evaluation_FK FOREIGN KEY (odd_coefficient, odd_addend, even_divisor) REFERENCES cc.function(odd_coefficient, odd_addend, even_divisor) ON DELETE CASCADE ON UPDATE CASCADE
    )
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_general_ci;");
    $stmt->execute();
    $stmt->close();

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
                insert_all($mysqli, $x, $a, $b, $evenDivisor, $unboundedSequences, $unboundedUnproven);
        }
    }
    $mysqli->close();
    $time_end = microtime(true);
    print("<h1>DB Created</h1>");
    $execution_time = ($time_end - $time_start) / 60;
    print("Execution time: ".$execution_time." minutes</br>");
    ?>
    <a href="index.html"><button>Back to Main Page</button></a>
</body>
</html>