<!DOCTYPE html>
<html>
<head>
    <title>Add Evaluations</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <link rel="stylesheet" type="text/css" href="cc.css"/>
</head>
<body>
<?php
include("cc_functions.php");
include("connection_cc.php");

$a = $_POST['a'];
$aMin = $_POST['aMin'];
$aMax = $_POST['aMax'];
$b = $_POST['b'];
$bMin = $_POST['bMin'];
$bMax = $_POST['bMax'];
$x = $_POST['x'];
$xMin = $_POST['xMin'];
$xMax = $_POST['xMax'];

if (empty($aMin))
{
    $aMin = $a;
    $aMax = $a;
}
if (empty($bMin))
{
    $bMin = $b;
    $bMax = $b;
}
if (empty($xMin))
{
    $xMin = $x;
    $xMax = $x;
}

$inputValid = True;
/*
if (empty($a) && empty($aMin) && empty($aMax))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Either a fixed value for \(a\) or a range must be given.</br>"); 
    $inputValid = False;
}
if (empty($b) && empty($bMin) && empty($bMax))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Either a fixed value for \(b\) or a range must be given.</br>");
    $inputValid = False;
}
if (empty($x) && empty($xMin) && empty($xMax))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Either a fixed value for \(x\) or a range must be given.</br>");
    $inputValid = False;
}

if (!empty($a) || !empty($b) || !empty($x) || !empty($aMin) || !empty($aMax) || !empty($bMin) || !empty($bMax) || !empty($xMin) || !empty($xMax))
{
    if ($a <= 0 || $b <= 0 || $x <= 0 || $aMin <= 0 || $aMax <= 0 || $bMin <= 0 || $bMax <= 0 || $xMin <= 0 || $xMax <= 0)
    {
        if ($inputValid)
            print("<h1 style=\"color:red\">Error</h1>");
        print("Values must be nonnegative</br>");
        $inputValid = False;
    }
}

if ((!empty($aMin) && !empty($aMax)) || (!empty($bMin) && !empty($bMax)) || (!empty($xMin) && !empty($xMax)))
{
    if ($aMin > $aMax || $bMin > $bMax || $cMin > $cMax)
    {
        if ($inputValid)
            print("<h1 style=\"color:red\">Error</h1>");
        print("Minimum values must be less than or equal to maximum values</br>");
        $inputValid = False;
    }
}
/**/

if ($inputValid)
{
    // FIX: input validation
    // Fatal error: Uncaught TypeError: Unsupported operand types: string * string in C:\xampp\htdocs\cc\cc_functions.php:396 Stack trace: #0 C:\xampp\htdocs\cc\cc_functions.php(271): isOverflowMultiply('10', '') #1 C:\xampp\htdocs\cc\add_evaluations.php(31): goesToInfinity('', '10', '', '2') #2 {main} thrown in C:\xampp\htdocs\cc\cc_functions.php on line 396

    $mysqli = new mysqli($server, $user, $pw, $db);
    if ($mysqli->connect_error)
        exit('Error connecting to database');
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli->set_charset("utf8mb4");
    $stmt = $mysqli->prepare("SET AUTOCOMMIT=0;");
    $stmt->execute();

    // a, b, x
    // a*, b, x
    // a, b*, x
    // a, b, x*
    // a*, b*, x
    // a*, b, x*
    // a, b*, x*
    // a*, b*, x*
    // values and bounds --> do both
    // if (!empty($oddAddend) && !empty($oddAddend) && !empty($x))
    // if (empty($oddAddend) && !empty($oddAddend) && !empty($x))

    $oneAdded = False;
    $oneNotAdded = False;
    for ($a = $aMin; $a <= $aMax; $a++)
    {
        for ($b = $bMin; $b <= $bMax; $b++)
        {
            for ($x = $xMin; $x <= $xMax; $x++)
            {
                // if (!unbounded_proven($x, $a, $b, $evenDivisor))
                    insert_no_check($mysqli, $x, $a, $b, 2, False);
                // else if ($oneNotAdded == False)
                //     $oneNotAdded = True;
            }
        }
    }

    if (!$oneNotAdded)
        print("<h1>Success</h1>");
    else if ($oneAdded && $oneNotAdded)
        print("Note: Evaluation parameters prevented some values from being entered.</br>");
    else // none added one not added
        print("Error: Evaluation parameters create a sequence that goes to infinity. No evaluations were added.</br>");
}
else
    print("</br>");
?>
<a href="add_evaluations.html"><button>Add More Evaluations</button></a>
<a href="index.html"><button>Back to Main Page</button></a>
<body>
</html>