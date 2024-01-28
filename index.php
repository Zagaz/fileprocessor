<?php 

class PriceProcessor{
     private $csvFilePath;

     private $cad;

     // constructor
     public function __construct($csvFilePath,  $cad){
          $this->csvFilePath = $csvFilePath;

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

          echo "<table class =  'table table-striped'>";
          // print_r($table);
          // get table first item and make a table row
          echo '<thead>';
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
          echo '</thead>';
          
          // with each data from $data array make a table row
          
          $theTable = "<tbody class ='table-striped' >";

          ?>
    
          <?php 
          $count = 0;
          $priceAv = [];
          $profitMarginAv = [];
          $qtyTotal = [];
          $profitTotal = [];
          $profitTotalCAD = [];

          foreach($data as $item){
               $sku = isset($item[0]) ? $item[0] : "N/A";
               $cost = isset($item[1]) ? floatval($item[1]) : 0;
               $price = isset($item[2]) ? floatval($item[2]) : 0;
               $qty = isset($item[3]) ? $item[3] : "N/A";

    
               $profitMarginCalc = floatval($price) - floatval($cost);
     
               $totaProfitUSD = number_format( $profitMarginCalc + $cost,2 ,'.','') ;
               // Make a totalProfit  in CAD based on the usd value on totalProfitUSD and the cad value on the constructor
               $totalProfitCAD = number_format($totaProfitUSD * $this->cad,2,'.','');

               if ($sku == "N/A" || $cost == 0 || $price == 0 || $qty == "N/A"){
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

               $theTable .= "<tbody>";
               array_push($priceAv, $price);
               array_push($profitMarginAv, $profitMarginCalc);
               array_push($qtyTotal, $qty);
               array_push($profitTotal, $totaProfitUSD);
               array_push($profitTotalCAD, $totalProfitCAD);

      
          }
          // footer
          echo "<tfoot>";
          // get the $count and loop it to make a table row
          echo "<tr>";
          echo "<td> </td>";
          echo "<td>  </td>";
          echo "<td> Avegare Price:  </td>";
          echo "<td>Total QTY  </td>";
          echo "<td> Average profit margin </td>";
          echo "<td> Total profit USD </td>";
          echo "<td> Total profit in CAD: </td>";

          echo "</tr>";
          echo "<tr>";
          // sku
          // 22222
                    echo "<td></td>";
                    //Cost
                    echo "<td></td>";
                    // price average
                    echo "<td> <strong>" . number_format(array_sum($priceAv) / count($priceAv),2,'.','') . " </strong> </td>";
               
                    //qty
                    echo "<td> <strong>" . array_sum($qtyTotal) . " </strong>  </td>";
                    // profit margin
                    echo "<td> <strong> " . number_format(array_sum($profitMarginAv) / count($profitMarginAv),2,'.','') . " </strong> </td>";
                    // total profit
                    echo "<td> <strong> USD " . number_format(array_sum($profitTotal),2,'.','') . " </strong> </td>";
                    // total profit in CAD
                    echo "<td> <strong> CAD " . number_format(array_sum($profitTotalCAD),2,'.','') . " </strong> </td>";

          echo "</tr>";
     

    
echo "</tfoot>";
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
 
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

     <title>Document</title>
 
     <?php 
          $priceProcessor = new PriceProcessor('sample.csv' , 1.3);
          $priceProcessor->processFile();
     ?>
</head>
<body>
     
</body>
</html>

