<?php


	require_once "order_items.php";
	require_once "commonFunctions.php";
	require "html2pdf/html2pdf.class.php";
	define("PAGE_BRK_START",'<page backtop="5mm" backbottom="10mm" backleft="0mm" backright="0mm">');
	define("PAGE_BRK_END", '</page>');
	define("FOOTER_INC", "PDFGen_footer.inc");
	define("HEADER_INC", "PDFGen_header.inc");
	
	define("MAX_TD_LEN", 25);
	
	define("MAX_ROWS_PER_PAGE", 27 );
	
	class EXPORTER_EX extends Exception 
	{
		private $head = "[ORDER_EXPORT]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
	
	class EXPORTERS extends ITEMS
	{
		private $htmlFile = null;
		private $pdfFile = null;
		private $curentPtrPos;
		private $header;
		private $footer;
		
		protected function __construct($so, $modelList)
		{
			parent::__construct($so , $modelList);
			
		}
		public function downloadReport($array_return=False)
		{
			return $this->generateReportPDF($array_return);
			
		}

		
		private function utf82ansi($str)
		{
			return iconv("UTF-8", "ISO-8859-1",$str);
		}
		
		private function load_template($template)
		{
			$TEMPLATESDIR = __DIR__."/templates/".$template;
			$template = file_get_contents($TEMPLATESDIR);
			
			$this->header = file_get_contents(__DIR__."/templates/".HEADER_INC);
			$this->footer = file_get_contents(__DIR__."/templates/".FOOTER_INC);
			
			if ( ! defined("HEADER_START") )
			{
				define("HEADER_START", "{begin_meterial}");
				define("HEADER_STOP", "{end_meterial}");
			}
			
			
			$metrial_header_pos = strpos($template, HEADER_START);
			$metrial_footer_pos = strpos($template, HEADER_STOP) + strlen(HEADER_STOP);
			
			if ( $metrial_header_pos === false or $metrial_footer_pos === false )
			{
				
				throw new EXPORTER_EX("Error malformed template!");
			}
			else
			{
				$material = substr($template, $metrial_header_pos , $metrial_footer_pos - $metrial_header_pos);
				$template = substr($template, 0, $metrial_header_pos ).substr($template, $metrial_footer_pos, strlen($template));
				
				$material_end_header = strlen(HEADER_START);
				$material_start_footer = strpos($material,HEADER_STOP); 
				
				$material_start_footer = $material_start_footer - $material_end_header;
				
			}	
			
			
			return array( "METERIAL" => substr($material, $material_end_header,$material_start_footer), 
			              "TEMPLATE" => $template);
			
			
		}
                private function encryptionBoard2Polaroid($data)
                {
                    return strlen($data)>2 ? preg_replace("/(zph|tph)\d\:/", "",$data) : "";
                }
		public function prepareExportPolaroid()
		{
			$fmt_datas = array();
			$items_list = $this->getItems();
			
			foreach ( $items_list as $category=>$serials)
			{
				if ( $serials != NULL )
				{
					foreach ($serials as $serial=>$item_values)
					{
						
						$sndSerialKey = "";
						foreach ( array_keys($item_values) as $index=>$keyName)
						{
                                                    
							if ( strpos($keyName, "Serial") !== FALSE )
							{
								
								if ( $item_values[$keyName] != "" && $keyName != "str_zephyrsSerials" && $item_values[$keyName] != "-" )
								{
                                                                    $sndSerialKey = $keyName;
								}
								
							}
						}
						if ( isset($item_values["str_zephyrsSerials"])  )
						{
							
							
							$encryptionBorards = explode(";", $item_values["str_zephyrsSerials"]);
							for ( $i = count($encryptionBorards); $i<3;$i++)
							{
								$encryptionBorards[$i] = "";
							}
							
						}
						else
							$encryptionBorards = explode(";", ";;");
						
						
						
						$fmt_datas[] = array("CRMID"          => $this->getCRMID() == "" ? "1-0000" : $this->getCRMID() ,
											 "SERIAL"   => $serial,
											 "BRAND"    => "",
											 "MODEL"    => "",
											 "_modelId" => $item_values["int_modelID"],
											 "PFUNCTION"=> "",
											 "HOSTNAME" => isset($item_values["str_hostname"]) ? $item_values["str_hostname"] : "",
											 "MAINIP"   => isset($item_values["str_ip"]) ? $item_values["str_ip"] : "",
											 "CPUNUMBER"=> 0,
											 "OSNAME"   => isset($item_values["str_osversion"]) ? $item_values["str_osversion"] : "",
											 "RAMSIZEMB"=> 0,
											 "ENCBOARD1"=>  $this->encryptionBoard2Polaroid($encryptionBorards[0]), 
											 "ENCBOARD2"=>  $this->encryptionBoard2Polaroid($encryptionBorards[1]), 
											 "ENCBOARD3"=>  $this->encryptionBoard2Polaroid($encryptionBorards[2]), 
											 "_sndSerial" => ! empty($sndSerialKey) ? $item_values[$sndSerialKey] : ""
											 );
					
					}
				}
			
			}
		
		
		return $fmt_datas;
		
			
			
		}
		public function export_pdf($template)
		{
			
			$loaded_template   = $this->load_template($template);
			$template          = $loaded_template["TEMPLATE"];
			$material_template = $loaded_template["METERIAL"];
			
			$cct_filename = pathinfo(str_replace("\\", "\/", $this->getCCTSnaptshot()), PATHINFO_BASENAME );
			
			$template = str_replace("{SalesOrder}"    , $this->getSalesOrder()                      , $template);
			$template = str_replace("{ProgramManager}", $this->utf82ansi($this->getProgramManager()), $template);
			$template = str_replace("{SiteEngineer}"  , $this->utf82ansi($this->getSiteEngineer())  , $template);
			$template = str_replace("{pEndDate}"      , $this->getProdEndDate()                     , $template);
			$template = str_replace("{PlanStartDate}" , $this->getEndDate()                         , $template);			
			$template = str_replace("{comment}"       , nl2br($this->getComments())                        , $template);
			$template = str_replace("{sysprodActor}"  , $this->getSysprodActor()                    , $template);
			$template = str_replace("{Release}"       , $this->getRelease()                         , $template);
			$template = str_replace("{customer}"      , $this->getCustomer()                        , $template);
			$template = str_replace("{customer_acry}" , $this->getcustomerAcronym()                 , $template);			
			$template = str_replace("{cctsnaptshot}"  , $cct_filename                               , $template);
			$template = str_replace("{crmID}"         , $this->getCRMID()                           , $template);
			$template = str_replace("{pdbverion}"     , $this->version                              , $template);
			
			
			
			$networkLine = "";
			
			if (count($this->getNetworks()) > 0 )
			{
				foreach ($this->getNetworks() as $nwtName=>$value)
					$networkLine .= $nwtName." : ".$value[0]."/".netmask2cidr($value[1])."<br>";
			}
			else
				$networkLine = "No network(s) defined";
			
			
			
			$template   = str_replace("{nwkName} :&nbsp; {IP}/{mask}", $networkLine, $template);	
			$template   = str_replace("{genDate}", date("d-m-Y"), $template);				
			$item_list  = $this->get_items_list();
			
			$apcCode    = $this->build_table_apc($item_list["APC"], $material_template);
			$singleCode = $this->build_single_items($item_list["SINGLE"], $material_template);
			$template = str_replace("{material}", $singleCode.$apcCode, $template);
			
			
			return $template;
		}
		
		
	/*This function return the column array and the unshowd column index! */
		private function get_columns($objectID, $data)
		{
			
			$object = $this->get_obj_def();
			$columns = array( 0 => "APC code", 1=>"Brand", 2=> "Model" , 3 => "S/N" );					
			
			$out = array();
			$cur_index = 4;
			
			$unshowed_cols_index = array();
			
			
			$cols =  $object[$objectID]->get_report_strings();
			
			
			
			foreach ( $cols as $param=>$reportOpt)
			{
				
				if ( $this->param_col_count($data, $param) != 0)
				{
					if ( strpos(strtolower($param), "modelid") !== FALSE)
						$columns[2]  = "Model";
					else
						$columns[]  = $reportOpt["string"];
				}
				else
				{
					$unshowed_cols_index[] = $cur_index;
				}	
				$cur_index++;
			}
			
			
			
			return array("DISPLAY_INDEXES" => $columns , "OTHERS_INDEXES" => $unshowed_cols_index);
			
			
		}
		
		private function _empty($val)
		{
			return is_null($val) or strval($val) == "0" or strlen($val) == 0 or strval($val) == "''" or count($val) == 0;
		}
		
		private function param_col_count($category, $param)
		{
			$columnCount = 0;
			
			
			
			foreach ( $category as $Index=>$value)
			{
				
				foreach ( $value as $j=>$k)
				{
					
					if ( $j != "_objID_")
					{
						if (   isset($k["data"][$param] )) 
						{

							if ( $this->_empty($k["data"][$param]) === false )
							{
							
								
								$columnCount++;
							}
							
								
						}
						
						
						
					}
						
				}
			}
		    
			return $columnCount;
			
		}
		
		private function get_footer()
		{
			$template = $this->footer;
			$template = str_replace("{genDate}", date("d-m-Y"), $template);				
			$template = str_replace("{pdbverion}" , $this->version , $template);
			
			return $template;
		}
		
		private function build_single_items($items, $template)
		{
			
			
			$object = $this->get_obj_def();
			
			$i=0;
			$fullLine = "";
			
			foreach ( $items as $category=>$index)
			{
				
				
				
				$catgoryLine = "";
				
				$objectID  = $index["_objID_"];
				unset($index["_objID_"]);
				
				$current = str_replace("{categoryID}", $category , $template);
				

				
				
				if ( strpos($category, "Cluster") !== False)
					$current = str_replace("{quantity}", count($index) / 2, $template);
				else
					$current = str_replace("{quantity}", count($index), $template);
				
				$current = str_replace("{category}", $category, $current);
				/*if ( count($index) >= MAX_ROWS_PER_PAGE )
				{
					$current = str_replace("[start_page]",  PAGE_BRK_START.$this->header , $current);
					$current = str_replace("[end_page]",    $this->get_footer().PAGE_BRK_END   , $current);
				}
				else
				{
					$current = str_replace("[start_page]",  ""   , $current);
					$current = str_replace("[end_page]",    "" , $current);					
				}
                                 * */
                                
                                if ( $i == 0 )
                                {
                                    $current = str_replace("[start_page]",   ""  , $current);
                                    $current = str_replace("[end_page]",   ""  , $current);	    
                                     $i = 1;
                                }
                                else
                                {
                                    $current = str_replace("[start_page]",  PAGE_BRK_START   , $current);
                                    $current = str_replace("[end_page]",    $this->get_footer().PAGE_BRK_END , $current);                                    
                                }
                                $cpt  = 0;
				
				
		
				$indexes = $this->get_columns($objectID, $items);
				$columns = $indexes["DISPLAY_INDEXES"];
				
				
				$description = $object[$objectID]->get_description();
				$columnString = $this->draw_table_headers($columns);
				$description = "";
				$cpt = 1;
                                
                                $rowCount = 0;
                                
				foreach ( $index as $key=>$values)
				{
			
					$data      = $values["data"];
					$datas   = array( 0 => "", 1 => "", 2=> "" ,3=> $key);
					
					foreach ( $data as $paramName=>$paramVal)
					{
						
						if ( $object[$objectID]->get_report_string($paramName) != NULL)
						{
							
							
							if ( strpos(strtolower($paramName), "modelid" ) !== FALSE)
							{
								$hw = $this->getModelFromID(intval($paramVal));
								
								$datas[0] = $hw["APCCODE"];
								$datas[1] = $hw["BRAND"];
								$datas[2] = @implode(" ", array_slice($hw, 2)); //Check !!
								
								if ( isset($hw["IBMMT"]))
									$datas[3] = str_replace("-", "", $datas[3]);
							
								
							}
							else
								$datas[] = $paramVal;
						}
		
						
					}
					
                                        
                                       /*if ( $rowCount == MAX_ROWS_PER_PAGE)
                                        {
                                            $catgoryLine .= PAGE_BRK_START.PAGE_BRK_END; 
                                            $rowCount = 0;
                                        }
                                         else
                                             $rowCount++;
					*/
                                        $catgoryLine .= $this->draw_cells($datas, $indexes["OTHERS_INDEXES"]);
                                        
				}
				
		
			
				$current = str_replace("{description}", $description, $current);
				$current = str_replace("{columns}", $columnString, $current);
				$current = str_replace("{hosts}",    $catgoryLine, $current);
				
				
				
				$fullLine .= $current;
				
			}
			
			
			return $fullLine;
			
		}
		
		
		private function draw_current_cell($cellVal)
		{
			
			return is_bool($cellVal) ? $this->draw_cell_tick($cellVal) : $this->draw_cell_value($cellVal);
		}
		private function draw_cell_value($cellVal)
		{
			if ( $cellVal === "" or strval($cellVal) == "0") return "";
			$out = $cellVal;
			if ( strpos($cellVal, ";") )
			{
				$tds = explode(";", $cellVal);
				$trs = "<th align='center'>".implode("</th align='center'><th>", array_keys($tds))."</th>";
				$tds = "<TR><TD>".implode("</TD></TR><TR><TD>", $tds)."</TD></TR>";
				
				return "<td class='tbl_entity_td' ><table>$tds</table></td>";
			}			
			else
				return "<TD class='tbl_entity_td'  style='word-wrap:break-word; width:auto'>".substr($cellVal, 0 , MAX_TD_LEN)."</TD>";
		}
		private function draw_cell_tick($cellVal)
		{
			
			$cellVal ? $image = "tick.png" : $image = "untick.png";
			return "<TD class='tbl_entity_td' style='align:center; width:auto'><img id='bool_box' src='".__DIR__."/templates/$image'></TD>";
		}
		private function draw_table_headers($columns)
		{
				return '<TH align="center" border="0">'.implode($columns, '</TH><TH align="center"  border="0">')."</TH>";
		}
		private function draw_cells($row, $unshed_indexs)
		{
			
			$str = "<TR>";
			
			

			
			foreach ( $row as $cellIndex=>$cellVal)
			{
			
				$str .= $this->draw_current_cell($cellVal);

								
			}
			
			$str .= "</TR>";
			
			return $str;
		}
		
		
		private function build_table_apc($APCArry, $template)
		{
			$fmt = "";
			
			foreach ( $APCArry as $code=>$gid)
			{
				$code_count = count($gid);
				
				
				$code_name  = $code;
				$output = PAGE_BRK_START;
				$output .= "<p align='left'><h3>$code_count x $code</h3>";
				$host_line = "";
				foreach ( $gid as $groupIndex=>$groupMember)
				{
				
					$host_line .= $this->build_single_items($groupMember,$template);
					$host_line .= "<br>";
					
				}
				
				$output .= $host_line."</p>";
				$output .= PAGE_BRK_END;
				
				$fmt .= $output;
			}
			
			return $fmt;
		}
		

		public function generateReportPDF($memory=False)
		{
			
			
			
			try
			{
				
				$PDF = new HTML2PDF('L', 'A4', 'fr',false, 'ISO-8859-1', array(10, 20, 5, 20)); 
				
				$PDF->pdf->SetAuthor($this->getSysprodActor());
				$PDF->setTestTdInOnePage(True);
				$PDF->pdf->SetSubject('Installation report '.$this->getSalesOrder());
				$PDF->pdf->SetDisplayMode('fullpage');
				$PDF->writeHTML($this->export_pdf("PDFGen.current.html"));
				
				if ( $memory )
				{
					$filename = "SO_".strval($this->getSalesOrder()).'_sysprod-installation_report.pdf';
					return array( 
								"filename" => $filename ,
								"content" => $PDF->Output('',true ) 
								);
				
				}
				return  $PDF->Output("SO_".strval($this->getSalesOrder()).'_sysprod-installation_report.pdf');
			}
			catch(HTML2PDF_exception $e)
			{
				
				return $e->getMessage();
				
			}
			

		}

	}
?>
