<?php
/**
 * @package     JCar.Component
 * @subpackage  Administrator
 *
 * @copyright   Copyright (C) 2015-2017 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Route Table class.
 */
class JCarTableRoute extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  Database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__jcar_routes', 'id', $db);

        $this->setColumnAlias('published', 'state');
    }

    /**
     * Overloaded check function
     *
     * @return  boolean  True on success, false on failure
     *
     * @see     JTable::check
     */
    public function check()
    {
        // Check for valid title
        if (trim($this->title) == '')
        {
            $this->setError(JText::_('COM_JCAR_WARNING_PROVIDE_VALID_TITLE'));

            return false;
        }

        // Verify that the title is unique
        $table = JTable::getInstance('Route', 'JCarTable');

        if ($table->load(array('title'=>$this->title)) && ($table->id != $this->id || $this->id == 0)) {
            $this->setError(JText::_('COM_JCAR_ERROR_UNIQUE_TITLE'));

            return false;
        }

        // Generate a valid alias
        $this->generateAlias();

        return true;
    }

    /**
     * Generate a valid alias from title / date.
     * Remains public to be able to check for duplicated alias before saving
     *
     * @return  string
     */
    public function generateAlias()
    {
        if (empty($this->alias)) {
            $this->alias = $this->title;
        }

        $this->alias = JApplicationHelper::stringURLSafe($this->alias, $this->language);

        if (trim(str_replace('-', '', $this->alias)) == '') {
            $this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
        }

        return $this->alias;
    }
}
