<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class ModWhiteleafBookingHelper
{
    public static function getRooms()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('id, title, price')
              ->from('#__whiteleaf_rooms')
              ->where('published = 1')
              ->order('title ASC');
              
        $db->setQuery($query);
        
        try {
            return $db->loadObjectList();
        } catch (Exception $e) {
            return array();
        }
    }
}