<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

class WhiteleafBookingController extends BaseController
{
    public function checkAvailability()
    {
        // Get the input
        $input = Factory::getApplication()->input;
        $data = $input->getArray(array(
            'check_in' => 'string',
            'check_out' => 'string',
            'room_type' => 'int',
            'guests' => 'int',
            'guest_name' => 'string',
            'guest_email' => 'string',
            'option' => 'string',
            'task' => 'string'
        ));

        // Validate the data
        if (empty($data['check_in']) || empty($data['check_out']) || empty($data['room_type']) || empty($data['guests']) || empty($data['guest_name']) || empty($data['guest_email'])) {
            Factory::getApplication()->enqueueMessage('All fields are required', 'error');
            $this->setRedirect(Route::_('index.php?option=com_whiteleafbooking', false));
            return false;
        }

        // Query to check room availability
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__whiteleaf_room_availability'))
            ->where($db->quoteName('room_id') . ' = ' . (int)$data['room_type'])
            ->where($db->quoteName('date') . ' >= ' . $db->quote($data['check_in']))
            ->where($db->quoteName('date') . ' <= ' . $db->quote($data['check_out']))
            ->where($db->quoteName('status') . ' = ' . $db->quote('available'));

        $db->setQuery($query);
        $availableRooms = $db->loadObjectList();

        if (count($availableRooms) < $data['guests']) {
            Factory::getApplication()->enqueueMessage('No rooms available for the selected dates', 'error');
            $this->setRedirect(Route::_('index.php?option=com_whiteleafbooking', false));
            return false;
        }

        // Redirect to confirmation page
        $this->setRedirect(Route::_('index.php?option=com_whiteleafbooking&view=confirmation&check_in=' . $data['check_in'] . '&check_out=' . $data['check_out'] . '&room_type=' . $data['room_type'] . '&guests=' . $data['guests'] . '&guest_name=' . $data['guest_name'] . '&guest_email=' . $data['guest_email'], false));
        return true;
    }
}