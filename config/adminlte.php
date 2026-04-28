<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Ayccl Admin Panel',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>Ayccl</b>',
    'logo_img' => 'images/Footer_Logo.png',
    'logo_img_class' => 'brand-image',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xl',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'images/Footer_Logo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            // 'path' => asset('images/Footer_Logo.png'),
            'path' => 'images/Footer_Logo.png',
            // 'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Ayccl Preloader Image',
            'effect' => 'animation__shake',
            'width' => 100,
            'height' => 100,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-olive',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-outline-success',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => 'nav-child-indent',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => true,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => true,
    'laravel_css_path' => 'resources/css/app.css',
    'laravel_js_path' => 'resources/js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        // [
        //     'type' => 'navbar-search',
        //     'text' => 'search',
        //     'topnav_right' => true,
        // ],
        [
            'key' => 'fullscreen-widget',
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        // [
        //     'type' => 'navbar-notification',
        //     'id' => 'my-notification',                // An ID attribute (required).
        //     'icon' => 'fas fa-bell',                  // A font awesome icon (required).
        //     'icon_color' => 'warning',                // The initial icon color (optional).
        //     'label' => 0,                             // The initial label for the badge (optional).
        //     'label_color' => 'danger',                // The initial badge color (optional).
        //     'url' => 'notifications/show',            // The url to access all notifications/elements (required).
        //     'topnav_right' => true,                   // Or "topnav => true" to place on the left (required).
        //     'dropdown_mode' => true,                  // Enables the dropdown mode (optional).
        //     'dropdown_flabel' => 'All notifications', // The label for the dropdown footer link (optional).
        //     'update_cfg' => [
        //         'url' => 'notifications/get',         // The url to periodically fetch new data (optional).
        //         'period' => 30,                       // The update period for get new data (in seconds, optional).
        //     ],
        // ],
        // [
        //     'type' => 'navbar-dropdown',
        //     'text' => '',
        //     'icon' => 'fas fa-language',
        //     'submenu' => [
        // [
        //         'text' => 'english',
        //         'icon' => 'flag-icon flag-icon-us',
        //         'route'  => ['lang.switch', ['lang' => 'en']],
        //         'topnav_right' => true,
        // 'route' => ['adminpanel.lang.switch', ['lang' => 'en']],
        // localizedRoute('lang.switch', ['lang' => 'en'])
        // 'route' => ['lang.switch', ['locale' => 'en', 'lang' => 'en']],
        // ],

        // [
        //     'text' => 'arabic',
        //     'icon' => 'flag-icon flag-icon-eg',
        //     'route' => 'home',
        //     'topnav_right' => true,
        // ],
        //     ],
        // ],

        // Sidebar items:
        // [
        //     'type' => 'sidebar-menu-search',
        //     'text' => 'search',
        // ],
        // [
        //     'text' => 'blog',
        //     'url' => 'admin/blog',
        //     // 'route' => ['admin.profile', ['userID' => '673']],
        //     'can' => 'manage-blog',
        // ],
        // [
        //     'text' => 'pages',
        //     'url' => 'admin/pages',
        //     'icon' => 'far fa-fw fa-file',
        //     'label' => 4,
        //     'label_color' => 'success',
        // ],
        // ['header' => 'account_settings'],
        // [
        //     'text' => 'change_password',
        //     'route' => 'home',
        //     'icon' => 'fas fa-fw fa-lock',
        // ]
        [
            'text' => 'dashboard',
            'icon' => '	fas fa-tachometer-alt',
            'route' => 'home',
            // 'route'  => ['home', ['locale' => app()->getLocale()]],
            'active' => ['dashboard*'],
        ],
        [
            'text' => 'password_change',
            'route' => 'change-password.index',
            'icon' => 'fas fa-fw fa-user',
        ],
        // [
        //     'text' => 'website_management',
        //     'icon' => 'fab fa-chrome',
        //     'submenu' => [
        //         [
        //             'text' => 'website_variables',
        //             'route' => 'webmgt.website_variables',
        //         ],
        //         [
        //             'text' => 'website_logs',
        //             'route' => 'webmgt.website_logs',
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'users_management',
        //     'icon' => 'fas fa-user-cog',
        //     'active' => ['usermgt.*'],
        //     'submenu' => [
        //         [
        //             'text' => 'users_data',
        //             'route' => 'usermgt.users_data',

        //         ],
        //         [
        //             'text' => 'users_privilages',
        //             'route' => 'usermgt.users_privilages',
        //         ],
        //     ],
        // ],

        [
            'text' => 'users_management',
            'icon' => 'fas fa-user-cog',
            'route' => 'users-management.index',
            'active' => ['users-management.*'],
            'roles' => [
                '0'
            ],
        ],
        [
            'text' => 'content_management',
            'icon' => 'fa fa-book',
            'active' => ['content.*'],
            'submenu' => [
                [
                    'icon'=>'fas fa-home',
                    'text' => 'home',
                    'route' => 'home-page.index',
                    'active' => ['*home-page*'],
                ],
                [
                    'icon'=>'fas fa-question-circle',
                    'text' => 'aboutUs',
                    'route' => 'content.aboutUs',
                    'active' => ['*About-Us*'],
                    'submenu' => [
                        [
                            'icon'=>'fas fa-info',
                            'text' => 'aboutCompany',
                            'route' => 'about-company.index',
                            'active' => ['*About-Us/news*'],
                        ],
                        [
                            'icon'=>'fas fa-chair',
                            'text' => 'managementBoard',
                            'route' => 'management-board.index',
                            'active' => ['*About-Us/management-board*'],
                        ],
                        [
                            'icon'=>'fas fa-certificate',
                            'text' => 'visionAndMessage',
                            'route' => 'vision-and-message.index',
                            'active' => ['*About-Us/vision-and-message/*'],
                        ],
                        [
                            'icon'=>'fas fa-eye',
                            'text' => 'futurePlans',
                            'route' => 'future-plans.index',
                            'active' => ['*About-Us/future-plans*'],
                        ],
                        [
                            'icon'=>'fas fa-people-arrows',
                            'text' => 'socialReponsibility',
                            'route' => 'social-reponsibility.index',
                            'active' => ['*About-Us/social-reponsibility*'],
                        ],
                        [
                            'icon'=>'fas fa-medal',
                            'text' => 'prizedAndCertificates',
                            'route' => 'prizes-and-certificates.index',
                            'active' => ['*About-Us/prizes-and-certificates*'],
                        ],
                        [
                            'icon'=>'fas fa-project-diagram',
                            'text' => 'ourProjects',
                            'route' => 'our-projects.index',
                            'active' => ['*About-Us/our-projects*'],
                        ],
                        [
                            'icon'=>'fas fa-leaf',
                            'text' => 'environment',
                            'route' => 'environments.index',
                            'active' => ['*About-Us/environment*'],
                        ],
                    ],
                ],
                [
                    'icon'=>'fas fa-blog',
                    'text' => 'blog',
                    'route' => 'content.blogs',
                    'active' => ['*Blog*'],
                    'submenu' => [
                        [
                            'icon'=>'fas fa-cement',
                            'text' => 'cementBlogs',
                            'route' => 'cement-blogs.index',
                            'active' => ['*Blog/cement-blogs*'],
                        ],
                    ]
                ],
                [
                    'icon'=>'fas fa-file-contract',
                    'text' => 'electronicServices',
                    'route' => 'content.electronicServices',
                    'active' => ['*Electronic-Services*'],
                    'submenu' => [
                        [
                            'icon'=>'far fa-file-word',
                            'text' => 'jobApplication',
                            'route' => 'job-application.index',
                            'active' => ['*Human-Resources./management-board*'],
                        ],
                        [
                            'icon'=>'fas fa-handshake',
                            'text' => 'askForVisit',
                            'route' => 'ask-for-visit.index',
                            'active' => ['*Human-Resources/vision-and-message*'],
                        ],
                        [
                            'icon'=>'fas fa-school',
                            'text' => 'internshipRequest',
                            'route' => 'internship-request.index',
                            'active' => ['*Human-Resources/vision-and-message*'],
                        ],
                    ]
                ],
                [
                    'icon'=>'fas fa-money-bill',
                    'text' => 'salesAndManagement',
                    'route' => 'content.salesAndManagement',
                    'active' => ['*Sales-And-Marketing*'],
                    'submenu' => [
                        [
                            'icon'=>'fas fa-percent',
                            'text' => '100Hadrami',
                            'route' => 'hadhrami.index',
                            'active' => ['*Sales-And-Marketing/hadhrami/*'],
                        ],
                        [
                            'icon'=>'fas fa-box-open',
                            'text' => 'products',
                            'route' => 'products.index',
                            'active' => ['*Sales-And-Marketing/products*'],
                        ],
                        [
                            'icon'=>'fas fa-headphones',
                            'text' => 'customerService',
                            'route' => 'customer-service.index',
                            'active' => ['*Sales-And-Marketing/customer-service*'],
                        ],
                    ],
                ],
                [
                    'icon'=>'fas fa-male',
                    'text' => 'humanResources',
                    'route' => 'content.humanResources',
                    'active' => ['*Human-Resources*'],
                    'submenu' => [
                        [
                            'icon'=>'fas fa-user-tie',
                            'text' => 'employees',
                            'route' => 'employees.index',
                            'active' => ['*Human-Resources/employees*'],
                        ],
                        [
                            'icon'=>'fas fa-user-friends',
                            'text' => 'ourGuests',
                            'route' => 'our-guests.index',
                            'active' => ['*Human-Resources/our-guests*'],
                        ],
                        [
                            'icon'=>'fas fa-people-carry',
                            'text' => 'employeeAdvantages',
                            'route' => 'employee-advantages.index',
                            'active' => ['*Human-Resources/news*'],
                        ],
                        
                    ],
                ],
                [
                    'icon'=>'fab fa-blogger-b',
                    'text' => 'mediaCenter',
                    'active' => ['*Media-Center*'],
                    'submenu' => [
                        [
                            'icon'=>'far fa-newspaper',
                            'text' => 'newsAndActivities',
                            'route' => 'news.index',
                            'active' => ['*Media-Center/news*'],
                        ],
                        [
                            'icon'=>'fas fa-images',
                            'text' => 'photosGalary',
                            'route' => 'photos.index',
                            'active' => ['*Media-Center/photos*'],
                        ],
                        [
                            'icon'=>'fas fa-video',
                            'text' => 'videos',
                            'route' => 'videos.index',
                            'active' => ['*Media-Center/videos*','*Media-Center/categories*' ],
                        ],
                        [
                            'icon'=>'far fa-file-pdf',
                            'text' => 'documents',
                            'route' => 'documents.index',
                            'active' => ['*Media-Center/documents*'],
                        ],
                        [
                            'icon'=>'fas fa-certificate',
                            'text' => 'inspectionCertificates',
                            'route' => 'inspection-certificates.index',
                            'active' => ['*Media-Center/inspection-certificates*'],
                        ],
                        [
                            'icon'=>'fas fa-file-contract',
                            'text' => 'specifications',
                            'route' => 'specifications.index',
                            'active' => ['*Media-Center/specifications*'],
                        ],
                    ],
                ],
                [
                    'icon'=>'fas fa-phone',
                    'text' => 'contactUs',
                    'route' => 'contactus-page.index',
                    'active' => ['*contactus-page*'],
                ],
            ],
        ],
        [
            'icon'=>'fas fa-share-alt',
            'text' => 'externalLinks',
            'route' => 'external-links.index',
            'active' => ['*external-links*'],
        ],
        [
            'icon' => 'fas fa-chart-bar',
            'text' => 'statistics',
            'route' => 'statistics.index',
            'active' => ['*statistics*'],
        ],
        [
            'text' => 'settings',
            'icon' => 'fas fa-cog',
            'active' => ['admin/settings*'],
            'submenu' => [
                [
                    'text' => 'mailSettings',
                    'route' => 'admin.settings.mail',
                    'icon' => 'fas fa-envelope',
                ],
            ],
        ],

        // ['header' => 'labels'],
        // [
        //     'text' => 'important',
        //     'icon_color' => 'red',
        //     'route' => 'home',
        // ],
        // [
        //     'text' => 'warning',
        //     'icon_color' => 'yellow',
        //     'route' => 'home',
        // ],
        // [
        //     'text' => 'information',
        //     'icon_color' => 'cyan',
        //     'route' => 'home',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
        App\Filters\PrivilegesFilter::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'flagIconCss' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '/vendor/flag-icon-css/css/flag-icon.min.css',
                ],
            ],
        ],
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/datatables/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/datatables/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/datatables-plugins/responsive/js/responsive.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '/vendor/datatables/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '/vendor/datatables-plugins/responsive/css/responsive.bootstrap4.min.css',
                ],
            ],
        ],
        'TempusDominusBs4' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/moment/moment.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
                ],
            ],
        ],
        'Summernote' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.css',
                ],
            ],
        ],
        'KrajeeFileinput' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/css/fileinput.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/themes/explorer-fa5/theme.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/js/fileinput.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/themes/fa5/theme.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/themes/explorer-fa5/theme.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/js/plugins/sortable.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/krajee-fileinput/js/locales/es.js',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/select2/js/select2.full.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2/css/select2.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css',
                ],
            ],
        ],

        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'toastr' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/toastr/toastr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '/vendor/toastr/toastr.min.css',
                ],
            ],
        ],
        'sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/sweetalert2/sweetalert2.all.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/sweetalert2/sweetalert2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '/vendor/sweetalert2/sweetalert2.min.css',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => false,
            'scroll_right' => false,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
