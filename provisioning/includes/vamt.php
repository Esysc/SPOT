<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Initial version to read data from a mssql server
 * In this case read the keys stored in VAMT
 */

include 'share.php';

function unsetNumKeys($row) {
    foreach ($row as $key => $value) {
        if (is_array($value))
            unsetNumKeys ($value);
        if (is_int($key)) {
            unset($row[$key]);
        }
    }
    return $row;
}

$connection = mssql_connect('VAMT', 'sa', '***REMOVED***');

if (!$connection) {
    die('<div class="alert alert-danger">Unable to connect!</div>');
}

if (!mssql_select_db('VAMT', $connection)) {
    die('<div class="alert alert-danger">Unable to select database!</div>');
}
$stmt = "SELECT  [KeyDescription]      
      ,[KeyValue]
      ,[RemainingActivations]
      ,[SupportedEditions]
      ,[SupportedSKU]
      ,[KeyTypeName]
  FROM [VAMT].[api].[ProductKey]";
$result = mssql_query($stmt);
$return1 = mssql_fetch_array($result);

$stmt = "SELECT  [FullyQualifiedDomainName]
      ,[DomainWorkgroupName]
      ,[IsKmsHost]
      ,[NetworkType]
      ,[OSEdition]
      ,[OSVersion]
  FROM [VAMT].[api].[VolumeClient]";
//$result = mssql_query('SELECT * FROM base.ProductKey');
$result = mssql_query($stmt);
$return2 = mssql_fetch_array($result);
$return = array2table(unsetNumKeys($return1))."<div id='export'>".array2table(unsetNumKeys($return2))."</div>";

echo $return;


mssql_free_result($result);


