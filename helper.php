<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class ModWhiteleafBookingHelper
{
    public function getRooms()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('*')
              ->from($db->quoteName('#__whiteleaf_rooms'))
              ->where($db->quoteName('published') . ' = 1')
              ->order($db->quoteName('ordering') . ' ASC');
        
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}