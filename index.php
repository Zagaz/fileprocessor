<?php 

class PriceProcessor{
     private $csvFilePath;
     private $profitMargin;
     private $cad;

     // constructor
     public function __construct($csvFilePath, $profitMargin, $cad){
          $this->csvFilePath = $csvFilePath;
          $this->profitMargin = $profitMargin;
          $this->cad = $cad;
     }





     public function processFile(){
          // open the file and make a table with it
          $file = fopen($this->csvFilePath, 'r');
          $table = array();
          while (($line = fgetcsv($file)) !== FALSE) {
               $table[] = $line;
          }
          fclose($file);

          echo "<pre>";
          echo "<table>";
          // print_r($table);
          // get table first item and make a table row
          echo "<tr>";
          foreach($table[0] as $item){
               echo "<th>$item</th>";
          }
          echo "</tr>";
          // get the data from tabel and make an array
          $data = array();
          for($i = 1; $i < count($table); $i++){
               $data[] = $table[$i];
          }
          
          // with each data from $data array make a table row
          $theTable = "";
          foreach($data as $item){
               $sku = isset($item[0]) ? $item[0] : "N/A";
               $cost = isset($item[1]) ? floatval($item[1]) : 0;
               $price = isset($item[2]) ? floatval($item[2]) : 0;
               $qty = isset($item[3]) ? $item[3] : "N/A";

    
               $profitMarginCalc = floatval($price) - floatval($cost);
     
               $totaProfitUSD = number_format( $profitMarginCalc + $cost,2 ,'.','') ;
               // Make a totalProfit  in CAD based on the usd value on totalProfitUSD and the cad value on the constructor
               $totalProfitCAD = number_format($totaProfitUSD * $this->cad,2,'.','');

               if ($sku == "N/A"){
                    continue;
               }

               echo "<tr>";
               echo "<td>$sku</td>";
               echo "<td>$cost</td>";
               echo "<td>$price</td>";
               echo "<td>$qty</td>";

               echo "<td>$profitMarginCalc</td>";
               echo "<td>$totaProfitUSD</td>";
               echo "<td>$totalProfitCAD</td>";
               echo "</tr>";
          }
          echo "</table>";

          echo $theTable;
  
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="style.css">
     <title>Document</title>
     <?php 
          $priceProcessor = new PriceProcessor('sample.csv',0.5,1.35);
          $priceProcessor->processFile();
     ?>
</head>
<body>
     
</body>
</html>

