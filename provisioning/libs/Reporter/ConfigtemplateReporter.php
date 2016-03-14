<?php

/** @package    Spot::Reporter */
/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Configtemplate object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ConfigtemplateReporter extends Reporter {

    // the properties in this class must match the columns returned by GetCustomQuery().
    // 'CustomFieldExample' is an example that is not part of the `configTemplate` table
    public $TargetName;
    public $VersionId;
    public $ConfigTarget;
    public $ConfigTemplate;
    public $TimeStamp;

    /*
     * GetCustomQuery returns a fully formed SQL statement.  The result columns
     * must match with the properties of this reporter object.
     *
     * @see Reporter::GetCustomQuery
     * @param Criteria $criteria
     * @return string SQL statement
     */

    static function GetCustomQuery($criteria) {
        $sql = "select
			`networkEquipment`.`equip_model` as TargetName
			,`configTemplate`.`version_id` as VersionId
			,`configTemplate`.`config_target` as ConfigTarget
			,`configTemplate`.`config_template` as ConfigTemplate
                        ,`configTemplate`.`time_stamp` as TimeStamp
		from `configTemplate`
        inner join networkEquipment on config_target = equip_id";

        // the criteria can be used or you can write your own custom logic.
        // be sure to escape any user input with $criteria->Escape()
        $sql .= $criteria->GetWhere();
        $sql .= $criteria->GetOrder();

        return $sql;
    }

    /*
     * GetCustomCountQuery returns a fully formed SQL statement that will count
     * the results.  This query must return the correct number of results that
     * GetCustomQuery would, given the same criteria
     *
     * @see Reporter::GetCustomCountQuery
     * @param Criteria $criteria


     * @return string SQL statement
     */

    static function GetCustomCountQuery($criteria) {
        $sql = "select count(1) as counter from `configTemplate`";

        // the criteria can be used or you can write your own custom logic.
        // be sure to escape any user input with $criteria->Escape()
        $sql .= $criteria->GetWhere();

        return $sql;
    }

}

?>