<?php
/** @package    Customer IP inventory::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/IP_valid_ranges.php");

/**
 * IP_valid_rangesController is the controller class for the IP_valid_ranges object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Customer IP inventory::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class IP_valid_rangesController extends AppBaseController
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
	 * Displays a list view of IP_valid_ranges objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for IP_valid_ranges records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new IP_valid_rangesCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Start,End,Id'
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

				$ranges = $this->Phreezer->Query('IP_valid_ranges',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $ranges->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $ranges->TotalResults;
				$output->totalPages = $ranges->TotalPages;
				$output->pageSize = $ranges->PageSize;
				$output->currentPage = $ranges->CurrentPage;
			}
			else
			{
				// return all results
				$ranges = $this->Phreezer->Query('IP_valid_ranges',$criteria);
				$output->rows = $ranges->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single IP_valid_ranges record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$ip_valid_ranges = $this->Phreezer->Get('IP_valid_ranges',$pk);
			$this->RenderJSON($ip_valid_ranges, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new IP_valid_ranges record and render response as JSON
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

			$ip_valid_ranges = new IP_valid_ranges($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$ip_valid_ranges->Start = $this->SafeGetVal($json, 'start');
			$ip_valid_ranges->End = $this->SafeGetVal($json, 'end');
			// this is an auto-increment.  uncomment if updating is allowed
			// $ip_valid_ranges->Id = $this->SafeGetVal($json, 'id');


			$ip_valid_ranges->Validate();
			$errors = $ip_valid_ranges->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$ip_valid_ranges->Save();
				$this->RenderJSON($ip_valid_ranges, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing IP_valid_ranges record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('id');
			$ip_valid_ranges = $this->Phreezer->Get('IP_valid_ranges',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			$ip_valid_ranges->Start = $this->SafeGetVal($json, 'start', $ip_valid_ranges->Start);
			$ip_valid_ranges->End = $this->SafeGetVal($json, 'end', $ip_valid_ranges->End);
			// this is a primary key.  uncomment if updating is allowed
			// $ip_valid_ranges->Id = $this->SafeGetVal($json, 'id', $ip_valid_ranges->Id);


			$ip_valid_ranges->Validate();
			$errors = $ip_valid_ranges->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$ip_valid_ranges->Save();
				$this->RenderJSON($ip_valid_ranges, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing IP_valid_ranges record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$ip_valid_ranges = $this->Phreezer->Get('IP_valid_ranges',$pk);

			$ip_valid_ranges->Delete();

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
