<?php

session_start();
ini_set("memory_limit", "-1");
ini_set("set_time_limit", "0");
set_time_limit(0);
if (!isset($_GET['var'])) {
    echo "<br />No Table Variable Present, nothing to Export.";
    exit;
} else {
    $tablevar = $_GET['var'];
    $title = $_GET['title'];
    $fname = $title;


    if (isset($_SESSION['confidential'])) {
        $confidential = $_SESSION['confidential'];
        $rgb = "00FFFF";
    } else {
        $confidential = "Public";
        $rgb = "FF0000";
    }
}
if (!isset($_GET['limit'])) {
    $limit = 12;
} else {
    $limit = $_GET['limit'];
}
if (!isset($_GET['debug'])) {
    $debug = true;
} else {
    $debug = true;
    $handle = fopen("exportlog.txt", "w");
    fwrite($handle, "\nDebugging On...");
}
if (!isset($_SESSION[$tablevar]) OR $_SESSION[$tablevar] == '') {
    echo "<br />Empty HTML Table, nothing to Export.";
    exit;
} else {
    $htmltable = $_SESSION[$tablevar];
}
if (strlen($htmltable) == strlen(strip_tags($htmltable))) {
    echo "<br />Invalid HTML Table after Stripping Tags, nothing to Export.";
    exit;
}
$htmltable = str_replace("<br />", "\n", $htmltable);
$htmltable = str_replace("<br/>", "\n", $htmltable);
$htmltable = str_replace("<br>", "\n", $htmltable);
$htmltable = str_replace("&nbsp;", " ", $htmltable);
$htmltable = str_replace("\n\n", "\n", $htmltable);

//
//  Extract HTML table contents to array
//

$dom = new domDocument;
$dom->loadHTML($htmltable);
if (!$dom) {
    echo "<br />Invalid HTML DOM, nothing to Export.";
    exit;
}
$dom->preserveWhiteSpace = false;             // remove redundant whitespace
$tables = $dom->getElementsByTagName('table');
if (!is_object($tables)) {
    echo "<br />Invalid HTML Table DOM, nothing to Export.";
    exit;
}
if ($debug) {
    fwrite($handle, "\nTable Count: " . $tables->length);
}
$tbcnt = $tables->length - 1;                 // count minus 1 for 0 indexed loop over tables
if ($tbcnt > $limit) {
    $tbcnt = $limit;
}
//
//
// Create new PHPExcel object with default attributes
//
require_once ('../../phreeze/libs/PEAR/PHPExcel.php');


$objPHPExcel = new PHPExcel();
//$objPHPExcel->setInputEncoding('UTF-8');
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$usermail = 'sysprod_sw_delivery@mycompany.com';
$tm = date("YmdHis");
$pos = strpos($usermail, "@");
$user = substr($usermail, 0, $pos);
$user = str_replace(".", "", $user);


//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// $objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT );
//$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_CENTER); 
//$tfn = $user."_".$tm."_".$tablevar.".xlsx";
//$fname = "AuditLog/".$tfn;
$fname = $title;

$objPHPExcel->getProperties()->setCreator('SysProd')
        ->setLastModifiedBy('SysProd')
        ->setTitle($title)
        ->setSubject('Excel export of ' . $title)
        ->setDescription('Automated report generation.')
        ->setKeywords('Exported File')
        ->setCompany('Mycompany')
        ->setCategory('Export');


$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BMycompany&R&B&K' . $rgb . $confidential);
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B&D&R' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');
//$objPHPExcel->getActiveSheet()->mergeCells("A0:A1");
//
// Loop over tables in DOM to create an array, each table becomes a worksheet
//
for ($z = 0; $z <= $tbcnt; $z++) {
    $maxcols = 0;
    $totrows = 0;
    $headrows = array();
    $bodyrows = array();
    $r = 0;
    $h = 0;
    $rows = $tables->item($z)->getElementsByTagName('tr');
    $totrows = $rows->length;
    if ($debug) {
        fwrite($handle, "\nTotal Rows: " . $totrows);
    }


    foreach ($rows as $row) {
        $ths = $row->getElementsByTagName('th');
        if (is_object($ths)) {
            if ($ths->length > 0) {
                $headrows[$h]['colcnt'] = $ths->length;
                if ($ths->length > $maxcols) {
                    $maxcols = $ths->length;
                }
                $nodes = $ths->length - 1;
                for ($x = 0; $x <= $nodes; $x++) {
                    $thishdg = $ths->item($x)->nodeValue;
                    $headrows[$h]['th'][] = $thishdg;
                    $headrows[$h]['strong'][] = findBoldText(innerHTML($ths->item($x)));
                    if ($ths->item($x)->hasAttribute('style')) {
                        $style = $ths->item($x)->getAttribute('style');
                        $stylecolor = findStyleColor($style);
                        if ($stylecolor == '') {
                            $headrows[$h]['color'][] = findSpanColor(innerHTML($ths->item($x)));
                        } else {
                            $headrows[$h]['color'][] = $stylecolor;
                        }
                    } else {
                        $headrows[$h]['color'][] = findSpanColor(innerHTML($ths->item($x)));
                    }
                    if ($ths->item($x)->hasAttribute('colspan')) {
                        $headrows[$h]['colspan'][] = $ths->item($x)->getAttribute('colspan');
                    } else {
                        $headrows[$h]['colspan'][] = 1;
                    }
                    if ($ths->item($x)->hasAttribute('align')) {
                        $headrows[$h]['align'][] = $ths->item($x)->getAttribute('align');
                    } else {
                        $headrows[$h]['align'][] = 'left';
                    }
                    if ($ths->item($x)->hasAttribute('valign')) {
                        $headrows[$h]['valign'][] = $ths->item($x)->getAttribute('valign');
                    } else {
                        $headrows[$h]['valign'][] = 'top';
                    }
                    if ($ths->item($x)->hasAttribute('bgcolor')) {
                        $headrows[$h]['bgcolor'][] = str_replace("#", "", $ths->item($x)->getAttribute('bgcolor'));
                    } else {
                        $headrows[$h]['bgcolor'][] = 'FFFFFF';
                    }
                }
                $h++;
            }
        }
    }



    foreach ($rows as $row) {
        $tds = $row->getElementsByTagName('td');
        if (is_object($tds)) {
            if ($tds->length > 0) {
                $bodyrows[$r]['colcnt'] = $tds->length;
                if ($tds->length > $maxcols) {
                    $maxcols = $tds->length;
                }
                $nodes = $tds->length - 1;
                for ($x = 0; $x <= $nodes; $x++) {
                    $thistxt = $tds->item($x)->nodeValue;
                    $bodyrows[$r]['td'][] = $thistxt;
                    $bodyrows[$r]['strong'][] = findBoldText(innerHTML($tds->item($x)));
                    if ($tds->item($x)->hasAttribute('style')) {
                        $style = $tds->item($x)->getAttribute('style');
                        $stylecolor = findStyleColor($style);
                        if ($stylecolor == '') {
                            $bodyrows[$r]['color'][] = findSpanColor(innerHTML($tds->item($x)));
                        } else {
                            $bodyrows[$r]['color'][] = $stylecolor;
                        }
                    } else {
                        $bodyrows[$r]['color'][] = findSpanColor(innerHTML($tds->item($x)));
                    }
                    if ($tds->item($x)->hasAttribute('colspan')) {
                        $bodyrows[$r]['colspan'][] = $tds->iy§tem($x)->getAttribute('colspan');
                    } else {
                        $bodyrows[$r]['colspan'][] = 1;
                    }
                    if ($tds->item($x)->hasAttribute('align')) {
                        $bodyrows[$r]['align'][] = $tds->item($x)->getAttribute('align');
                    } else {
                        $bodyrows[$r]['align'][] = 'left';
                    }
                    if ($tds->item($x)->hasAttribute('valign')) {
                        $bodyrows[$r]['valign'][] = $tds->item($x)->getAttribute('valign');
                    } else {
                        $bodyrows[$r]['valign'][] = 'top';
                    }
                    if ($tds->item($x)->hasAttribute('bgcolor')) {
                        $bodyrows[$r]['bgcolor'][] = str_replace("#", "", $tds->item($x)->getAttribute('bgcolor'));
                    } else {
                        $bodyrows[$r]['bgcolor'][] = 'FFFFFF';
                    }
                }
                $r++;
            }
        }
    }






    if ($z > 0) {
        $objPHPExcel->createSheet($z);
    }
    $suf = $z + 1;
    //  $tableid = $tablevar.$suf;
    $tableid = $title . '_' . $suf;
    $wksheetname = ucfirst($tableid);
    $objPHPExcel->setActiveSheetIndex($z);                      // each sheet corresponds to a table in html
    $objPHPExcel->getActiveSheet()->setTitle($wksheetname);     // tab name
    $worksheet = $objPHPExcel->getActiveSheet();                // set worksheet we're working on
    $style_overlay = array('font' =>
        array('color' =>
            array('rgb' => 'FFFFFF'), 'bold' => false,),
        'fill' =>
        array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCFF')),
        'alignment' =>
        array('wrap' => true, 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP),
        'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_NONE),
            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE),
            'left' => array('style' => PHPExcel_Style_Border::BORDER_NONE),
            'right' => array('style' => PHPExcel_Style_Border::BORDER_NONE)),
    );
    $xcol = '';
    $xrow = 1;
    $usedhdrows = 0;
    $heightvars = array(1 => '42', 2 => '42', 3 => '48', 4 => '52', 5 => '58', 6 => '64', 7 => '68', 8 => '76', 9 => '82');
    for ($h = 0; $h < count($headrows); $h++) {
        $th = $headrows[$h]['th'];
        $colspans = $headrows[$h]['colspan'];
        $aligns = $headrows[$h]['align'];
        $valigns = $headrows[$h]['valign'];
        $bgcolors = $headrows[$h]['bgcolor'];
        $colcnt = $headrows[$h]['colcnt'];
        $colors = $headrows[$h]['color'];
        $bolds = $headrows[$h]['bold'];
        $usedhdrows++;
        $mergedcells = false;
        for ($t = 0; $t < count($th); $t++) {
            if ($xcol == '') {
                $xcol = 'A';
            } else {
                $xcol++;
            }
            $thishdg = $th[$t];
            $thisalign = $aligns[$t];
            $thisvalign = $valigns[$t];
            $thiscolspan = $colspans[$t];
            $thiscolor = $colors[$t];
            $thisbg = $bgcolors[$t];
            //	$thisbg = $bgcolors; 
            $thisbold = $bolds[$t];
            $strbold = ($thisbold == true) ? 'true' : 'false';
            if ($thisbg == 'FFFFFF') {
                $style_overlay['fill']['type'] = PHPExcel_Style_Fill::FILL_NONE;
            } else {
                $style_overlay['fill']['type'] = PHPExcel_Style_Fill::FILL_SOLID;
            }
            $style_overlay['alignment']['vertical'] = $thisvalign;              // set styles for cell
            $style_overlay['alignment']['horizontal'] = $thisalign;
            $style_overlay['font']['color']['rgb'] = $thiscolor;
            $style_overlay['font']['bold'] = $thisbold;
            $style_overlay['fill']['color']['rgb'] = $thisbg;
            $worksheet->setCellValue($xcol . $xrow, $thishdg);
            $worksheet->getStyle($xcol . $xrow)->applyFromArray($style_overlay);
            if ($debug) {
                fwrite($handle, "\n" . $xcol . ":" . $xrow . " ColSpan:" . $thiscolspan . " Color:" . $thiscolor . " Align:" . $thisalign . " VAlign:" . $thisvalign . " bgcolor:" . $thisbg . " Bold:" . $strbold . " cellValue: " . $thishdg);
            }
            if ($thiscolspan > 1) {                                                // spans more than 1 column
                $mergedcells = true;
                $lastxcol = $xcol;
                for ($j = 1; $j < $thiscolspan; $j++) {
                    $lastxcol++;
                    $worksheet->setCellValue($lastxcol . $xrow, '');
                    $worksheet->getStyle($lastxcol . $xrow)->applyFromArray($style_overlay);
                }
                $cellRange = $xcol . $xrow . ':' . $lastxcol . $xrow;
                if ($debug) {
                    fwrite($handle, "\nmergeCells: " . $xcol . ":" . $xrow . " " . $lastxcol . ":" . $xrow);
                }
                $worksheet->mergeCells($cellRange);
                $worksheet->getStyle($cellRange)->applyFromArray($style_overlay);
                $num_newlines = substr_count($thishdg, "\n");                       // count number of newline chars
                if ($num_newlines > 1) {
                    $rowheight = $heightvars[1];                                      // default to 35
                    if (array_key_exists($num_newlines, $heightvars)) {
                        $rowheight = $heightvars[$num_newlines];
                    } else {
                        $rowheight = 75;
                    }
                    $worksheet->getRowDimension($xrow)->setRowHeight($rowheight);     // adjust heading row height
                }
                $xcol = $lastxcol;
            }
        }
        $xrow++;
        $xcol = '';
    }
    //Put an auto filter on last row of heading only if last row was not merged
    if (!$mergedcells) {
        $worksheet->setAutoFilter("A$usedhdrows:" . $worksheet->getHighestColumn() . $worksheet->getHighestRow());
    }
    if ($debug) {
        fwrite($handle, "\nautoFilter: A" . $usedhdrows . ":" . $worksheet->getHighestColumn() . $worksheet->getHighestRow());
    }
    // Freeze heading lines starting after heading lines
    $usedhdrows++;
    $worksheet->freezePane("A$usedhdrows");
    if ($debug) {
        fwrite($handle, "\nfreezePane: A" . $usedhdrows);
    }
    //
    // Loop thru data rows and write them out
    //
		$xcol = '';
    $xrow = $usedhdrows;
    for ($b = 0; $b < count($bodyrows); $b++) {
        $td = $bodyrows[$b]['td'];
        $colcnt = $bodyrows[$b]['colcnt'];
        $colspans = $bodyrows[$b]['colspan'];
        $aligns = $bodyrows[$b]['align'];
        $valigns = $bodyrows[$b]['valign'];
        $bgcolors = $bodyrows[$b]['bgcolor'];
        $colors = $bodyrows[$b]['color'];
        $bolds = $bodyrows[$b]['bold'];
        for ($t = 0; $t < count($td); $t++) {
            if ($xcol == '') {
                $xcol = 'A';
            } else {
                $xcol++;
            }
            $thistext = $td[$t];
            $thisalign = $aligns[$t];
            $thisvalign = $valigns[$t];
            $thiscolspan = $colspans[$t];
            $thiscolor = $colors[$t];
            $thisbg = $bgcolors[$t];
            $thisbold = $bolds[$t];
            $strbold = ($thisbold == true) ? 'true' : 'false';
            if ($thisbg == 'FFFFFF') {
                $style_overlay['fill']['type'] = PHPExcel_Style_Fill::FILL_NONE;
            } else {
                $style_overlay['fill']['type'] = PHPExcel_Style_Fill::FILL_SOLID;
            }
            $style_overlay['alignment']['vertical'] = $thisvalign;              // set styles for cell
            $style_overlay['alignment']['horizontal'] = $thisalign;
            $style_overlay['font']['color']['rgb'] = $thiscolor;
            $style_overlay['font']['bold'] = $thisbold;
            $style_overlay['fill']['color']['rgb'] = $thisbg;
            if ($thiscolspan == 1) {
                $worksheet->getColumnDimension($xcol)->setWidth(25);
            }
            if (strpos($thistext, 'http') !== false) {
                $url = true;
                $worksheet->setCellValue($xcol . $xrow, 'IST link');
            } else {
                $url = false;
                $worksheet->setCellValue($xcol . $xrow, $thistext);
            }

            if ($url == true) {
                $worksheet->getCell($xcol . $xrow)->getHyperlink()->setUrl($thistext);
            }
            if ($debug) {
                fwrite($handle, "\n" . $xcol . ":" . $xrow . " ColSpan:" . $thiscolspan . " Color:" . $thiscolor . " Align:" . $thisalign . " VAlign:" . $thisvalign . " bgcolor:" . $thisbg . " Bold:" . $strbold . " cellValue: " . $thistext);
            }

            $worksheet->getStyle($xcol . $xrow)->applyFromArray($style_overlay);
            if ($thiscolspan > 1) {                                                // spans more than 1 column
                $lastxcol = $xcol;
                for ($j = 1; $j < $thiscolspan; $j++) {
                    $lastxcol++;
                }
                $cellRange = $xcol . $xrow . ':' . $lastxcol . $xrow;
                if ($debug) {
                    fwrite($handle, "\nmergeCells: " . $xcol . ":" . $xrow . " " . $lastxcol . ":" . $xrow);
                }
                $worksheet->mergeCells($cellRange);
                $worksheet->getStyle($cellRange)->applyFromArray($style_overlay);
                $xcol = $lastxcol;
            }
        }
        $worksheet->getStyle($xcol . $xrow)
                ->getBorders()
                ->getTop()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
        $worksheet->getStyle($xcol . $xrow)
                ->getBorders()
                ->getBottom()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
        $worksheet->getStyle($xcol . $xrow)
                ->getBorders()
                ->getLeft()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
        $worksheet->getStyle($xcol . $xrow)
                ->getBorders()
                ->getRight()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);

        $xrow++;
        $worksheet->getColumnDimension($xcol)->setWidth(50);

        $xcol = '';
    }

    if ($debug) {
        fwrite($handle, "\nHEADROWS: " . print_r($headrows, true));
        fwrite($handle, "\nBODYROWS: " . print_r($bodyrows, true));
    }
} // end for over tables
$objPHPExcel->setActiveSheetIndex(0);                      // set to first worksheet before close

$worksheet->insertNewRowBefore(1, 1);
// Generate an image
/* $gdImage = @imagecreatetruecolor(1200, 200) or die('Cannot Initialize new GD image stream');
  $textColor = imagecolorallocate($gdImage, 255, 255, 255);
  imagestring($gdImage, 101, 5, 5,  'Secret', $textColor);
 */
// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();

//$objDrawing = new PHPExcel_Worksheet_HeaderFooterDrawing();
$objDrawing->setName('Mycompany Logo');
$objDrawing->setDescription('Mycompany Logo');
//$img = imagecreatefrompng($_SERVER["DOCUMENT_ROOT"] . '/SPOT/provisioning/images/logo_mycompany.png');
//$objDrawing->setImageResour($img);
//$objDrawing->setPath($_SERVER["DOCUMENT_ROOT"] . '/SPOT/provisioning/images/logo_mycompany.png', false);
$objDrawing->setPath('http://localhost/SPOT/provisioning/images/logo_mycompany.png', false);
////$objDrawing->setRenderingFunction(PHPExcel_Worksheet_::RENDERING_PNG);
//$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_PNG);


$objDrawing->setCoordinates('A1');
//$objPHPExcel->getActiveSheet()->addImage($objDrawing, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT);
$objDrawing->setResizeProportional(true);
$objDrawing->setHeight(50);
$objDrawing->setWidth(150);
$objDrawing->setOffsetX(8);
$objDrawing->getShadow()->setVisible(true);
$objDrawing->setWorksheet($worksheet);
//$worksheet->mergeCells('A1:');
//
// Write to Browser
if (isset($_SESSION['imageOS'])) {
    $img = $_SESSION['imageOS'];
    // unset($_SESSION['imageOS']);
    $file = "/var/www/SPOT/log/" . $img;
    while (!file_exists($file))
        sleep(1);
    $objDrawing2 = new PHPExcel_Worksheet_Drawing();
    $objDrawing2->setCoordinates('B1');
    $objDrawing2->setPath("http://x.x.x.204/SPOT/log/" . $img, false);
    $objDrawing2->setResizeProportional(true);
    $objDrawing2->setName('Operating system logo');
    $objDrawing2->setDescription('Operating system logo');
    $objDrawing2->setHeight(50);
    $objDrawing2->setWidth(50);
    $objDrawing2->getShadow()->setVisible(true);

    $objDrawing2->setWorksheet($worksheet);
} else {
    $salesorder = basename($title, ".pdf");
    $worksheet->getCell('B1')->setValue("Sales order: ".$salesorder);
    $worksheet->getStyle("B1")->getFont()->setSize(25);
    $worksheet->getStyle("B1")->getFont()->setBold(true);
    $worksheet->getStyle("B1")->getFont()->getColor()->setRGB('FFFFFF');
    
}
$worksheet->getStyle('A1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF0000')
            )
        )
);
$worksheet->getStyle('B1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF0000')
            )
        )
);
if ($debug) {
    fclose($handle);
}
//$worksheet->setCellValue('B' . '1', file_get_contents("http://x.x.x.204/SPOT/log/" . $img) );
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
header('Content-Type: application/pdf');
header("Content-Disposition: attachment;filename=$fname");
header('Cache-Control: max-age=0');
header('Content-Transfer-Encoding: binary');
//$objWriter->save($fname);
$objWriter->save('php://output');

exit;

function innerHTML($node) {
    $doc = $node->ownerDocument;
    $frag = $doc->createDocumentFragment();
    foreach ($node->childNodes as $child) {
        $frag->appendChild($child->cloneNode(TRUE));
    }
    return $doc->saveXML($frag);
}

function findSpanColor($node) {
    $pos = stripos($node, "color:");       // ie: looking for style='color: #FF0000;'
    if ($pos === false) {                  //                        12345678911111
        return '000000';                     //                                 01234
    }
    $node = substr($node, $pos);           // truncate to color: start
    $start = "#";                          // looking for html color string
    $end = ";";                            // should end with semicolon
    $node = " " . $node;                     // prefix node with blank
    $ini = stripos($node, $start);          // look for #
    if ($ini === false)
        return "000000";   // not found, return default color of black
    $ini += strlen($start);                // get 1 byte past start string
    $len = stripos($node, $end, $ini) - $ini; // grab substr between start and end positions
    return substr($node, $ini, $len);        // return the RGB color without # sign
}

function findStyleColor($style) {




    $pos = stripos($style, "bgcolor:");      // ie: looking for style='color: #FF0000;'
    if ($pos === false) {                  //                        12345678911111
        return '';                           //                                 01234
    }
    $style = substr($style, $pos);           // truncate to color: start
    $start = "#";                          // looking for html color string
    $end = ";";                            // should end with semicolon
    $style = " " . $style;                     // prefix node with blank
    $ini = stripos($style, $start);          // look for #
    if ($ini === false)
        return "";         // not found, return default color of black
    $ini += strlen($start);                // get 1 byte past start string
    $len = stripos($style, $end, $ini) - $ini; // grab substr between start and end positions
    return substr($style, $ini, $len);        // return the RGB color without # sign
}

function findBoldText($node) {
    $pos = stripos($node, "<b>");          // ie: looking for bolded text
    if ($pos === false) {                  //                        12345678911111
        return false;                        //                                 01234
    }
    return true;                           // found <b>
}

?>
