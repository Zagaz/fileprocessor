<?php 

class PriceProcessor{
     private $csvFilePath;

     // constructor
     public function __construct($csvFilePath){
          $this->csvFilePath = $csvFilePath;
     }

     public function processFile(){
          // open the file and make a table with it
          $file = fopen($this->csvFilePath, 'r');
          $table = array();
          while (($line = fgetcsv($file)) !== FALSE) {
               $table[] = $line;
          }
          fclose($file);

          var_dump($table);



     }

}

$priceProcessor = new PriceProcessor('sample.csv');
$priceProcessor->processFile();

