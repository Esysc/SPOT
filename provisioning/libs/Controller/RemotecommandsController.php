<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Remotecommands.php");

/**
 * RemotecommandsController is the controller class for the Remotecommands object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class RemotecommandsController extends AppBaseController
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
	 * Displays a list view of Remotecommands objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Remotecommands records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new RemotecommandsCriteria();
			$criteria->SetOrder('Remotecommandid',true);
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Remotecommandid,Salesorder,Rack,Shelf,Clientaddress,Arguments,Exesequence,Scriptid,Returncode,Returnstdout,Returnstderr,Executionflag,Logtime,Exectime'
				, '%'.$filter.'%')
			);
                         $clientaddress = RequestUtil::Get('clientaddress');
                        if ($clientaddress) $criteria->AddFilter(
				new CriteriaFilter('Clientaddress'
				, $clientaddress)
			);
                        $executionflag = RequestUtil::Get('executionflag');
                        if ($executionflag) $criteria->AddFilter(
				new CriteriaFilter('Executionflag'
				, '%'.$executionflag.'%')
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
                       // if ($output->orderBy) $criteria->SetOrder($output->orderBy, !$output->orderDesc);
			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$remotecommandses = $this->Phreezer->Query('Remotecommands',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $remotecommandses->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $remotecommandses->TotalResults;
				$output->totalPages = $remotecommandses->TotalPages;
				$output->pageSize = $remotecommandses->PageSize;
				$output->currentPage = $remotecommandses->CurrentPage;
			}
			else
			{
				// return all results
				$remotecommandses = $this->Phreezer->Query('Remotecommands',$criteria);
				$output->rows = $remotecommandses->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Remotecommands record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('remotecommandid');
			$remotecommands = $this->Phreezer->Get('Remotecommands',$pk);
			$this->RenderJSON($remotecommands, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Remotecommands record and render response as JSON
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

			$remotecommands = new Remotecommands($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $remotecommands->Remotecommandid = $this->SafeGetVal($json, 'remotecommandid');

			$remotecommands->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$remotecommands->Rack = $this->SafeGetVal($json, 'rack');
			$remotecommands->Shelf = $this->SafeGetVal($json, 'shelf');
			$remotecommands->Clientaddress = $this->SafeGetVal($json, 'clientaddress');
			$remotecommands->Arguments = $this->SafeGetVal($json, 'arguments');
                        
			$remotecommands->Exesequence = $this->SafeGetVal($json, 'exesequence');
			$remotecommands->Scriptid = $this->SafeGetVal($json, 'scriptid');
			$remotecommands->Returncode = $this->SafeGetVal($json, 'returncode');
			$remotecommands->Returnstdout = $this->SafeGetVal($json, 'returnstdout');
			$remotecommands->Returnstderr = $this->SafeGetVal($json, 'returnstderr');
			$remotecommands->Executionflag = $this->SafeGetVal($json, '');
			$remotecommands->Logtime = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'logtime')));
			$remotecommands->Exectime = $this->SafeGetVal($json, 'exectime');

			$remotecommands->Validate();
			$errors = $remotecommands->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$remotecommands->Save();
				$this->RenderJSON($remotecommands, $this->JSONPCallback(), true, $this->SimpleObjectParams());
                                
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Remotecommands record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('remotecommandid');
			$remotecommands = $this->Phreezer->Get('Remotecommands',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $remotecommands->Remotecommandid = $this->SafeGetVal($json, 'remotecommandid', $remotecommands->Remotecommandid);

			$remotecommands->Salesorder = $this->SafeGetVal($json, 'salesorder', $remotecommands->Salesorder);
			// this is a primary key.  uncomment if updating is allowed
			 $remotecommands->Rack = $this->SafeGetVal($json, 'rack', $remotecommands->Rack);

			// this is a primary key.  uncomment if updating is allowed
			 $remotecommands->Shelf = $this->SafeGetVal($json, 'shelf', $remotecommands->Shelf);

			$remotecommands->Clientaddress = $this->SafeGetVal($json, 'clientaddress', $remotecommands->Clientaddress);
			$remotecommands->Arguments = $this->SafeGetVal($json, 'arguments', $remotecommands->Arguments);
			$remotecommands->Exesequence = $this->SafeGetVal($json, 'exesequence', $remotecommands->Exesequence);
			$remotecommands->Scriptid = $this->SafeGetVal($json, 'scriptid', $remotecommands->Scriptid);
			$remotecommands->Returncode = $this->SafeGetVal($json, 'returncode', $remotecommands->Returncode);
			$remotecommands->Returnstdout = $this->SafeGetVal($json, 'returnstdout', $remotecommands->Returnstdout);
			$remotecommands->Returnstderr = $this->SafeGetVal($json, 'returnstderr', $remotecommands->Returnstderr);
			$remotecommands->Executionflag = $this->SafeGetVal($json, 'executionflag', $remotecommands->Executionflag);
			$remotecommands->Logtime = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'logtime', $remotecommands->Logtime)));
			$remotecommands->Exectime = $this->SafeGetVal($json, 'exectime', $remotecommands->Exectime);

			$remotecommands->Validate();
			$errors = $remotecommands->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$remotecommands->Save();
                                
				$this->RenderJSON($remotecommands, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Remotecommands record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('remotecommandid');
			$remotecommands = $this->Phreezer->Get('Remotecommands',$pk);

			$remotecommands->Delete();

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
