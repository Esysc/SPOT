<?php
/** @package Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/Phreezable.php");
require_once("EventcategoryMap.php");

/**
 * EventcategoryDAO provides object-oriented access to the eventCategory table.  This
 * class is automatically generated by ClassBuilder.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * Add any custom business logic to the Model class which is extended from this DAO class.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class EventcategoryDAO extends Phreezable
{
	/** @var string */
	public $Category;

	/** @var string */
	public $Description;


	/**
	 * Returns a dataset of Notifications objects with matching Eventcategory
	 * @param Criteria
	 * @return DataSet
	 */
	public function GetNotificationss($criteria = null)
	{
		return $this->_phreezer->GetOneToMany($this, "category", $criteria);
	}


}
?>