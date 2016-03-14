<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Sysprodracksmapping.php");

/**
 * SysprodracksmappingController is the controller class for the Sysprodracksmapping object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class SysprodracksmappingController extends AppBaseController
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
	 * Displays a list view of Sysprodracksmapping objects
	 */
	public function ListView()
	{
            
		$this->Render();
	}

	/**
	 * API Method queries for Sysprodracksmapping records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new SysprodracksmappingCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Rack,Shelf,Cycladesip,Cycladesport,Switchip,Switchport,Bootpip,Clientid'
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

				$sysprodracksmappings = $this->Phreezer->Query('Sysprodracksmapping',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $sysprodracksmappings->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $sysprodracksmappings->TotalResults;
				$output->totalPages = $sysprodracksmappings->TotalPages;
				$output->pageSize = $sysprodracksmappings->PageSize;
				$output->currentPage = $sysprodracksmappings->CurrentPage;
			}
			else
			{
				// return all results
				$sysprodracksmappings = $this->Phreezer->Query('Sysprodracksmapping',$criteria);
				$output->rows = $sysprodracksmappings->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Sysprodracksmapping record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('clientid');
			$sysprodracksmapping = $this->Phreezer->Get('Sysprodracksmapping',$pk);
			$this->RenderJSON($sysprodracksmapping, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Sysprodracksmapping record and render response as JSON
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

			$sysprodracksmapping = new Sysprodracksmapping($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$sysprodracksmapping->Rack = $this->SafeGetVal($json, 'rack');
			$sysprodracksmapping->Shelf = $this->SafeGetVal($json, 'shelf');
			$sysprodracksmapping->Cycladesip = $this->SafeGetVal($json, 'cycladesip');
			$sysprodracksmapping->Cycladesport = $this->SafeGetVal($json, 'cycladesport');
			$sysprodracksmapping->Switchip = $this->SafeGetVal($json, 'switchip');
			$sysprodracksmapping->Switchport = $this->SafeGetVal($json, 'switchport');
			$sysprodracksmapping->Bootpip = $this->SafeGetVal($json, 'bootpip');
			// this is an auto-increment.  uncomment if updating is allowed
			// $sysprodracksmapping->Clientid = $this->SafeGetVal($json, 'clientid');


			$sysprodracksmapping->Validate();
			$errors = $sysprodracksmapping->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$sysprodracksmapping->Save();
				$this->RenderJSON($sysprodracksmapping, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Sysprodracksmapping record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('clientid');
			$sysprodracksmapping = $this->Phreezer->Get('Sysprodracksmapping',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			$sysprodracksmapping->Rack = $this->SafeGetVal($json, 'rack', $sysprodracksmapping->Rack);
			$sysprodracksmapping->Shelf = $this->SafeGetVal($json, 'shelf', $sysprodracksmapping->Shelf);
			$sysprodracksmapping->Cycladesip = $this->SafeGetVal($json, 'cycladesip', $sysprodracksmapping->Cycladesip);
			$sysprodracksmapping->Cycladesport = $this->SafeGetVal($json, 'cycladesport', $sysprodracksmapping->Cycladesport);
			$sysprodracksmapping->Switchip = $this->SafeGetVal($json, 'switchip', $sysprodracksmapping->Switchip);
			$sysprodracksmapping->Switchport = $this->SafeGetVal($json, 'switchport', $sysprodracksmapping->Switchport);
			$sysprodracksmapping->Bootpip = $this->SafeGetVal($json, 'bootpip', $sysprodracksmapping->Bootpip);
			// this is a primary key.  uncomment if updating is allowed
			// $sysprodracksmapping->Clientid = $this->SafeGetVal($json, 'clientid', $sysprodracksmapping->Clientid);


			$sysprodracksmapping->Validate();
			$errors = $sysprodracksmapping->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$sysprodracksmapping->Save();
				$this->RenderJSON($sysprodracksmapping, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Sysprodracksmapping record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('clientid');
			$sysprodracksmapping = $this->Phreezer->Get('Sysprodracksmapping',$pk);

			$sysprodracksmapping->Delete();

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
