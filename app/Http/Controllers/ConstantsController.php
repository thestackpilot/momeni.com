<?php

namespace App\Http\Controllers;

class ConstantsController extends RootController
{
    // 18cbee9638cb567b44fbcb302afd037b

    const ADMIN_SECRET_STRING = "OPEN SESAME, I'M ADMIN";

    const ADMIN_EMAIL = ["Aliuf@momeni.com","imran.majeed@sparsus.com","info@momeni.com"];

    const WEB_HOOK_EMAIL = ['imran.majeed@sparsus.com'];

    const ORDER_NOTIFICATION = ['Orders@momeni.com'];

    const TEST_EMAIL = ['imran.majeed@sparsus.com'];

    const ALLOWED_DECIMALS = 2;

    const ALLOWED_FILTER_OPERATIONS = [
        'LIKE'     => 'Contains',
        'NOT LIKE' => 'Does not contain',
        '='        => 'Equals'
    ];

    const API_SECRET = '5686fbdff19956769860a66a5522c214';

    const BADGES = [
        'N/A'       => 'badge-dark',
        'INITIATED' => 'badge-info',
        'PROCESSED' => 'badge-primary',
        'DONE'      => 'badge-success',
        'FAILED'    => 'badge-danger'
    ];

    const CACHEABLE = [
        // 'Get_Favourities',
        // 'Get_Designs',
        // 'Get_Collections',
        // 'Get_MainCollections',
        // 'Get_Filters',
        // 'Get_GetHangTagsDetailData'
        // 'Get_Items',
        // 'theme'
    ];

    const CACHE_DURATION = 30;

    const COST_TYPES = [
        'msrp'    => 'MSRP',
        'my-cost' => 'My Cost'
    ];

    const CURRENCY = '$';

    const FORM_TYPES = [
        'profile'        => 'profile',
        'update-cost'    => 'update-cost',
        'update-freight' => 'update-freight'
    ];

    const FTP_DETAILS = [
        'host'     => '122.129.80.188',
        'user'     => 'AshtexFTP',
        'password' => '@shtexftp858$',
        'port'     => '9898'
    ];

    const IMAGE_PLACEHOLDER = '/images/placeholder.jpg?v=1';

    const IMAGE_PLACEHOLDER_FULL = '/images/placeholder-full.jpg?v=1';

    const LISTING_LIMIT = 30;

    const NON_API_MODE = false;

    const NO_FILTER_FLAG = '{"Filters": []}';

    const ORDER_STATUS = [
        'not-applicable' => 'N/A',
        'initiated'      => 'INITIATED',
        'processed'      => 'PROCESSED',
        'done'           => 'DONE',
        'failed'         => 'FAILED'
    ];

    const PAYMENT_GATEWAY = [
        'paytrace' => [
            'base_url'                           => 'https://api.paytrace.com/',
            'auth_url'                           => 'https://api.paytrace.com/oauth/token',
            'client_url'                         => 'https://api.paytrace.com/v1/customer/export',
            'create_client_url'                  => 'https://api.paytrace.com/v1/customer/create',
            'update_client_url'                  => 'https://api.paytrace.com/v1/customer/update',
            'create_client_from_transaction_url' => 'https://api.paytrace.com/v1/customer/create_from_transaction',
            'transaction_url'                    => 'https://api.paytrace.com/v1/transactions/authorization/keyed',
            'customer_transaction_url'           => 'https://api.paytrace.com/v1/transactions/authorization/by_customer',
            'capture_transaction_url'            => 'https://api.paytrace.com/v1/transactions/authorization/capture',
            'void_transaction_url'               => 'https://api.paytrace.com/v1/transactions/void',
            // this should be moved to admin settings
            'integrator_id'                      => '888000002887', // '888000002887',
            'grant_type'                         => 'password',
          //  'user'                               => 'sumit@lrresources.com', // 'adil@ashtexsolutions.com',
          //  'pass'                               => 'Ay$062022', // 'BDTeam!@#321'
        ]
    ];

    const PAYMENT_STATUS = [
        'not-applicable' => 'N/A',
        'initiated'      => 'INITIATED',
        'processed'      => 'PROCESSED',
        'done'           => 'DONE',
        'voided'         => 'VOIDED',
        'failed'         => 'FAILED'
    ];

    const PERMISSIONS = [
        'manage-orders'     => 'Manage Orders',
        'manage-staff'      => 'Manage Staff',
        'manage-invoices'   => 'Manage Invoices',
        'manage-financials' => 'Manage Financials',
        'manage-frieght'    => 'Manage Freight',
        'manage-claims'     => 'Manage Claims'
    ];

    const SPARS_LOGO = '/images/spars.jpeg';

    const THEME_BASE_PATH = "resources/views/frontend";

    const USER_ROLES = [
        'admin'    => 'admin',
        'staff'    => 'staff',
        'customer' => 'customer'
    ];

    const USER_STATUSES = [
        'active'   => 'Active',
        'inactive' => 'In Active'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
