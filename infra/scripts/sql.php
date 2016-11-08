<?php
   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('/var/www/SPOT/infra/web/db/conf.db');
      }
   }
   $db = new MyDB();
   if(!$db){
      echo $db->lastErrorMsg();
   } else {
      echo "Opened database successfully\n";
   }

   $sql =<<<EOF
      CREATE TABLE TRACE
      (ID INT PRIMARY KEY     NOT NULL,
      SWITCH           TEXT    NOT NULL,
      PORTS        CHAR(250))
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "Table created successfully\n";
   }
   $db->close();
?>
