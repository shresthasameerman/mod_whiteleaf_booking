<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

// Load jQuery and Bootstrap if needed
HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');
?>

<div class="whiteleaf-booking-module">
    <h3>Book Your Stay at White Leaf Resort</h3>
    <form action="index.php" method="post" id="bookingForm">
        <div class="form-group">
            <label for="check_in">Check-in Date:</label>
            <input type="date" id="check_in" name="check_in" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="check_out">Check-out Date:</label>
            <input type="date" id="check_out" name="check_out" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="room_type">Room Type:</label>
            <select id="room_type" name="room_type" class="form-control" required>
                <option value="">Select Room Type</option>
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo htmlspecialchars($room->id, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($room->title, ENT_QUOTES, 'UTF-8'); ?> - 
                            <?php echo number_format($room->price, 2); ?> per night
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No rooms available</option>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="guests">Number of Guests:</label>
            <select id="guests" name="guests" class="form-control" required>
                <option value="1">1 Guest</option>
                <option value="2">2 Guests</option>
                <option value="3">3 Guests</option>
                <option value="4">4 Guests</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="guest_name">Guest Name:</label>
            <input type="text" id="guest_name" name="guest_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="guest_email">Guest Email:</label>
            <input type="email" id="guest_email" name="guest_email" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Check Availability</button>
        
        <input type="hidden" name="option" value="com_whiteleafbooking">
        <input type="hidden" name="task" value="booking.submit">
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>