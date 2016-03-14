<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Orderslog.php");

/**
 * OrderslogController is the controller class for the Orderslog object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class OrderslogController extends AppBaseController
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
	 * Displays a list view of Orderslog objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Orderslog records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new OrderslogCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Salesorder,Title,Text,Userid,Date'
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

				$orderslogs = $this->Phreezer->Query('Orderslog',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $orderslogs->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $orderslogs->TotalResults;
				$output->totalPages = $orderslogs->TotalPages;
				$output->pageSize = $orderslogs->PageSize;
				$output->currentPage = $orderslogs->CurrentPage;
			}
			else
			{
				// return all results
				$orderslogs = $this->Phreezer->Query('Orderslog',$criteria);
				$output->rows = $orderslogs->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Orderslog record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$orderslog = $this->Phreezer->Get('Orderslog',$pk);
			$this->RenderJSON($orderslog, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Orderslog record and render response as JSON
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

			$orderslog = new Orderslog($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $orderslog->Id = $this->SafeGetVal($json, 'id');

			$orderslog->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$orderslog->Title = $this->SafeGetVal($json, 'title');
			$orderslog->Text = $this->SafeGetVal($json, 'text');
			$orderslog->Userid = $this->SafeGetVal($json, 'userid');
			$orderslog->Date = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'date')));

			$orderslog->Validate();
			$errors = $orderslog->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$orderslog->Save();
				$this->RenderJSON($orderslog, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Orderslog record and render response as JSON
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
			$orderslog = $this->Phreezer->Get('Orderslog',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $orderslog->Id = $this->SafeGetVal($json, 'id', $orderslog->Id);

			$orderslog->Salesorder = $this->SafeGetVal($json, 'salesorder', $orderslog->Salesorder);
			$orderslog->Title = $this->SafeGetVal($json, 'title', $orderslog->Title);
			$orderslog->Text = $this->SafeGetVal($json, 'text', $orderslog->Text);
			$orderslog->Userid = $this->SafeGetVal($json, 'userid', $orderslog->Userid);
			$orderslog->Date = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'date', $orderslog->Date)));

			$orderslog->Validate();
			$errors = $orderslog->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$orderslog->Save();
				$this->RenderJSON($orderslog, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Orderslog record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$orderslog = $this->Phreezer->Get('Orderslog',$pk);

			$orderslog->Delete();

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
