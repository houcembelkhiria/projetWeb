<?php

class Property {
    private $property_id;
    private $landlord_id;
    private $title;
    private $description;
    private $location;
    private $rent_amount;
    private $amenities;
    private $photo;

    public function __construct($property_id , $landlord_id, $title, $description, $location, $rent_amount, $amenities, $photo) {
        $this->property_id = $property_id;
        $this->landlord_id = $landlord_id;
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->rent_amount = $rent_amount;
        $this->amenities = $amenities;
        $this->photo = $photo;
    }

    // Getter and setter methods for each property attribute

    public function getPropertyId() {
        return $this->property_id;
    }

    public function setPropertyId($property_id) {
        $this->property_id = $property_id;
    }

    public function getLandlordId() {
        return $this->landlord_id;
    }

    public function setLandlordId($landlord_id) {
        $this->landlord_id = $landlord_id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function getRentAmount() {
        return $this->rent_amount;
    }

    public function setRentAmount($rent_amount) {
        $this->rent_amount = $rent_amount;
    }

    public function getAmenities() {
        return $this->amenities;
    }

    public function setAmenities($amenities) {
        $this->amenities = $amenities;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    // Method to verify if property data is valid (optional)

    // Other methods related to properties can be added as needed

}

?>
