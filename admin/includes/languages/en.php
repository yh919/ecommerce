<?php



function lang($phrase) {
    // Words Array
    static $lang = array(
       // Dashboard Page Navbar
    'HOME_ADMIN'    => 'ADMIN PANEL',
    'CATEGORIES'    => 'Sections',
    'ITEMS'         => 'Items',
    'MEMBERS'       => 'Members',
    'ADMIN_NAME'    => "Bunny",
    'EDIT_PROFILE'  => 'Edit Profile',
    'SETTINGS'      => 'Settings',
    'LOGS'          => 'Logs',
    'LOGOUT'        => 'Logout',
    // Edit Member Page Form
    'EDIT_MEMBER' => 'Edit Profile',
    'USERNAME' => 'Username',
    'FULLNAME' => 'Full Name',
    'EMAIL_ADDRESS' => 'Email Address',
    'PASSWORD' => 'Password',
    'VERIFY_PASSWORD' => 'Verify Password',
    'NEW_PASSWORD' => 'New Password',
    'USER_ACCESS' => 'User Access',
    'ADMIN' => 'Admin',
    'MEMBER' => 'Member',
    'TRUSTED_STATUS' => 'Trusted Status',
    'TRUSTED_SELLER' => 'Trusted Seller',
    'NOT_TRUSTED_SELLER' => 'Not Trusted Seller',
    'SUBMIT' => 'Submit',
    'UPDATETEXT' => 'Update',
    'SAVE' => 'Save',
    // Update Page
    'UPDATE_MEMBER' => 'Update Profile',
    'REG_STATUS' => 'Activation Status',
    'REG_ACTIVATED' => 'Activated',
    'REG_NOT_ACTIVATED' => 'Not Activated',
    // Add Member Page
    'ADD_MEMBER' => 'Add Member',
    // Manage Page
    'MANAGE_MEMBERS' => 'Manage Members',
    // Delete Member
    'DELETE_MEMBER' => 'Delete Member',
    // Activation
    'ACTIVATE_MEMBER' => 'Activate Member',
    'DEACTIVATE_MEMBER' => 'Dectivate Member',
    );

    return $lang[$phrase];
}

?>