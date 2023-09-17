<?php



function lang($phrase) {
    // Words Array
    static $lang = array(
       // Dashboard Page Navbar
    'HOME_ADMIN'    => 'ADMIN PANEL',
    'CATEGORIES'    => 'Categories',
    'ITEMS'         => 'Items',
    'MEMBERS'       => 'Members',
    'SHOPS'       => 'Shops',
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
    // Categories 
    'CATEGORY_NAME' => 'Category Name',
    'CATEGORY_DESCTIPTION' => 'Description',
    'MANAGE_CATEGORIES' => 'Manage Categories',
    'ADD_CATEGORY' => 'Add Category',
    'UPDATE_CATEGORY' => 'Update Category',
    'EDIT_CATEGORY' => 'Edit Category',
    'DELETE_CATEGORY' => 'Delete Category',
    // Items
    'ITEM_NAME' => 'Item Name',
    'ITEM_PRICE' => 'Price',
    'ITEM_CATEGORY' => 'Category',
    'MANAGE_ITEMS' => 'Manage Items',
    'ADD_ITEM' => 'Add Item',
    'UPDATE_ITEM' => 'Update Item',
    'EDIT_ITEM' => 'Edit Item',
    'DELETE_ITEM' => 'Delete Item',
    // Shops
    'MANAGE_SHOPS' => 'Manage Shops',
    'SHOP_NAME' => 'Shop Name',
    'SHOP_DESCRIPTION' => 'Description',
    'SHOP_OWNER' => 'Shop Owner',
    'EDIT_SHOP' => 'Edit Shop',
    'ADD_SHOP' => 'Add Shop',
    'DELETE_SHOP' => 'Delete Shop',
    'ACTIVATE_SHOP' => 'Activate Shop',
    'DEACTIVATE_SHOP' => 'Activate Shop',
    
    );

    return $lang[$phrase];
}

?>