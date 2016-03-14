<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Tempdata.php");

/**
 * TempdataController is the controller class for the Tempdata object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class TempdataController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	/**
	 * Displays a list view of Tempdata objects
	 */
	public function ListView()
	{
		$this->Render();
	}
        public function DhcpView()
        {
            $this->Render('Dhcpmap.tpl');
        }
        public function PxelistView()
        {
           
            $this->Render('Pxelist.tpl');
        }
        public function MemoView()
        {
            $this->Render('MemosListView.tpl');
        }
	/**
	 * API Method queries for Tempdata records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new TempdataCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Salesorder,Data,Status,Timestamps,Message,Creator,Dwprocessed'
				, '%'.$filter.'%')
			);

			// TODO: this is generic query filtering based only on criteria properties
			foreach (array_keys($_REQUEST) as $prop)
			{
				$prop_normal = ucfirst($prop);
				$prop_equals = $prop_normal.'_Equals';

				if (property_exists($criteria, $prop_normal))
				{
					$criteria->$prop_normal = RequestUtil::Get($prop);
				}
				elseif (property_exists($criteria, $prop_equals))
				{
					// this is a convenience so that the _Equals suffix is not needed
					$criteria->$prop_equals = RequestUtil::Get($prop);
				}
			}

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);
                        
			$page = RequestUtil::Get('page');
                        
			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
                                
				//$pagesize = $this->GetDefaultPageSize();
                              
                               $pagesize = 1;
				$tempdatas = $this->Phreezer->Query('Tempdata',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $tempdatas->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $tempdatas->TotalResults;
				$output->totalPages = $tempdatas->TotalPages;
				$output->pageSize = $tempdatas->PageSize;
				$output->currentPage = $tempdatas->CurrentPage;
			}
			else
			{
				// return all results
				$tempdatas = $this->Phreezer->Query('Tempdata',$criteria);
				$output->rows = $tempdatas->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method retrieves a single Tempdata record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$tempdata = $this->Phreezer->Get('Tempdata',$pk);
			$this->RenderJSON($tempdata, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Tempdata record and render response as JSON
	 */
	public function Create()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$tempdata = new Tempdata($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$tempdata->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$tempdata->Data = $this->SafeGetVal($json, 'data');
			$tempdata->Status = $this->SafeGetVal($json, 'status');
			$tempdata->Timestamps = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'timestamps')));
			$tempdata->Message = $this->SafeGetVal($json, 'message');
			$tempdata->Creator = $this->SafeGetVal($json, 'creator');
			$tempdata->Dwprocessed = $this->SafeGetVal($json, 'dwprocessed');

			$tempdata->Validate();
			$errors = $tempdata->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				// since the primary key is not auto-increment we must force the insert here
				$tempdata->Save(true);
				$this->RenderJSON($tempdata, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Tempdata record and render response as JSON
	 */
	public function Update()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$tempdata = $this->Phreezer->Get('Tempdata',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $tempdata->Salesorder = $this->SafeGetVal($json, 'salesorder', $tempdata->Salesorder);

			$tempdata->Data = $this->SafeGetVal($json, 'data', $tempdata->Data);
			$tempdata->Status = $this->SafeGetVal($json, 'status', $tempdata->Status);
			$tempdata->Timestamps = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'timestamps', $tempdata->Timestamps)));
			$tempdata->Message = $this->SafeGetVal($json, 'message', $tempdata->Message);
			$tempdata->Creator = $this->SafeGetVal($json, 'creator', $tempdata->Creator);
			$tempdata->Dwprocessed = $this->SafeGetVal($json, 'dwprocessed', $tempdata->Dwprocessed);

			$tempdata->Validate();
			$errors = $tempdata->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$tempdata->Save();
				$this->RenderJSON($tempdata, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{

			// this table does not have an auto-increment primary key, so it is semantically correct to
			// issue a REST PUT request, however we have no way to know whether to insert or update.
			// if the record is not found, this exception will indicate that this is an insert request
			if (is_a($ex,'NotFoundException'))
			{
				return $this->Create();
			}

			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Tempdata record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$tempdata = $this->Phreezer->Get('Tempdata',$pk);

			$tempdata->Delete();

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
}

?>
