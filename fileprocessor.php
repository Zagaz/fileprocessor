<?php

class PriceProcessor
{
     private $csvFilePath;

     private $cad;

     /*
     * @param string $csvFilePath
     * - The path to the CSV file
     * @param float $cad
     * - The exchange rate between USD and CAD
     * - The 'FREE' APIs were asking for a credit card, so instead of using an API I just used a static value.
     */
     public function __construct($csvFilePath,  $cad)
     {
          $this->csvFilePath = $csvFilePath;
          // The "FREE" API's were asking for a credit card, so intead of using an API I just used a static value.
          $this->cad = $cad;
     }

     /*
     * @return void
     * - This function will process the CSV file and output the data in a table
     */
     public function processFile()
     {
          // open the file and make a table with it
          $file = fopen($this->csvFilePath, 'r');
          $table = array();
          while (($line = fgetcsv($file)) !== FALSE) {
               $table[] = $line;
          }
          fclose($file);

          // The table starts here....

          echo "<table class =  'table table-striped'>";
          echo '<thead>';
          echo "<tr>";
          foreach ($table[0] as $item) {
               echo "<th>$item</th>";
          }
          echo "</tr>";
          // get the data from tabel and make an array
          $data = array();
          for ($i = 1; $i < count($table); $i++) {
               $data[] = $table[$i];
          }
          echo '</thead>';

          // with each data from $data array make a table row
          $theTable = "<tbody class ='table-striped' >";

          // To be used for the footer calculations
          $priceAv = [];
          $profitMarginAv = [];
          $qtyTotal = [];
          $profitTotal = [];
          $profitTotalCAD = [];

          // The table body starts here....
          foreach ($data as $item) {
               $sku = isset($item[0]) ? $item[0] : "N/A";
               $cost = isset($item[1]) ? floatval($item[1]) : 0;
               $price = isset($item[2]) ? floatval($item[2]) : 0;
               $qty = isset($item[3]) ? $item[3] : "N/A";
               
               $profitMarginCalc = floatval($price) - floatval($cost);
               $totaProfitUSD = number_format($profitMarginCalc + $cost, 2, '.', '');
               $totalProfitCAD = number_format($totaProfitUSD * $this->cad, 2, '.', '');

               if ($sku == "N/A" || $cost == 0 || $price == 0 || $qty == "N/A") {
                    continue;
               }
               // QTY, profit margin, and total profit have the possibility to be negative. If these values are negative output the values as red. If positive, green.
               $qtyClass = $qty <= 0 ? "text-danger" : "text-success";
               $profitMarginClass = $profitMarginCalc <= 0 ? "text-danger" : "text-success";
               $totalProfitClass = $totaProfitUSD <= 0 ? "text-danger" : "text-success";
               
               echo "<tr>";
               echo "<td>$sku</td>";
               echo "<td>$cost</td>";
               echo "<td>$price</td>";
               echo "<td class = $qtyClass >$qty</td>";
               echo "<td class = $profitMarginClass> $profitMarginCalc</td>";
               echo "<td class = $totalProfitClass> $totaProfitUSD</td>";
               echo "<td>$totalProfitCAD</td>";
               echo "</tr>";

               $theTable .= "<tbody>";
               array_push($priceAv, $price);
               array_push($profitMarginAv, $profitMarginCalc);
               array_push($qtyTotal, $qty);
               array_push($profitTotal, $totaProfitUSD);
               array_push($profitTotalCAD, $totalProfitCAD);
          }
  

          echo "</tbody>";         // The table body ends here....

          // The table footer starts here....
          echo "<tfoot>";
          // Footer: Average Price, total qty, average profit margin, total profit (USD), total profit (CAD).
          echo "<tr>";
          echo "<td> </td>";
          echo "<td>  </td>";
          echo "<td> Avegare Price:  </td>";
          echo "<td>Total QTY  </td>";
          echo "<td> Average profit margin </td>";
          echo "<td> Total profit USD </td>";
          echo "<td> Total profit in CAD</td>";

          echo "</tr>";
          echo "<tr>";

          $totalAveragePrice = array_sum($priceAv) / count($priceAv) <= 0 ? "text-danger" : "text-success";
          $totalAverageQty = array_sum($qtyTotal) <= 0 ? "text-danger" : "text-success";
          $totalAverageProfitMargin = array_sum($profitMarginAv) / count($profitMarginAv) <= 0 ? "text-danger" : "text-success";
          $totalAverageProfitUSD = array_sum($profitTotal) <= 0 ? "text-danger" : "text-success";
          $totalAverageProfitCAD = array_sum($profitTotalCAD) <= 0 ? "text-danger" : "text-success";

          // SKU Blank
          echo "<td></td>";
          //Cost
          echo "<td></td>";
          // price average
          echo "<td class = '$totalAveragePrice'> <strong>" . number_format(array_sum($priceAv) / count($priceAv), 2, '.', '') . " </strong> </td>";
          //qty
          echo "<td class = ' $totalAverageQty'> <strong>" . array_sum($qtyTotal) . " </strong>  </td>";
          // profit margin
          echo "<td class = '$totalAverageProfitMargin' > <strong> " . number_format(array_sum($profitMarginAv) / count($profitMarginAv), 2, '.', '') . " </strong> </td>";
          // total profit
          echo "<td class = '$totalAverageProfitUSD'> <strong> USD " . number_format(array_sum($profitTotal), 2, '.', '') . " </strong> </td>";
          // total profit in CAD
          echo "<td class = '$totalAverageProfitCAD'> <strong> CAD " . number_format(array_sum($profitTotalCAD), 2, '.', '') . " </strong> </td>";
          echo "</tr>";
          echo "</tfoot>"; // The table footer ends here....
          echo "</table>";
          // The table ends here....

         // And here is where the magic happens. 
          echo $theTable;
     }
}
?>
