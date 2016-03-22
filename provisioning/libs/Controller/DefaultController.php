<?php
/** @package Spot::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");

/**
 * DefaultController is the entry point to the application
 *
 * @package Spot::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class DefaultController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	/**
	 * Display the home page for the application
	 */
	public function Home()
	{
		$this->Render();
	}
        /**
         * Display CVSWEB in iframe
         */
        public function Cvsweb()
        {
            $this->Render('cvsWeb.tpl');
        }
        /*
         * This is for the old DB, where we need for large orders to import values from a csv file
         */
        public function CreateCsv() 
        {
            $this->Render('CreateCsvDb.tpl');
        }
        /**
         * New template to set attr hostname and ip to the new sysprod DB interface
         * It performs ack and assembling too call api sysproddb interface)
         * The data is taken from SPOT provisioning DB
         */
        public function SetAttrSysDB() 
        {
            $this->Render('SetAttrSysDb.tpl');
        }
        /**
         * Template form, to set specifications, ack and assemble (call api sysproddb interface)
         * The data is collected by the form
         */
        public function setSpecsDb() 
        {
            $this->Render('SetSpecsDb.tpl');
        }
        public function setSpecsDbTesting() 
        {
            $this->Render('setSpecsTesting.tpl');
        }
        /**
         * Production monitoring display
         * 
         */
        public function PMon()
        {
            $this->Render('ProductionMonitoring.tpl');
        }
        /**
         * Template form, to set specifications, ack and assemble (call api sysproddb interface)
         * The data is collected by the form
         */
        public function setAssemblyGly() 
        {
            $this->Render('AssembleView.tpl');
        }
        
        
        public function InstallModules() 
        {
            $this->Render('InstallModulesView.tpl');
        }
	/**
	 * Displayed when an invalid route is specified
	 */
	public function Error404()
	{
		$this->Render();
	}

	/**
	 * Display a fatal error message
	 */
	public function ErrorFatal()
	{
		$this->Render();
	}

	public function ErrorApi404()
	{
		$this->RenderErrorJSON('An unknown API endpoint was requested.');
	}

}
?>