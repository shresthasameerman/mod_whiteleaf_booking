<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

class WhiteleafBookingController extends BaseController
{
    public function submit()
    {
        // Get the input
        $input = Factory::getApplication()->input;
        $data = $input->getArray(array(
            'check_in' => 'string',
            'check_out' => 'string',
            'room_type' => 'int',
            'guests' => 'int',
            'option' => 'string',
            'task' => 'string'
        ));

        // Validate the data
        if (empty($data['check_in']) || empty($data['check_out']) || empty($data['room_type']) || empty($data['guests'])) {
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

        // Save the booking
        $booking = (object) [
            'room_id' => $data['room_type'],
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'guest_name' => $input->getString('guest_name'),
            'guest_email' => $input->getString('guest_email'),
            'num_adults' => $data['guests'],
            'total_price' => 0, // Calculate total price based on room price and guests
            'booking_status' => 'pending',
            'created' => Factory::getDate()->toSql(),
        ];

        if (!$db->insertObject('#__whiteleaf_bookings', $booking)) {
            Factory::getApplication()->enqueueMessage('Error saving booking', 'error');
            $this->setRedirect(Route::_('index.php?option=com_whiteleafbooking', false));
            return false;
        }

        Factory::getApplication()->enqueueMessage('Booking successfully created', 'message');
        $this->setRedirect(Route::_('index.php?option=com_whiteleafbooking', false));
        return true;
    }
}