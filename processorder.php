<?php

$post = $_POST;
$tireqty = (int) $post['tireqty'];
$oilqty = (int) $post['oilqty'];
$sparkqty = (int) $post['sparkqty'];
$address = preg_replace('/\t|R/',' ', $post['address']);
$document_root = $_SERVER['DOCUMENT_ROOT'];
// $document_root = getenv('DOCUMENT_ROOT');
$date = date('H:i, jS F Y');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bob's Auto Parts - Order Results</title>
</head>
<body>
    <h1>Bob's Auto Parts</h1>
    <h2>Order Results</h2>
    <?php 
        echo "<p>Order processed at " . date('H:i, jS F Y') . "</p>";
        echo "<p>Your order is as follow: </p>";

        $totalqty = 0;
        $totalamount = 0.00;

        define('TIREPRICE', 100);
        define('OILPRICE', 10);
        define('SPARKPRICE', 4);

        $totalqty = $tireqty + $oilqty + $sparkqty;
        echo "<p>Items ordered : " . $totalqty . "<br />";

        if($totalqty == 0)
        {
            echo "You did not order anything on the previous page!<br />";
        }
        else
        {
            if($tireqty > 0)
            {
                echo htmlspecialchars($tireqty) . ' tires<br />';
            }

            if($oilqty > 0)
            {
                echo htmlspecialchars($oilqty) . ' oils<br />';
            }

            if($sparkqty > 0)
            {
                echo htmlspecialchars($sparkqty) . ' spark plugs<br />';
            }
        }

        $totalamount = $tireqty * TIREPRICE + $oilqty * OILPRICE + $sparkqty * SPARKPRICE;
        
        echo "Subtotal: $" . number_format($totalamount, 2) . "<br />";

        $taxrate = 0.10;
        $totalamount = $totalamount * (1 + $taxrate);
        echo "Total including tax: $" . number_format($totalamount, 2). "</p>";
        echo "<p>Address to ship to is " . htmlspecialchars($address) . "</p>";
    
        $outputstring = $date . "\t" . $tireqty . " tires\t" . $oilqty . " oils \t" . $sparkqty . " spark plugs \t\$" . $totalamount . "\t" . $address . "\n";

        @$fp = fopen("$document_root/ch02/orders.txt", 'ab');

        if(!$fp)
        {
            echo "<p><strong> Your order could not be processed at this time. Please try again later.</strong></p>";
            exit;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $outputstring, strlen($outputstring));
        flock($fp, LOCK_UN);
        fclose($fp);

        echo "<p>Order written.</p>";
    ?>
</body>
</html>