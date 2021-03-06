<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Dhcpbootpinv.php");

/**
 * DhcpbootpinvController is the controller class for the Dhcpbootpinv object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class DhcpbootpinvController extends AppBaseController
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
	 * Displays a list view of Dhcpbootpinv objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Dhcpbootpinv records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new DhcpbootpinvCriteria();
			
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
				$pagesize = $this->GetDefaultPageSize();

				$dhcpbootpinvs = $this->Phreezer->Query('Dhcpbootpinv',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $dhcpbootpinvs->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $dhcpbootpinvs->TotalResults;
				$output->totalPages = $dhcpbootpinvs->TotalPages;
				$output->pageSize = $dhcpbootpinvs->PageSize;
				$output->currentPage = $dhcpbootpinvs->CurrentPage;
			}
			else
			{
				// return all results
				$dhcpbootpinvs = $this->Phreezer->Query('Dhcpbootpinv',$criteria);
				$output->rows = $dhcpbootpinvs->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Dhcpbootpinv record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$dhcpbootpinv = $this->Phreezer->Get('Dhcpbootpinv',$pk);
			$this->RenderJSON($dhcpbootpinv, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Dhcpbootpinv record and render response as JSON
	 */
	public function Create()
	{
		try
		{
			// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$dhcpbootpinv = new Dhcpbootpinv($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$dhcpbootpinv->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$dhcpbootpinv->Data = $this->SafeGetVal($json, 'data');
			$dhcpbootpinv->Status = $this->SafeGetVal($json, 'status');
			$dhcpbootpinv->Timestamps = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'timestamps')));
			$dhcpbootpinv->Message = $this->SafeGetVal($json, 'message');
			$dhcpbootpinv->Creator = $this->SafeGetVal($json, 'creator');
			$dhcpbootpinv->Dwprocessed = $this->SafeGetVal($json, 'dwprocessed');

			$dhcpbootpinv->Validate();
			$errors = $dhcpbootpinv->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				// since the primary key is not auto-increment we must force the insert here
				$dhcpbootpinv->Save(true);
				$this->RenderJSON($dhcpbootpinv, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Dhcpbootpinv record and render response as JSON
	 */
	public function Update()
	{
		try
		{
			// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$dhcpbootpinv = $this->Phreezer->Get('Dhcpbootpinv',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $dhcpbootpinv->Salesorder = $this->SafeGetVal($json, 'salesorder', $dhcpbootpinv->Salesorder);

			$dhcpbootpinv->Data = $this->SafeGetVal($json, 'data', $dhcpbootpinv->Data);
			$dhcpbootpinv->Status = $this->SafeGetVal($json, 'status', $dhcpbootpinv->Status);
			$dhcpbootpinv->Timestamps = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'timestamps', $dhcpbootpinv->Timestamps)));
			$dhcpbootpinv->Message = $this->SafeGetVal($json, 'message', $dhcpbootpinv->Message);
			$dhcpbootpinv->Creator = $this->SafeGetVal($json, 'creator', $dhcpbootpinv->Creator);
			$dhcpbootpinv->Dwprocessed = $this->SafeGetVal($json, 'dwprocessed', $dhcpbootpinv->Dwprocessed);

			$dhcpbootpinv->Validate();
			$errors = $dhcpbootpinv->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$dhcpbootpinv->Save();
				$this->RenderJSON($dhcpbootpinv, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
	 * API Method deletes an existing Dhcpbootpinv record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
			// TODO: views are read-only by default.  uncomment at your own discretion
			throw new Exception('Database views are read-only and cannot be updated');
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$dhcpbootpinv = $this->Phreezer->Get('Dhcpbootpinv',$pk);

			$dhcpbootpinv->Delete();

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
