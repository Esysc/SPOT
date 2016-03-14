<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Jobtostart.php");

/**
 * JobtostartController is the controller class for the Jobtostart object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class JobtostartController extends AppBaseController
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
	 * Displays a list view of Jobtostart objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Jobtostart records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new JobtostartCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Scriptid,Salesorder,Rack,Shelf,Clientaddress,Arguments,Exesequence,Scripttarget,Scriptname,Scriptcontent,Interpreter,Version,Returncode,Returnstdout,Returnstderr,Executionflag,Exectime'
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

				$jobtostarts = $this->Phreezer->Query('Jobtostart',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $jobtostarts->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $jobtostarts->TotalResults;
				$output->totalPages = $jobtostarts->TotalPages;
				$output->pageSize = $jobtostarts->PageSize;
				$output->currentPage = $jobtostarts->CurrentPage;
			}
			else
			{
				// return all results
				$jobtostarts = $this->Phreezer->Query('Jobtostart',$criteria);
				$output->rows = $jobtostarts->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Jobtostart record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('scriptid');
			$jobtostart = $this->Phreezer->Get('Jobtostart',$pk);
			$this->RenderJSON($jobtostart, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Jobtostart record and render response as JSON
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

			$jobtostart = new Jobtostart($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$jobtostart->Scriptid = $this->SafeGetVal($json, 'scriptid');
			$jobtostart->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$jobtostart->Rack = $this->SafeGetVal($json, 'rack');
			$jobtostart->Shelf = $this->SafeGetVal($json, 'shelf');
			$jobtostart->Clientaddress = $this->SafeGetVal($json, 'clientaddress');
			$jobtostart->Arguments = $this->SafeGetVal($json, 'arguments');
			$jobtostart->Exesequence = $this->SafeGetVal($json, 'exesequence');
			$jobtostart->Scripttarget = $this->SafeGetVal($json, 'scripttarget');
			$jobtostart->Scriptname = $this->SafeGetVal($json, 'scriptname');
			$jobtostart->Scriptcontent = $this->SafeGetVal($json, 'scriptcontent');
			$jobtostart->Interpreter = $this->SafeGetVal($json, 'interpreter');
			$jobtostart->Version = $this->SafeGetVal($json, 'version');
			$jobtostart->Returncode = $this->SafeGetVal($json, 'returncode');
			$jobtostart->Returnstdout = $this->SafeGetVal($json, 'returnstdout');
			$jobtostart->Returnstderr = $this->SafeGetVal($json, 'returnstderr');
			$jobtostart->Executionflag = $this->SafeGetVal($json, 'executionflag');
			$jobtostart->Exectime = $this->SafeGetVal($json, 'exectime');

			$jobtostart->Validate();
			$errors = $jobtostart->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				// since the primary key is not auto-increment we must force the insert here
				$jobtostart->Save(true);
				$this->RenderJSON($jobtostart, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Jobtostart record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('scriptid');
			$jobtostart = $this->Phreezer->Get('Jobtostart',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $jobtostart->Scriptid = $this->SafeGetVal($json, 'scriptid', $jobtostart->Scriptid);

			$jobtostart->Salesorder = $this->SafeGetVal($json, 'salesorder', $jobtostart->Salesorder);
			$jobtostart->Rack = $this->SafeGetVal($json, 'rack', $jobtostart->Rack);
			$jobtostart->Shelf = $this->SafeGetVal($json, 'shelf', $jobtostart->Shelf);
			$jobtostart->Clientaddress = $this->SafeGetVal($json, 'clientaddress', $jobtostart->Clientaddress);
			$jobtostart->Arguments = $this->SafeGetVal($json, 'arguments', $jobtostart->Arguments);
			$jobtostart->Exesequence = $this->SafeGetVal($json, 'exesequence', $jobtostart->Exesequence);
			$jobtostart->Scripttarget = $this->SafeGetVal($json, 'scripttarget', $jobtostart->Scripttarget);
			$jobtostart->Scriptname = $this->SafeGetVal($json, 'scriptname', $jobtostart->Scriptname);
			$jobtostart->Scriptcontent = $this->SafeGetVal($json, 'scriptcontent', $jobtostart->Scriptcontent);
			$jobtostart->Interpreter = $this->SafeGetVal($json, 'interpreter', $jobtostart->Interpreter);
			$jobtostart->Version = $this->SafeGetVal($json, 'version', $jobtostart->Version);
			$jobtostart->Returncode = $this->SafeGetVal($json, 'returncode', $jobtostart->Returncode);
			$jobtostart->Returnstdout = $this->SafeGetVal($json, 'returnstdout', $jobtostart->Returnstdout);
			$jobtostart->Returnstderr = $this->SafeGetVal($json, 'returnstderr', $jobtostart->Returnstderr);
			$jobtostart->Executionflag = $this->SafeGetVal($json, 'executionflag', $jobtostart->Executionflag);
			$jobtostart->Exectime = $this->SafeGetVal($json, 'exectime', $jobtostart->Exectime);

			$jobtostart->Validate();
			$errors = $jobtostart->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$jobtostart->Save();
				$this->RenderJSON($jobtostart, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
	 * API Method deletes an existing Jobtostart record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('scriptid');
			$jobtostart = $this->Phreezer->Get('Jobtostart',$pk);

			$jobtostart->Delete();

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
