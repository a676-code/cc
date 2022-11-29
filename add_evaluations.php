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

if ((empty($a) || $a == '') && ($aMin == '' || $aMax == ''))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Either a nonnegative value for \(a\) or a range must be given.</br>"); 
    $inputValid = False;
}
if ((empty($b) || $b == '') && ($bMin == '' || $bMax == ''))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Either a nonnegative value for \(b\) or a range must be given.</br>");
    $inputValid = False;
}
if ((empty($x) || $x == '') && ($xMin == '' || $xMax == ''))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Either a nonnegative value for \(x\) or a range must be given.</br>");
    $inputValid = False;
}

if ((!empty($a) && $a < 0) || (!empty($aMin) && $aMin < 0) || (!empty($aMax) && $aMax < 0))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Values for \(a\) must be nonnegative</br>");
    $inputValid = False;
}

if ((!empty($b) && $b < 0) || (!empty($bMin) && $bMin < 0) || (!empty($bMax) && $bMax < 0))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Values for \(b\) must be nonnegative</br>");
    $inputValid = False;
}

if ((!empty($x) && $x < 0) || (!empty($xMin) && $xMin < 0) || (!empty($xMax) && $xMax < 0))
{
    if ($inputValid)
        print("<h1 style=\"color:red\">Error</h1>");
    print("Values for \(x\) must be nonnegative</br>");
    $inputValid = False;
}

if (!empty($aMin) && !empty($aMax))
{
    if ($aMin > $aMax)
    {
        if ($inputValid)
            print("<h1 style=\"color:red\">Error</h1>");
        print("The minimum value for \(a\) must be less than or equal to maximum value for \(a\).</br>");
        $inputValid = False;
    }
}

if (!empty($bMin) && !empty($bMax))
{
    if ($bMin > $bMax)
    {
        if ($inputValid)
            print("<h1 style=\"color:red\">Error</h1>");
        print("The minimum value for \(b\) must be less than or equal to maximum value for \(b\).</br>");
        $inputValid = False;
    }
}

if (!empty($xMin) && !empty($xMax))
{
    if ($xMin > $xMax)
    {
        if ($inputValid)
            print("<h1 style=\"color:red\">Error</h1>");
        print("The minimum value for \(x\) must be less than or equal to maximum value for \(x\).</br>");
        $inputValid = False;
    }
}

if ($inputValid)
{
    $mysqli = new mysqli($server, $user, $pw, $db);
    if ($mysqli->connect_error)
        exit('Error connecting to database');
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli->set_charset("utf8mb4");
    $stmt = $mysqli->prepare("SET AUTOCOMMIT=0;");
    $stmt->execute();

    for ($a = $aMin; $a <= $aMax; $a++)
    {
        for ($b = $bMin; $b <= $bMax; $b++)
        {
            for ($x = $xMin; $x <= $xMax; $x++)
            {
                insert_all($mysqli, $x, $a, $b, $evenDivisor, $primeSequences, $unboundedSequences, $unboundedUnproven, $stabilityMax);
            }
        }
    }
}
else
    print("</br>");
?>
<a href="add_evaluations.html"><button>Add More Evaluations</button></a>
<a href="index.html"><button>Back to Main Page</button></a>
<body>
</html>