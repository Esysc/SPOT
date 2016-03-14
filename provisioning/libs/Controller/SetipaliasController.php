<?php

/** @package    SPOT::Controller */
/**
 * ProductcodesController is the controller class for the Productcodes object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package SPOT::Controller
 * @author ClassBuilder
 * @version 1.0
 */
require_once("AppBaseController.php");
require_once("App/ExampleUser.php");

class SetipaliasController extends AppBaseController {

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
    public function ListView() {
        $this->Render();
    }
    
    

   
}

?>
