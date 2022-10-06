<!DOCTYPE html>
<html>
    <head>
        <title>TEST</title>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
        <link rel="stylesheet" type="text/css" href="cc.css"/>
    </head>
    <body>
        <?php
            include("cc_functions.php");
            $a = $_POST['a'];
            $b = $_POST['b'];
            $c = $_POST['c'];
            $x = $_POST['x'];

            $stability = stabilizes(1, 3, 2);
            if ($stability === True)
                print("YES</br></br>");
            else
                print("instability: ".$stability."</br></br>");

            // $instability = instability($a, $b, $c);
            // print("instability: ".$instability."</br>");

            // $data = getEval($instability, $instability, $a, $b, $c);
            // $loop = implode(", ", $data['loop']);
            // print("loop: ".$loop."</br>");
        ?>
        </br>
        <a href="index.html">Back to Main Page</a>
    </body>
</html>