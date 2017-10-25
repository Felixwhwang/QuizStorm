<?php

    class ilm_DisableSite {

        /**
         * Plugin Name: Disable Site
         * Plugin URI: http://www.nintencode.com
         * Description: A WordPress plugin to disable your website front-end and display a message to your visitors while still allowing backend access for administrators and authenticated users. This plugin is perfect for displaying maintenance pages, "coming soon" pages, and more.
         * Version: 1.3.1
         *
         * Author: Jimmy K. <jimmy@nintencode.com>
         * Author URI: http://www.nintencode.com
         */

        /* ======================================== */
        /* Variables
        /* ======================================== */

        // Hold the settings.
        public $settings = array();
        public $version = '1.3.1';

        /* ======================================== */
        /* Constructor & Initialization
        /* ======================================== */

        /**
         * The constructor for this plugin.
         *
         * @return void
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function __construct()
        {

            // Set the settings.
            $this->settings['namespace'] = 'ilmds';
            $this->settings['dir'] = plugin_dir_url(__FILE__);
            $this->settings['version'] = $this->version;
            $this->settings['override'] = false;

            // Set default settings.
            $this->settings['html'] = '';

            // HOOKS
            add_action('init', array($this, 'init'), 1);
            add_action('admin_menu', array($this, 'create_menu'), 1);
            add_action('admin_init', array($this, 'register_settings'), 1);

        }

        /**
         * Initialize the plugin.
         *
         * @return void
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function init()
        {

            // Do the header actions.
            $this->do_header_actions();

        }

        /**
         * Do the header actions.
         *
         * @return void
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function do_header_actions()
        {

            // Start the output buffer.
            ob_start(array($this, 'do_footer_actions'));

        }

        /**
         * Do the footer actions. This is where we'll replace the expected
         * output of the page with our HTML.
         *
         * @param string $buffer
         * @return string
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function do_footer_actions($buffer)
        {

            global $disable_site_plugin;

            // Whether or not we're logged in.
            $is_user_logged_in = is_user_logged_in();

            // Whether or not we're an admin.
            $is_user_admin = current_user_can('manage_options');

            // Whether or not we're on the login page.
            $is_login_page = $GLOBALS['pagenow'] == 'wp-login.php';

            // Whether or not we're in the admin ui.
            $is_admin_ui = is_admin();

            // LOGIC:
            // Show the splash page by default.
            // Don't show the splash page if the splash page isn't enabled.
            // Don't show the splash page on the login page or the admin UI.
            // Don't show the splash page if the user is an admin and admin access is enabled.
            // Don't show the splash page if the user is logged in and authorized user access is enabled.
            // Don't show the splash page if the IP address is allowed.

            // Hold so we can test our logic.
            $show_splash_page = true;

            switch (true) {
            case (!$this->is_splash_page_enabled()): // Splash page is not enabled.
            case ($is_login_page || $is_admin_ui): // Is login page or admin UI.
            case ($is_user_admin && $this->is_admin_access_enabled()): // Admin user and access is enabled.
            case ($is_user_logged_in && $this->is_authenticated_user_access_enabled()): // Logged in user and access is enabled.
            case (in_array($_SERVER['REMOTE_ADDR'], $this->get_allowed_ips())): // IP address is allowed.
                $show_splash_page = false;
                break;
            }

            if ($show_splash_page) {

                // Replace the page HTML.
                $buffer = $disable_site_plugin->get_output_html();

                if ($this->is_returning_503()) {
                    // Send a "Temporarily Unavailable" header. For a complete list,
                    // check this list out:
                    // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes#5xx_Server_Error
                    // Thanks to @pembo13 for pointing this out!
                    status_header(503);
                }

                return $buffer;

            }

            return $buffer;

        }

        /* ======================================== */
        /* Admin
        /* ======================================== */

        /**
         * Create the admin menu.
         *
         * @return void
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function create_menu()
        {

            add_menu_page(
                'Disable Site Settings',
                'Disable Site',
                'administrator',
                __FILE__,
                array($this, 'output_settings_page')
            );

        }

        /**
         * Register the settings.
         *
         * @return void
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function register_settings()
        {

            register_setting(
                $this->settings['namespace'] . '-settings-group',
                $this->settings['namespace'] . '_splash_page_enabled'
            );

            register_setting(
                $this->settings['namespace'] . '-settings-group',
                $this->settings['namespace'] . '_admin_access_enabled'
            );

            register_setting(
                $this->settings['namespace'] . '-settings-group',
                $this->settings['namespace'] . '_authenticated_user_access_enabled'
            );

            register_setting(
                $this->settings['namespace'] . '-settings-group',
                $this->settings['namespace'] . '_allowed_ips'
            );

            register_setting(
                $this->settings['namespace'] . '-settings-group',
                $this->settings['namespace'] . '_custom_message'
            );

            register_setting(
                $this->settings['namespace'] . '-settings-group',
                $this->settings['namespace'] . '_custom_html'
            );

            register_setting(
                $this->settings['namespace'] . '-settings-group',
                $this->settings['namespace'] . '_return_503'
            );

        }

        /**
         * Output the settings page.
         *
         * @return void
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function output_settings_page()
        {

            echo '
                <!-- wrap -->
                <div class="wrap">
            ';

            // screen_icon();

            echo '
                <h2>Disable Site</h2>
            ';

            if (isset($_GET['settings-updated'])) {
                echo '
                    <div id="message" class="updated">
                        <p><strong>' . __('Settings saved.') . '</strong></p>
                    </div>
                ';
            }

            echo '
                <form method="post" action="options.php">
            ';

            settings_fields($this->settings['namespace'] . '-settings-group');

            // Get the options.
            $splash_page_enabled = get_option($this->settings['namespace'] . '_splash_page_enabled', 'n') == 'y';
            $admin_access_enabled = get_option($this->settings['namespace'] . '_admin_access_enabled', 'n') == 'y';
            $authorized_user_access_enabled = get_option($this->settings['namespace'] . '_authenticated_user_access_enabled', 'n') == 'y';
            $custom_message_text = get_option($this->settings['namespace'] . '_custom_message', 'This website is currently unavailable.');
            $custom_message_html = get_option($this->settings['namespace'] . '_custom_html', '');
            $allowed_ips = get_option($this->settings['namespace'] . '_allowed_ips', "# These are example IPs. Feel free to remove them.\n192.168.1.0\n192.168.2.0 # Example IP with a note to help you keep track. :)");
            $return_503 = get_option($this->settings['namespace'] . '_return_503', 'y') != 'n';

            echo '
                <table class="form-table">
                <tr valign="top">
                <th scope="row">
                    Enable Splash Page
                </th>
                <td>
                    <select name="' . $this->settings['namespace'] . '_splash_page_enabled">
                        <option value="y" ' . ($splash_page_enabled ? 'selected' : '') . '>Yes</option>
                        <option value="n" ' . (!$splash_page_enabled ? 'selected' : '') . '>No</option>
                    </select>
                    <span class="help">Whether or not the splash page is enabled.</span>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row">
                    Custom Message
                </th>
                <td>
                    <input type="text" name="' . $this->settings['namespace'] . '_custom_message" value="' . $custom_message_text . '" />
                    <span class="help">Use this field to easily change the splash page message. For more control, specify your own custom HTML below.</span>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row">
                    Custom Output HTML
                </th>
                <td>
                    <textarea name="' . $this->settings['namespace'] . '_custom_html">' . $custom_message_html . '</textarea>
                    <span class="help">Type any custom HTML output for your splash page here. (Leave blank for default output.)</span>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row">
                    Admin Access
                </th>
                <td>
                    <select name="' . $this->settings['namespace'] . '_admin_access_enabled">
                        <option value="y" ' . ($admin_access_enabled ? 'selected' : '') . '>Yes</option>
                        <option value="n" ' . (!$admin_access_enabled ? 'selected' : '') . '>No</option>
                    </select>
                    <span class="help">Whether or not administrators can bypass the splash page.</span>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row">
                    Authenticated User Access
                </th>
                <td>
                    <select name="' . $this->settings['namespace'] . '_authenticated_user_access_enabled">
                        <option value="y" ' . ($authorized_user_access_enabled ? 'selected' : '') . '>Yes</option>
                        <option value="n" ' . (!$authorized_user_access_enabled ? 'selected' : '') . '>No</option>
                    </select>
                    <span class="help">Whether or not authenticated users can bypass the splash page.</span>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row">
                    Allowed IPs
                </th>
                <td>
                    <textarea name="' . $this->settings['namespace'] . '_allowed_ips">' . $allowed_ips . '</textarea>
                    <span class="help">The list of IPs that should bypass the splash page. (Enter each IP address on it\'s own line.)</span>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row">
                    Use 503 Response Code
                </th>
                <td>
                    <select name="' . $this->settings['namespace'] . '_return_503">
                        <option value="y" ' . ($return_503 ? 'selected' : '') . '>Yes</option>
                        <option value="n" ' . (!$return_503 ? 'selected' : '') . '>No</option>
                    </select>
                    <span class="help">Whether or not the splash page should return a 503 (temporarily disabled) HTTP response. (Used by search engines. It is recommended that you only change this setting if you know what it does.)</span>
                </td>
                </tr>
                </table>
            ';

            // Output the submit button.
            submit_button();

            echo '
                    </form>
                </div><!-- /wrap -->
            ';

            echo '
                <style type="text/css">

                    .form-table th {
                        font-weight: bold;
                        padding: 15px 0 10px;
                    }

                    .form-table td {
                        padding: 10px 0 5px;
                    }

                    .form-table input[type="text"],
                    .form-table select,
                    .form-table textarea {
                        -moz-box-sizing: border-box;
                        -o-box-sizing: border-box;
                        -webkit-box-sizing: border-box;
                        box-sizing: border-box;
                        line-height: normal;
                    }

                    .form-table input[type="text"] {
                        width: 300px;
                        padding: 3px;
                    }

                    .form-table select {
                        width: 200px;
                        height: auto;
                        padding: 4px 3px 3px;
                    }

                    .form-table textarea {
                        width: 600px;
                        height: 200px;
                        line-height: 14px;
                        padding: 6px;
                    }

                    .form-table .help {
                        max-width: 600px;
                        color: #aaa;
                        display: block;
                        font-size: 11px;
                        font-style: italic;
                        margin-top: 2px;
                    }

                </style>
            ';

        }

        /* ======================================== */
        /* Helper Functions
        /* ======================================== */

        /**
         * Set the HTML to use for the splash page.
         *
         * @param string $value
         * @return void
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function set_output_html($value)
        {

            $this->settings['html'] = $value;

        }

        /**
         * Get the output HTML.
         *
         * @return string
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function get_output_html()
        {

            if (!empty($this->settings['html'])) {
                // Return the custom HTML set by the developer in code.
                return $this->settings['html'];
            }

            // Get the custom HTML.
            $custom_message_html = trim(get_option($this->settings['namespace'] . '_custom_html'));

            if (!empty($custom_message_html)) {
                // Return the custom HTML that the admin entered.
                return $custom_message_html;
            }

            $output = '
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Website Unavailable</title>
                    ' . $this->get_default_output_styles() . '
                </head>
                <body>

                    <!--
                    This temporary page was generated by the Disable Site plugin
                    for WordPress. To disable this temporary page, simply set
                    \'Enable Splash Page\' to \'No\' or deactivate the plugin.
                    -->

                    <!-- table -->
                    <div class="table">
                        <!-- cell -->
                        <div class="cell">
                            ' . __($this->get_custom_message_text()) . '
                        </div><!-- /cell -->
                    </div><!-- /table -->

                </body>
                </html>
            ';

            return $output;

        }

        /**
         * Get the default output styles.
         *
         * @return string
         * @author Jimmy K. <jimmy@orbitmedia.com>
         * @since 1.3.1
         */

        public function get_default_output_styles()
        {

            $output = '
                <style type="text/css" media="all">

                    * {
                        -moz-box-sizing: border-box;
                        -o-box-sizing: border-box;
                        -webkit-box-sizing: border-box;
                        box-sizing: border-box;
                    }

                    html, body {
                        width: 100%;
                        height: 100%;
                    }

                    body {
                        background: #f0f0f0;
                        background-image: linear-gradient(top, #f0f0f0 0%, #fafafa 100%);
                        background-image: -o-linear-gradient(top, #f0f0f0 0%, #fafafa 100%);
                        background-image: -moz-linear-gradient(top, #f0f0f0 0%, #fafafa 100%);
                        background-image: -webkit-linear-gradient(top, #f0f0f0 0%, #fafafa 100%);
                        background-image: -ms-linear-gradient(top, #f0f0f0 0%, #fafafa 100%);
                        color: #888;
                        font-family: Helvetica, Arial, sans-serif;
                        font-size: 32px;
                        font-weight: bold;
                        letter-spacing: -1px;
                        line-height: auto;
                        margin: 0;
                        text-align: center;
                        text-transform: uppercase;
                    }

                    .table {
                        width: 100%;
                        height: 100%;
                        display: table;
                    }

                    .cell {
                        display: table-cell;
                        padding: 50px;
                        vertical-align: middle;
                    }

                </style>
            ';

            return $output;

        }

        /**
         * Get the custom message text.
         *
         * @return void
         * @author Jimmy K. <jimmy@orbitmedia.com>
         * @since 1.1.0
         */

        public function get_custom_message_text()
        {

            return get_option($this->settings['namespace'] . '_custom_message', 'This website is currently unavailable.');

        }

        /**
         * Whether or not the splash page is enabled.
         *
         * @return boolean
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function is_splash_page_enabled()
        {

            return get_option($this->settings['namespace'] . '_splash_page_enabled') == 'y';

        }

        /**
         * Whether or not admin access is enabled.
         *
         * @return boolean
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function is_admin_access_enabled()
        {

            return get_option($this->settings['namespace'] . '_admin_access_enabled') == 'y';

        }

        /**
         * Whether or not authenticated user access is enabled.
         *
         * @return boolean
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.1.0
         */

        public function is_authenticated_user_access_enabled()
        {

            return get_option($this->settings['namespace'] . '_authenticated_user_access_enabled') == 'y';

        }

        /**
         * Whether or not the splash page should return a 503 response code.
         *
         * @return boolean
         * @author Jimmy K. <jimmy@nintencode.com>
         * @since 1.2.1
         */

        public function is_returning_503()
        {

            return get_option($this->settings['namespace'] . '_return_503', 'y') != 'n';

        }

        /**
         * Get the array of allowed IP addresses.
         *
         * @return array
         * @author Jimmy K. <jimmy@orbitmedia.com>
         * @since 1.2.0
         */

        public function get_allowed_ips()
        {

            $sRawValue = get_option($this->settings['namespace'] . '_allowed_ips');

            // Explode the raw data into lines.
            $lines = $this->split_new_lines($sRawValue);

            if (is_array($lines) && count($lines) > 0) {

                foreach ($lines as $k => $v) {

                    if (strpos($v, '#') !== false) {
                        // Remove comments.
                        $lines[$k] = substr($v, 0, strpos($v, '#'));
                    }

                }

                // Trim each line.
                $lines = array_map('trim', $lines);

                // Remove empty elements.
                $lines = array_filter($lines);

                return $lines;

            }

            return array();

        }

        /**
         * Explode a value into an array of lines.
         *
         * @param string $value
         * @return array
         * @author Jimmy K. <jimmy@orbitmedia.com>
         * @since 1.1.0
         */

        private function split_new_lines($value) {

            $value = preg_replace('/\n$/', '', preg_replace('/^\n/', '', preg_replace('/[\r\n]+/', "\n", $value)));
            return explode("\n", $value);

        }

    }

    /* ======================================== */
    /* Initialize
    /* ======================================== */

    // Create an instance of our plugin.
    $disable_site_plugin = new ilm_DisableSite();
