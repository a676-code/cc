<!DOCTYPE html>
<html>
<head>
    <title>Clear DB</title>
    <link rel="stylesheet" type="text/css" href="cc.css"/>
</head>
<body>
    <?php
    set_time_limit(86400);
    // ini_set('memory_limit', '2048M');
    include("cc_functions.php");
    include("connection_blank.php");

    if ($_POST['sure'] == "yes")
        $clear = True;
    else
        $clear = False;
    
    if ($clear)
    {
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
        $mysqli->close();

        print("<h1>DB Cleared</h1>");
    }
    ?>
    <a href="index.html"><button>Back to Main Page</button></a>
</body>
</html>