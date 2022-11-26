<!DOCTYPE html>
<html>
    <head>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
        <link rel="stylesheet" type="text/css" href="cc.css"/>
    </head>
    <body>
        <h1>Polynomials</h1>
        <?php
            // include("cc_functions.php");
            // require_once 'Polynomial.php';
            // require_once 'PolynomialOp.php';
            
            // $p1 = new Math_Polynomial("3x + 1");
            // $p2 = new Math_Polynomial("x + 2");

            // $t1 = $p1->getTerm(0);
            // $t2 = $p1->getTerm(1);

            // print($t1->toString()."</br>");
            // print($t2->toString()."</br>");

            // Fatal error: Uncaught TypeError: count(): Argument #1 ($value) must be of type Countable|array, null given in C:\xampp\htdocs\cc\Polynomial.php:288 Stack trace: #0 C:\xampp\htdocs\cc\TEST2.php(24): Math_Polynomial->toString() #1 {main} thrown in C:\xampp\htdocs\cc\Polynomial.php on line 288
            // print($p1->toString());

            // $res = PolynomialOp::add("x + 3", $p);
            // print($res->toString()); // Prints 2x + 5 ( sum of the two )

            require "Polynomial_example.php";
        ?>
        </br>
        <a href="cc.html">Back to Main Page</a>
    </body>
</html>