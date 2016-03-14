<?php
/** @package    DRBL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Tblorders.php");

/**
 * TblordersController is the controller class for the Tblorders object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package DRBL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class TblordersController extends AppBaseController
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
	 * Displays a list view of Tblorders objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Tblorders records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new TblordersCriteria();
			 $criteria->SetOrder('Prodenddate', true);
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Salesorder,Programmanager,Siteengineer,Sysprodactor,Release,Comment,Startdate,Enddate,Prodstartdate,Prodenddate,Customer,Timezone,Cctsnapshotpath,Sid,Customersigle,Exported'
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

				$tblorderses = $this->Phreezer->Query('Tblorders',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $tblorderses->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $tblorderses->TotalResults;
				$output->totalPages = $tblorderses->TotalPages;
				$output->pageSize = $tblorderses->PageSize;
				$output->currentPage = $tblorderses->CurrentPage;
			}
			else
			{
				// return all results
				$tblorderses = $this->Phreezer->Query('Tblorders',$criteria);
				$output->rows = $tblorderses->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Tblorders record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$tblorders = $this->Phreezer->Get('Tblorders',$pk);
			$this->RenderJSON($tblorders, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Tblorders record and render response as JSON
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

			$tblorders = new Tblorders($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			$tblorders->Salesorder = $this->SafeGetVal($json, 'salesorder');
			$tblorders->Programmanager = $this->SafeGetVal($json, 'programmanager');
			$tblorders->Siteengineer = $this->SafeGetVal($json, 'siteengineer');
			$tblorders->Sysprodactor = $this->SafeGetVal($json, 'sysprodactor');
			$tblorders->Release = $this->SafeGetVal($json, 'release');
			$tblorders->Comment = $this->SafeGetVal($json, 'comment');
			$tblorders->Startdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'startdate')));
			$tblorders->Enddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'enddate')));
			$tblorders->Prodstartdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'prodstartdate')));
			$tblorders->Prodenddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'prodenddate')));
			$tblorders->Customer = $this->SafeGetVal($json, 'customer');
			$tblorders->Timezone = $this->SafeGetVal($json, 'timezone');
			$tblorders->Cctsnapshotpath = $this->SafeGetVal($json, 'cctsnapshotpath');
			$tblorders->Sid = $this->SafeGetVal($json, 'sid');
			$tblorders->Customersigle = $this->SafeGetVal($json, 'customersigle');
			$tblorders->Exported = $this->SafeGetVal($json, 'exported');

			$tblorders->Validate();
			$errors = $tblorders->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				// since the primary key is not auto-increment we must force the insert here
				$tblorders->Save(true);
				$this->RenderJSON($tblorders, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Tblorders record and render response as JSON
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
			$tblorders = $this->Phreezer->Get('Tblorders',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $tblorders->Salesorder = $this->SafeGetVal($json, 'salesorder', $tblorders->Salesorder);

			$tblorders->Programmanager = $this->SafeGetVal($json, 'programmanager', $tblorders->Programmanager);
			$tblorders->Siteengineer = $this->SafeGetVal($json, 'siteengineer', $tblorders->Siteengineer);
			$tblorders->Sysprodactor = $this->SafeGetVal($json, 'sysprodactor', $tblorders->Sysprodactor);
			$tblorders->Release = $this->SafeGetVal($json, 'release', $tblorders->Release);
			$tblorders->Comment = $this->SafeGetVal($json, 'comment', $tblorders->Comment);
			$tblorders->Startdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'startdate', $tblorders->Startdate)));
			$tblorders->Enddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'enddate', $tblorders->Enddate)));
			$tblorders->Prodstartdate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'prodstartdate', $tblorders->Prodstartdate)));
			$tblorders->Prodenddate = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'prodenddate', $tblorders->Prodenddate)));
			$tblorders->Customer = $this->SafeGetVal($json, 'customer', $tblorders->Customer);
			$tblorders->Timezone = $this->SafeGetVal($json, 'timezone', $tblorders->Timezone);
			$tblorders->Cctsnapshotpath = $this->SafeGetVal($json, 'cctsnapshotpath', $tblorders->Cctsnapshotpath);
			$tblorders->Sid = $this->SafeGetVal($json, 'sid', $tblorders->Sid);
			$tblorders->Customersigle = $this->SafeGetVal($json, 'customersigle', $tblorders->Customersigle);
			$tblorders->Exported = $this->SafeGetVal($json, 'exported', $tblorders->Exported);

			$tblorders->Validate();
			$errors = $tblorders->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$tblorders->Save();
				$this->RenderJSON($tblorders, $this->JSONPCallback(), true, $this->SimpleObjectParams());
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
	 * API Method deletes an existing Tblorders record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('salesorder');
			$tblorders = $this->Phreezer->Get('Tblorders',$pk);

			$tblorders->Delete();

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
