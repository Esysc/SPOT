<?php

/** @package    Customer IP inventory::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");
require_once("chartphp_dist.php");

/**
 * IP_valid_rangesController is the controller class for the IP_valid_ranges object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Customer IP inventory::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ChartsController extends AppBaseController {

    /**
     * Override here for any controller-specific functionality
     *
     * @inheritdocs
     */
    protected function Init() {
        parent::Init();
    }

    public function SubnetView() {
        $this->Render('subnetCalc.tpl');
    }

    public function RandomPass() {
        $this->Render('RandomPass.tpl');
    }

    public function StatsView() {


        $thisYear = $this->GetRouter()->GetUrlParam('YEAR');
        $byMonths = $this->GetRouter()->GetUrlParam('MONTHS');
        if ($byMonths === "")
            $byMonths = "January";



        if ($thisYear === "")
            $thisYear = date('Y');




        $p = new chartphp();
        $p->data_sql = "select os, COUNT(os)
                from provisioningNotifications
                WHERE `startDate` BETWEEN '$thisYear-01-01' AND '$thisYear-12-31'
                group by OS
                order by OS";

        $p->height = '600px';
        $p->xlabel = "        ";
        $p->ylabel = "Qty";
        $p->label = 'OS';
        $p->width = '1000px';
        $p->chart_type = 'bar';
        $p->title = '';
        $p->titleosdistrib = '<center><h3 class="well">Operating System Distribution year  ' . $thisYear . '  (Click to toggle)</h3></center>';
        $this->assign('titleosdistrib', $p->titleosdistrib);
        $this->assign('bar', $p->render('c1'));
        // $p->title = '<p class="well">Operating System Distribution year (percent) ' . $thisYear . '</p>';
        $p->chart_type = "pie";
        $this->assign('pie', $p->render('c2'));



        unset($p);
        $p = new chartphp();
        $p->xlabel = "        ";
        $p->ylabel = "Qty";
        $p->chart_type = "bar";
        $p->height = '600px';
        $p->width = '1000px';
        $p->data_sql = "
            SELECT 
            DATE_FORMAT(startDate, '%M') as 'month',
            
            
            
           COUNT(*)
            
            FROM provisioningNotifications
            WHERE `startDate` BETWEEN '$thisYear-01-01' AND '$thisYear-12-31'
            GROUP BY month
            
            ORDER by startdate";


//$p->data = array(array(3,7,9,1,4,6,8,2,5),array(5,3,8,2,6,2,9,2,6)); 
        $p->title = '<p class="well">Provisioning activity par month year ' . $thisYear . '</p>';
        $this->assign('months', $p->render('c3'));
        $this->assign('viewYear', $thisYear);

        unset($p);
        $p = new chartphp();
        $p->xlabel = "Month & OS Version";
        $p->ylabel = "Qty";
        $p->chart_type = "bar";
        $p->height = '600px';
        $p->width = '1000px';

        $p->direction = 'vertical';
        $p->varyBarColor = true;






        $p->data_sql = "SELECT DATE_FORMAT(startDate, '%M') AS 'month', COUNT(os) ,os
FROM provisioningNotifications
WHERE `startDate` BETWEEN '$thisYear-01-01' AND '$thisYear-12-31' 
GROUP BY month,os
            
            ORDER by startDate
                 ";



        $p->title = '<p class="well">Os Trend  par month year ' . $thisYear . '</p>'
        ;

        $months = $p->render('c4');




        $this->assign('stacked', $months);
        //  var_dump($p);
        unset($p);
        $p = new chartphp();
        $p->title = '<p class="well">Deployment per users ' . $thisYear . '</p>';
        $p->chart_type = "pie";
        $p->height = '600px';
        $p->xlabel = "        ";
        $p->ylabel = "Qty";
        $p->label = 'OS';
        $p->width = '1000px';
        $p->data_sql = "SELECT  USER, COUNT(salesOrder) AS total

FROM tblProgress
WHERE creationDate LIKE  '%$thisYear%'
GROUP BY USER
ORDER BY total,USER";

        $this->assign('users', $p->render('c5'));
        unset($p);
        $p = new chartphp();
        $p->title = '<p class="well">Model distribution ' . $thisYear . '</p>';
        $p->chart_type = "pie";
        $p->height = '600px';
        $p->xlabel = "        ";
        $p->ylabel = "Qty";
        $p->label = 'OS';
        $p->width = '1000px';
        $p->data_sql = "SELECT  model, COUNT(model) AS total
FROM provisioningNotifications
WHERE DATE_FORMAT(startDate, '%Y') LIKE  '%".$thisYear."%'
AND model <>''
GROUP BY model
ORDER BY total";

        $this->assign('models', $p->render('c6'));

        unset($p);
        $p = new chartphp();
        $p->titletimezones = '<center><h3 class="well">TimeZone distribution from the beginning.   (Click to toggle)</h3></center>';

        $p->title = '';
        $p->chart_type = "pie";
        $p->height = '600px';
        //  $p->xlabel = "        ";
        $p->ylabel = "Qty";
        $p->label = 'OS';
        $p->width = '1000px';
        $p->data_sql = "SELECT  timeZone,COUNT(salesOrder) AS total
FROM provisioningAction

GROUP BY timeZone
ORDER BY total";


        $timezones1 = $p->render('c7');

        $p->chart_type = "bar";
        $timezones2 = $p->render('c7bis');
        $this->assign('timezones1', $timezones1);
        $this->assign('timezones2', $timezones2);
        $this->assign('titletimezones', $p->titletimezones);
        $this->Render('StatisticsView.tpl');
        // var_dump($p);
    }

    public function ChartsView() {


        $this->Render();
    }

}

?>
