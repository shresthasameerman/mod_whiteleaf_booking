<?php
// /modules/mod_whiteleaf_booking/install/sample_data.php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class WhiteleafSampleDataInstaller
{
    public function installSampleData()
    {
        $db = Factory::getDbo();
        
        // Sample rooms data
        $rooms = [
            [
                'title' => 'Deluxe Garden View',
                'alias' => 'deluxe-garden-view',
                'description' => 'Spacious room with garden view and modern amenities',
                'room_type' => 'deluxe',
                'capacity' => 2,
                'price' => 150.00,
                'base_price' => 150.00,
                'weekend_price' => 180.00,
                'room_size' => '32 sqm',
                'bed_type' => 'King Size',
                'max_adults' => 2,
                'max_children' => 1,
                'published' => 1,
                'created_by' => 1
            ],
            [
                'title' => 'Premium Ocean View',
                'alias' => 'premium-ocean-view',
                'description' => 'Luxurious room with spectacular ocean views',
                'room_type' => 'premium',
                'capacity' => 2,
                'price' => 250.00,
                'base_price' => 250.00,
                'weekend_price' => 300.00,
                'room_size' => '40 sqm',
                'bed_type' => 'King Size',
                'max_adults' => 2,
                'max_children' => 2,
                'published' => 1,
                'created_by' => 1
            ],
            [
                'title' => 'Family Suite',
                'alias' => 'family-suite',
                'description' => 'Perfect for families with separate living area',
                'room_type' => 'suite',
                'capacity' => 4,
                'price' => 350.00,
                'base_price' => 350.00,
                'weekend_price' => 400.00,
                'room_size' => '60 sqm',
                'bed_type' => 'King Size + 2 Singles',
                'max_adults' => 4,
                'max_children' => 2,
                'published' => 1,
                'created_by' => 1
            ]
        ];

        foreach ($rooms as $room) {
            $query = $db->getQuery(true);
            
            // Create columns array
            $columns = array_keys($room);
            
            // Create values array
            $values = array_values($room);
            
            // Prepare the insert query
            $query
                ->insert($db->quoteName('#__whiteleaf_rooms'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', array_map(function($value) use ($db) {
                    return $db->quote($value);
                }, $values)));
            
            try {
                $db->setQuery($query);
                $db->execute();
                echo "Inserted room: " . $room['title'] . "<br>";
            } catch (Exception $e) {
                echo "Error inserting room " . $room['title'] . ": " . $e->getMessage() . "<br>";
            }
        }
        
        echo "Sample data installation completed!";
    }
}

// Run the installer
$installer = new WhiteleafSampleDataInstaller();
$installer->installSampleData();