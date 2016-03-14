<?php
/** @package    SPOT::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Orders.php");

/**
 * OrdersController is the controller class for the Orders object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class OrdersController extends AppBaseController
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
	 * Displays a list view of Orders objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Orders records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new OrdersCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Salesorder,Crmuid,Pgm,Ordertitle,Heacronym,Systemtype,Snapavail,Pstartdate,Penddate,Rstartdate,Renddate,Shippmentdate,Status,Polaroidexport,Userid,Commiteddate,Moveorder,Oracleorder,Comments'
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

				$orderses = $this->Phreezer->Query('Orders',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $orderses->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $orderses->TotalResults;
				$output->totalPages = $orderses->TotalPages;
				$output->pageSize = $orderses->PageSize;
				$output->currentPage = $orderses->CurrentPage;
			}
			else
			{
				// return all results
				$orderses = $this->Phreezer->Query('Orders',$criteria);
				$output->rows = $orderses->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Orders record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$orders = $this->Phreezer->Get('Orders',$pk);
			$this->RenderJSON($orders, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Orders record and render response as JSON
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

			$orders = new Orders($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$orders->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$orders->Crmuid = $this->SafeGetVal($json, 'crmuid');
			$orders->Pgm = $this->SafeGetVal($json, 'pgm');
			$orders->Ordertitle = $this->SafeGetVal($json, 'ordertitle');
			$orders->Heacronym = $this->SafeGetVal($json, 'heacronym');
			$orders->Systemtype = $this->SafeGetVal($json, 'systemtype');
			$orders->Snapavail = $this->SafeGetVal($json, 'snapavail');
			$orders->Pstartdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'pstartdate')));
			$orders->Penddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'penddate')));
			$orders->Rstartdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'rstartdate')));
			$orders->Renddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'renddate')));
			$orders->Shippmentdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'shippmentdate')));
			$orders->Status = $this->SafeGetVal($json, 'status');
			$orders->Polaroidexport = $this->SafeGetVal($json, 'polaroidexport');
			$orders->Userid = $this->SafeGetVal($json, 'userid');
			$orders->Commiteddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'commiteddate')));
			$orders->Moveorder = $this->SafeGetVal($json, 'moveorder');
			$orders->Oracleorder = $this->SafeGetVal($json, 'oracleorder');
			$orders->Comments = $this->SafeGetVal($json, 'comments');

			$orders->Validate();
			$errors = $orders->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				// since the primary key is not auto-increment we must force the insert here
				$orders->Save(true);
				$this->RenderJSON($orders, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Orders record and render response as JSON
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
			$orders = $this->Phreezer->Get('Orders',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $orders->Salesorder = $this->SafeGetVal($json, 'salesorder', $orders->Salesorder);

			// this is a primary key.  uncomment if updating is allowed
			// $orders->Crmuid = $this->SafeGetVal($json, 'crmuid', $orders->Crmuid);

			$orders->Pgm = $this->SafeGetVal($json, 'pgm', $orders->Pgm);
			$orders->Ordertitle = $this->SafeGetVal($json, 'ordertitle', $orders->Ordertitle);
			$orders->Heacronym = $this->SafeGetVal($json, 'heacronym', $orders->Heacronym);
			$orders->Systemtype = $this->SafeGetVal($json, 'systemtype', $orders->Systemtype);
			$orders->Snapavail = $this->SafeGetVal($json, 'snapavail', $orders->Snapavail);
			$orders->Pstartdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'pstartdate', $orders->Pstartdate)));
			$orders->Penddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'penddate', $orders->Penddate)));
			$orders->Rstartdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'rstartdate', $orders->Rstartdate)));
			$orders->Renddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'renddate', $orders->Renddate)));
			$orders->Shippmentdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'shippmentdate', $orders->Shippmentdate)));
			$orders->Status = $this->SafeGetVal($json, 'status', $orders->Status);
			$orders->Polaroidexport = $this->SafeGetVal($json, 'polaroidexport', $orders->Polaroidexport);
			$orders->Userid = $this->SafeGetVal($json, 'userid', $orders->Userid);
			$orders->Commiteddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'commiteddate', $orders->Commiteddate)));
			$orders->Moveorder = $this->SafeGetVal($json, 'moveorder', $orders->Moveorder);
			$orders->Oracleorder = $this->SafeGetVal($json, 'oracleorder', $orders->Oracleorder);
			$orders->Comments = $this->SafeGetVal($json, 'comments', $orders->Comments);

			$orders->Validate();
			$errors = $orders->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$orders->Save();
				$this->RenderJSON($orders, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
	 * API Method deletes an existing Orders record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$orders = $this->Phreezer->Get('Orders',$pk);

			$orders->Delete();

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
