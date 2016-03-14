<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Provisioning.php");

/**
 * ProvisioningController is the controller class for the Provisioning object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ProvisioningController extends AppBaseController
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
	 * Displays a list view of Provisioning objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Provisioning records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new ProvisioningCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Provisioningid,Salesorder,Rack,Shelf,Clientaddress,Arguments,Exesequence,Scriptid,Returncode,Returnstdout,Returnstderr,Executionflag,Logtime,Exectime,Scriptname,Scriptcontent,Remotecommandid,Interpreter,Version'
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

				$provisionings = $this->Phreezer->Query('Provisioning',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $provisionings->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $provisionings->TotalResults;
				$output->totalPages = $provisionings->TotalPages;
				$output->pageSize = $provisionings->PageSize;
				$output->currentPage = $provisionings->CurrentPage;
			}
			else
			{
				// return all results
				$provisionings = $this->Phreezer->Query('Provisioning',$criteria);
				$output->rows = $provisionings->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Provisioning record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('provisioningid');
			$provisioning = $this->Phreezer->Get('Provisioning',$pk);
			$this->RenderJSON($provisioning, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Provisioning record and render response as JSON
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

			$provisioning = new Provisioning($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$provisioning->Provisioningid = $this->SafeGetVal($json, 'provisioningid');
			$provisioning->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$provisioning->Rack = $this->SafeGetVal($json, 'rack');
			$provisioning->Shelf = $this->SafeGetVal($json, 'shelf');
			$provisioning->Clientaddress = $this->SafeGetVal($json, 'clientaddress');
			$provisioning->Arguments = $this->SafeGetVal($json, 'arguments');
			$provisioning->Exesequence = $this->SafeGetVal($json, 'exesequence');
			$provisioning->Scriptid = $this->SafeGetVal($json, 'scriptid');
			$provisioning->Returncode = $this->SafeGetVal($json, 'returncode');
			$provisioning->Returnstdout = $this->SafeGetVal($json, 'returnstdout');
			$provisioning->Returnstderr = $this->SafeGetVal($json, 'returnstderr');
			$provisioning->Executionflag = $this->SafeGetVal($json, 'executionflag');
			$provisioning->Logtime = $this->SafeGetVal($json, 'logtime');
			$provisioning->Exectime = $this->SafeGetVal($json, 'exectime');
			$provisioning->Scriptname = $this->SafeGetVal($json, 'scriptname');
			$provisioning->Scriptcontent = $this->SafeGetVal($json, 'scriptcontent');
			$provisioning->Remotecommandid = $this->SafeGetVal($json, 'remotecommandid');
			$provisioning->Interpreter = $this->SafeGetVal($json, 'interpreter');
			$provisioning->Version = $this->SafeGetVal($json, 'version');

			$provisioning->Validate();
			$errors = $provisioning->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				// since the primary key is not auto-increment we must force the insert here
				$provisioning->Save(true);
				$this->RenderJSON($provisioning, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Provisioning record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('provisioningid');
			$provisioning = $this->Phreezer->Get('Provisioning',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $provisioning->Provisioningid = $this->SafeGetVal($json, 'provisioningid', $provisioning->Provisioningid);

			$provisioning->Salesorder = $this->SafeGetVal($json, 'salesorder', $provisioning->Salesorder);
			$provisioning->Rack = $this->SafeGetVal($json, 'rack', $provisioning->Rack);
			$provisioning->Shelf = $this->SafeGetVal($json, 'shelf', $provisioning->Shelf);
			$provisioning->Clientaddress = $this->SafeGetVal($json, 'clientaddress', $provisioning->Clientaddress);
			$provisioning->Arguments = $this->SafeGetVal($json, 'arguments', $provisioning->Arguments);
			$provisioning->Exesequence = $this->SafeGetVal($json, 'exesequence', $provisioning->Exesequence);
			$provisioning->Scriptid = $this->SafeGetVal($json, 'scriptid', $provisioning->Scriptid);
			$provisioning->Returncode = $this->SafeGetVal($json, 'returncode', $provisioning->Returncode);
			$provisioning->Returnstdout = $this->SafeGetVal($json, 'returnstdout', $provisioning->Returnstdout);
			$provisioning->Returnstderr = $this->SafeGetVal($json, 'returnstderr', $provisioning->Returnstderr);
			$provisioning->Executionflag = $this->SafeGetVal($json, 'executionflag', $provisioning->Executionflag);
			$provisioning->Logtime = $this->SafeGetVal($json, 'logtime', $provisioning->Logtime);
			$provisioning->Exectime = $this->SafeGetVal($json, 'exectime', $provisioning->Exectime);
			$provisioning->Scriptname = $this->SafeGetVal($json, 'scriptname', $provisioning->Scriptname);
			$provisioning->Scriptcontent = $this->SafeGetVal($json, 'scriptcontent', $provisioning->Scriptcontent);
			$provisioning->Remotecommandid = $this->SafeGetVal($json, 'remotecommandid', $provisioning->Remotecommandid);
			$provisioning->Interpreter = $this->SafeGetVal($json, 'interpreter', $provisioning->Interpreter);
			$provisioning->Version = $this->SafeGetVal($json, 'version', $provisioning->Version);

			$provisioning->Validate();
			$errors = $provisioning->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$provisioning->Save();
				$this->RenderJSON($provisioning, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
	 * API Method deletes an existing Provisioning record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('provisioningid');
			$provisioning = $this->Phreezer->Get('Provisioning',$pk);

			$provisioning->Delete();

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
