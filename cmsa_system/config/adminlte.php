<?php

use App\Providers\AppServiceProvider;

$admin_path = AppServiceProvider::USER_HOME;

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

  'title' => '予算管理支援アプリケーション',
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

  'logo' => '',
  'logo_img' => 'assets/images/img_logo.svg',
  'logo_img_class' => 'brand-image',
  'logo_img_xl' => null,
  'logo_img_xl_class' => 'brand-image-xs',
  'logo_img_alt' => 'ライト工業株式会社',

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
      'path' => 'assets/images/icon.svg',
      'alt' => 'ライト工業株式会社',
      'class' => '',
      'width' => 48,
      'height' => 48,
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
      'path' => 'assets/images/icon.svg',
      'alt' => 'ライト工業株式会社',
      'effect' => 'animation__shake',
      'width' => 60,
      'height' => 60,
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
  'usermenu_header' => false,
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
  'layout_fixed_sidebar' => true, // null -> true
  'layout_fixed_navbar' => null,
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

  'classes_auth_card' => 'card-outline card-primary',
  'classes_auth_header' => '',
  'classes_auth_body' => '',
  'classes_auth_footer' => '',
  'classes_auth_icon' => '',
  'classes_auth_btn' => 'btn-flat btn-primary',

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
  'classes_sidebar_nav' => '',
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
  'sidebar_collapse_remember' => false,
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

  'use_route_url' => false,
  'dashboard_url' => '/',     // home -> admin
  'logout_url' => $admin_path . '/logout', // admin/追加
  'login_url' => $admin_path . '/login', // admin/追加
  'register_url' => '', // $admin_path . '/register', // admin/追加
  // パスワード忘れ メール送信
  'password_email_url' => $admin_path . '/forgot-password/email', // admin/追加
  // パスワード忘れ その他
  'password_reset_url' => $admin_path . '/forgot-password', // admin/追加
  'profile_url' => false,

  /*
  |--------------------------------------------------------------------------
  | Laravel Mix
  |--------------------------------------------------------------------------
  |
  | Here we can enable the Laravel Mix option for the admin panel.
  |
  | For detailed instructions you can look the laravel mix section here:
  | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
  |
  */

  'enabled_laravel_mix' => false,
  'laravel_mix_css_path' => 'css/app.css',
  'laravel_mix_js_path' => 'js/app.js',

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
    [
      'text'    => 'project',
      'icon' => 'nav-icon fa-solid fa-diagram-project',
      'route'  => 'admin.index',
      'active' => [
        $admin_path . '/',
        $admin_path . '/project*',
      ],
      'topnav_right' => true,
    ],
    [
      'text'    => 'free_form',
      'icon' => 'nav-icon fa-solid fa-layer-group',
      'route'  => 'admin.free_form.index',
      'classes'    => 'free-form-nav',
      'active' => [
        $admin_path . '/free_form*',
      ],
      'topnav_right' => true,
    ],
    [
      'type'         => 'fullscreen-widget',
      'topnav_right' => true,
    ],
    // Sidebar items:
    '［メインメニュー］',
    [
      'text'    => 'project',
      'icon' => 'nav-icon fa-solid fa-diagram-project',
      'route'  => 'admin.index',
      'active' => [
        $admin_path . '/',
        $admin_path . '/project*',
      ],
    ],
    [
      'text'    => 'free_form',
      'icon' => 'nav-icon fa-solid fa-layer-group',
      'route'  => 'admin.free_form.index',
      'classes'    => 'free-form-nav',
      'active' => [
        $admin_path . '/free_form*',
      ],
    ],
    [
      'text' => 'mode',
      'icon' => 'nav-icon fas fa-fw fa-share',
      'classes' => 'c-mode-change',
      'submenu' => [
        [
          'text' => 'mode1',
          'icon' => 'nav-icon fa-solid fa-receipt',
          'icon_color' => 'danger',
          'route'  => ['admin.mode.dummy', ['mode' => 'mode1']],
          'classes'    => 'js-mode',
          'data' => [
            'mode-num' => '1',
          ],
          'active' => [
            $admin_path . '/mode1',
          ],
        ],
        [
          'text' => 'mode2',
          'icon' => 'nav-icon fa-solid fa-person-digging',
          'icon_color' => 'warning',
          'route'  => ['admin.mode.dummy', ['mode' => 'mode2']],
          'classes'    => 'js-mode',
          'data' => [
            'mode-num' => '2',
          ],
          'active' => [
            $admin_path . '/mode2',
          ],
        ],
        [
          'text' => 'mode3',
          'icon' => 'nav-icon fa-solid fa-chart-line',
          'icon_color' => 'success',
          'route'  => ['admin.mode.dummy', ['mode' => 'mode3']],
          'classes'    => 'js-mode last-nav-item',
          'data' => [
            'mode-num' => '3',
          ],
          'active' => [
            $admin_path . '/mode3',
          ],
        ],
      ],
    ],
    [
      'text'    => 'mode1',
      'icon' => 'nav-icon fa-solid fa-receipt',
      'icon_color' => 'danger',
      'route'  => ['admin.process.edit', ['all']],
      'classes'    => 'c-mode c-mode-m1',
      'active' => [
        $admin_path . '/process*',
      ],
    ],
    [
      'text'    => 'mode2',
      'icon' => 'nav-icon fa-solid fa-person-digging',
      'icon_color' => 'warning',
      'route'  => 'admin.csv.index',
      'classes'    => 'c-mode c-mode-m2',
      'active' => [
        $admin_path . '/process*',
        $admin_path . '/csv*',
      ],
    ],
    [
      'text'    => 'mode3',
      'icon' => 'nav-icon fa-solid fa-chart-line',
      'icon_color' => 'success',
      'route'  => 'admin.process.index',
      'classes'    => 'c-mode c-mode-m3',
      'active' => [
        $admin_path . '/process*',
      ],
    ],
    [
      'text'    => 'delivery',
      'icon' => 'nav-icon fa-solid fa-truck',
      'route'  => 'admin.delivery.index',
      'classes'    => 'mode2-nav',
      'active' => [
        $admin_path . '/delivery*',
      ],
    ],
    [
      'text'    => 'chart',
      'icon' => 'nav-icon fa-solid fa-chart-simple',
      'route'  => 'admin.chart.index',
      'classes'    => 'mode3-nav',
      'active' => [
        $admin_path . '/chart*',
      ],
    ],
    [
      'text'    => 'reset',
      'icon' => 'nav-icon fa-regular fa-circle-xmark',
      'route'  => 'admin.project.reset',
      'classes'    => 'c-reset',
    ],


    '［設定］',
    [
      'text'    => 'master',
      'icon' => 'nav-icon fa-solid fa-gears',
      'active' => [
        $admin_path . '/master*',
      ],
      'submenu' => [
        [
          'text'    => 'master.expense_item',
          'icon' => 'nav-icon far fa-circle',
          'route'  => 'admin.master.expense_item.index',
          'active' => [
            $admin_path . '/master/expense_item*',
          ],
        ],
        [
          'text'    => 'master.unit',
          'icon' => 'nav-icon far fa-circle',
          'route'  => 'admin.master.unit.index',
          'active' => [
            $admin_path . '/master/unit*',
          ],
        ],
        [
          'text'    => 'master.standard',
          'icon' => 'nav-icon far fa-circle',
          'route'  => 'admin.master.standard.index',
          'active' => [
            $admin_path . '/master/standard*',
          ],
        ],
        [
          'text'    => 'master.ff_default',
          'icon' => 'nav-icon far fa-circle',
          'route'  => 'admin.master.ff_default.index',
          'active' => [
            $admin_path . '/master/ff_default*',
          ],
        ],
        [
          'text'    => 'master.ff_category',
          'icon' => 'nav-icon far fa-circle',
          'route'  => 'admin.master.ff_category.index',
          'active' => [
            $admin_path . '/master/ff_category*',
          ],
        ],
        [
          'text'    => 'master.location',
          'icon' => 'nav-icon far fa-circle',
          'route'  => 'admin.master.location.index',
          'active' => [
            $admin_path . '/master/location*',
          ],
        ],
      ],
    ],
    [
      'text' => 'user.user',
      'icon' => 'nav-icon fas fa-user-cog',
      'route'  => 'admin.user.user.index',
      'can' => 'only_user_manage',
      'active' => [
        $admin_path . '/user/user*',
      ],
    ],
    [
      'text'    => 'user',
      'icon' => 'nav-icon fas fa-users-cog',
      'can' => 'users_manage',
      'active' => [
        $admin_path . '/user*',
      ],
      'submenu' => [
        [
          'text' => 'user.permission',
          'icon' => 'nav-icon fa-solid fa-list-check',
          'route'  => 'admin.user.permission.index',
          'can' => 'permissions_control',
          'active' => [
            $admin_path . '/user/permission*',
          ],
        ],
        [
          'text' => 'user.role',
          'icon' => 'nav-icon fas fa-briefcase',
          'route'  => 'admin.user.role.index',
          'can' => 'roles_control',
          'active' => [
            $admin_path . '/user/role*',
          ],
        ],
        [
          'text' => 'user.user',
          'icon' => 'nav-icon fas fa-user-cog',
          'route'  => 'admin.user.user.index',
          'can' => 'users_control',
          'active' => [
            $admin_path . '/user/user*',
          ],
        ],
      ],
    ],
    [
      'text'    => 'change_password',
      'icon' => 'nav-icon fas fa-key',
      'icon_color' => 'warning',
      'route'  => 'admin.change_password.index',
    ],
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
    //JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
    App\Libs\AdminLte\OriginalLangFilter::class,
    JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
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
    'Datatables' => [
      'active' => true,
      'files' => [
        [
          'type' => 'js',
          'asset' => false,
          'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
        ],
        [
          'type' => 'js',
          'asset' => false,
          'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
        ],
        [
          'type' => 'css',
          'asset' => false,
          'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
        ],
      ],
    ],
    'Select2' => [
      'active' => false,
      'files' => [
        [
          'type' => 'js',
          'asset' => false,
          'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
        ],
        [
          'type' => 'css',
          'asset' => false,
          'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
        ],
      ],
    ],
    'icheck' => [
      'active' => true,
      'files' => [
        [
          'type' => 'css',
          'asset' => false,
          'location' => '//cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css',
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
      'scroll_left' => true,
      'scroll_right' => true,
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
