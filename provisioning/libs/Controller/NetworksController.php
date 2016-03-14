<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Networks.php");

/**
 * NetworksController is the controller class for the Networks object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class NetworksController extends AppBaseController
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
	 * Displays a list view of Networks objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Networks records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new NetworksCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Salesorder,Name,Ip,Mask,Vlanno'
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

				$networkses = $this->Phreezer->Query('Networks',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $networkses->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $networkses->TotalResults;
				$output->totalPages = $networkses->TotalPages;
				$output->pageSize = $networkses->PageSize;
				$output->currentPage = $networkses->CurrentPage;
			}
			else
			{
				// return all results
				$networkses = $this->Phreezer->Query('Networks',$criteria);
				$output->rows = $networkses->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Networks record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$networks = $this->Phreezer->Get('Networks',$pk);
			$this->RenderJSON($networks, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Networks record and render response as JSON
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

			$networks = new Networks($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$networks->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$networks->Name = $this->SafeGetVal($json, 'name');
			$networks->Ip = $this->SafeGetVal($json, 'ip');
			$networks->Mask = $this->SafeGetVal($json, 'mask');
			$networks->Vlanno = $this->SafeGetVal($json, 'vlanno');

			$networks->Validate();
			$errors = $networks->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				// since the primary key is not auto-increment we must force the insert here
				$networks->Save(true);
				$this->RenderJSON($networks, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Networks record and render response as JSON
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
			$networks = $this->Phreezer->Get('Networks',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $networks->Salesorder = $this->SafeGetVal($json, 'salesorder', $networks->Salesorder);

			// this is a primary key.  uncomment if updating is allowed
			// $networks->Name = $this->SafeGetVal($json, 'name', $networks->Name);

			$networks->Ip = $this->SafeGetVal($json, 'ip', $networks->Ip);
			$networks->Mask = $this->SafeGetVal($json, 'mask', $networks->Mask);
			$networks->Vlanno = $this->SafeGetVal($json, 'vlanno', $networks->Vlanno);

			$networks->Validate();
			$errors = $networks->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$networks->Save();
				$this->RenderJSON($networks, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
	 * API Method deletes an existing Networks record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$networks = $this->Phreezer->Get('Networks',$pk);

			$networks->Delete();

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
