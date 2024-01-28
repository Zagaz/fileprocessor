<?php
include 'fileprocessor.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

     <title>PHP Exercise – File Processor</title>


</head>

<body>
     <?php
     $priceProcessor = new PriceProcessor('sample.csv', 1.3);
     $priceProcessor->processFile();
     ?>

</body>

</html>