<?php

/** @package    Customer IP inventory::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");


/**
 * IP_valid_rangesController is the controller class for the IP_valid_ranges object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Customer IP inventory::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class SysproddbController extends AppBaseController {

    /**
     * Override here for any controller-specific functionality
     *
     * @inheritdocs
     */
    protected function Init() {
        parent::Init();
    }

    public function FormView() {






        $this->Render('SysproddbView.tpl');
    }
    public function TreeBuilder() {
        $this->Render('TreeBuilderView.tpl');
    }

}

?>
