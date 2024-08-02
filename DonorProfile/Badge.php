<?php
require_once('../DonorRegistration/Database.php');


class Badge {
    private $name;
    private $requiredDonations;
    private $imageUnlocked;
    private $imageLocked;
    private $gradientColor;

    public function __construct($name, $requiredDonations, $imageUnlocked, $imageLocked, $gradientColor) {
        $this->name = $name;
        $this->requiredDonations = $requiredDonations;
        $this->imageUnlocked = $imageUnlocked;
        $this->imageLocked = $imageLocked;
        $this->gradientColor = $gradientColor;
    }

    public function getName() {
        return $this->name;
    }

    public function getRequiredDonations() {
        return $this->requiredDonations;
    }

    public function getImageUnlocked() {
        return $this->imageUnlocked;
    }

    public function getImageLocked() {
        return $this->imageLocked;
    }

    public function getGradientColor() {
        return $this->gradientColor;
    }

    public function isUnlocked($donationCount) {
        return $donationCount >= $this->requiredDonations;
    }

    public function getProgress($donationCount) {
        return min(100, ($donationCount / $this->requiredDonations) * 100);
    }

    public function getRemainingDonations($donationCount) {
        return max(0, $this->requiredDonations - $donationCount);
    }

    public static function getAllBadges() {
        return [
            new Badge('Silver', 5, 'Image/Silver.png', 'Image/locked-badge.png', 'linear-gradient(135deg, #B7B7B7, #E8E8E8)'),
            new Badge('Gold', 10, 'Image/Gold.png', 'Image/locked-badge.png', 'linear-gradient(135deg, #FFD700, #FFA500)'),
            new Badge('Platinum', 15, 'Image/Platinum.png', 'Image/locked-badge.png', 'linear-gradient(135deg, #E5E4E2, #A9A9A9)')
        ];
    }
}