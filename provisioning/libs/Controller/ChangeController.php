<?php

/** @package    SPOT::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Tblprogress.php");
require_once('includes/share.php');

/**
 * ChangeController is the controller class for the Tblprogress object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */

/**
 * Get the property name of an object
 */



class ChangeController extends AppBaseController {

    /**
     * Override here for any controller-specific functionality
     *
     * @inheritdocs
     */
    protected function Init() {
        parent::Init();

        // TODO: add controller-wide bootstrap code
        // TODO: if authentiation is required for this entire controller, for example:
        // $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
    }
    
    /**
     * Displays the template
     */
    public function View() {
        $this->Render();
    }
    
    



}

?>
