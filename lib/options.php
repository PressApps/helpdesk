<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux_Framework_sample_config' ) ) {

        class Redux_Framework_sample_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                $this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                // If Redux is running as a plugin, this will remove the demo notice and links
                add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

                // Function to test the compiler hook and demo CSS output.
                // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
                //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);

                // Change the arguments after they've been declared, but before the panel is created
                //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

                // Change the default value of a field after it's been set, but before it's been useds
                //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

                // Dynamically add a section. Can be also used to modify sections/fields
                //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * This is a test function that will let you see when the compiler hook occurs.
             * It only runs if a field    set with compiler=>true is changed.
             * */
            function compiler_action( $options, $css, $changed_values ) {
                echo '<h1>The compiler hook has run!</h1>';
                echo "<pre>";
                print_r( $changed_values ); // Values that have changed since the last save
                echo "</pre>";
                //print_r($options); //Option values
                //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

                /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'redux-framework-demo' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo' ),
                    'icon'   => 'el-icon-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    remove_filter( 'plugin_row_meta', array(
                        ReduxFrameworkPlugin::instance(),
                        'plugin_metalinks'
                    ), null, 2 );

                    // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'redux-framework-demo' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'redux-framework-demo' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'redux-framework-demo' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo $this->theme->display( 'Name' ); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'redux-framework-demo' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'redux-framework-demo' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'redux-framework-demo' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'redux-framework-demo' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'redux-framework-demo' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
                if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
                    Redux_Functions::initWpFilesystem();

                    global $wp_filesystem;

                    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
                }

                // ACTUAL DECLARATION OF SECTIONS
                $this->sections[] = array(
                    'icon'   => 'el-icon-cogs',
                    'title'  => __( 'General Settings', 'redux-framework-demo' ),
                    'fields' => array(
                        array(
                            'id' => 'logo',
                            'type' => 'media', 
                            'title' => __('Logo Upload', 'pressapps' ), 
                            'subtitle' => __('Upload logo image.', 'pressapps' ),
                        ),
                        array(
                            'id' => 'favicon',
                            'type' => 'media', 
                            'title' => __('Favicon Upload', 'pressapps' ), 
                            'subtitle' => __('Upload favicon image.', 'pressapps' ),
                        ),
                        array(
                            'id'       => 'layout',
                            'type'     => 'image_select',
                            'compiler' => true,
                            'title'    => __( 'Main Layout', 'redux-framework-demo' ),
                            'subtitle' => __( 'Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'redux-framework-demo' ),
                            'options'  => array(
                                '1' => array(
                                    'alt' => '1 Column',
                                    'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                                ),
                                '2' => array(
                                    'alt' => '2 Column Left',
                                    'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                                ),
                                '3' => array(
                                    'alt' => '2 Column Right',
                                    'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                                ),
                            ),
                            'default'  => '2'
                        ),
                        array(
                            'id'       => 'opt-textarea',
                            'type'     => 'textarea',
                            'required' => array( 'layout', 'equals', '1' ),
                            'title'    => __( 'Tracking Code', 'redux-framework-demo' ),
                            'subtitle' => __( 'Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'redux-framework-demo' ),
                            'validate' => 'js',
                            'desc'     => 'Validate that it\'s javascript!',
                        ),
                        array(
                            'id'       => 'opt-ace-editor-css',
                            'type'     => 'ace_editor',
                            'title'    => __( 'CSS Code', 'redux-framework-demo' ),
                            'subtitle' => __( 'Paste your CSS code here.', 'redux-framework-demo' ),
                            'mode'     => 'css',
                            'theme'    => 'monokai',
                            'desc'     => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                            'default'  => "#header{\nmargin: 0 auto;\n}"
                        ),
                        array(
                            'id'       => 'footer_text',
                            'type'     => 'editor',
                            'title'    => __( 'Footer Text', 'redux-framework-demo' ),
                            'default'  => 'Powered by Redux Framework.',
                            'args'     => array(
                                'media_buttons' => false,
                                //'teeny'         => false,
                            ),
                        ),
                    )
                );

                $this->sections[] = array(
                    'icon'       => 'el-icon-tint',
                    'title'      => __( 'Styling Options', 'redux-framework-demo' ),
                    'fields'     => array(
                        array(
                            'id'       => 'navbar_link_color',
                            'type'     => 'link_color',
                            'title'    => __( 'Nav Links Color', 'redux-framework-demo' ),
                            'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                            //'regular'   => false, // Disable Regular Color
                            //'hover'     => false, // Disable Hover Color
                            'active'    => false, // Disable Active Color
                            //'visited'   => true,  // Enable Visited Color
                            'default'  => array(
                                'regular' => '#999999',
                                'hover'   => '#439dd0',
                            ),
                            //'output'   => array( '.navbar-default .navbar-nav li > a' ),
                        ),
                        array(
                            'id'       => 'headline_bg',
                            'type'     => 'background',
                            'output'   => array( '.headline' ),
                            'title'    => __( 'Headline Background', 'redux-framework-demo' ),
                            'desc' => __( 'Headline background with image or color.', 'redux-framework-demo' ),
                            'default'  => array(
                                'background-color' => '#f3f3f3',
                            )
                        ),
                        array(
                            'id'       => 'headline_padding',
                            'type'     => 'spacing',
                            'output'   => array( '.headline' ),
                            'mode'     => 'padding',
                            //'all'      => false,
                            'right'         => false,
                            'left'          => false,
                            'units'         => 'px',
                            'title'    => __( 'Headline Padding', 'redux-framework-demo' ),
                            'desc'     => __( 'Default headline top and bottom padding in px.', 'redux-framework-demo' ),
                            'default'  => array(
                                'padding-top'    => '30px',
                                'padding-bottom' => '30px',
                            )
                        ),
                        array(
                            'id'       => 'headline_text',
                            'type'     => 'color',
                            'title'    => __( 'Headline Text Color', 'redux-framework-demo' ),
                            'subtitle' => __( 'Pick a headline text color.', 'redux-framework-demo' ),
                            'default'  => '#aaaaaa',
                            'transparent' => false,
                            'validate' => 'color',
                            'output'    => array(
                                'color'            => '.breadcrumb, .breadcrumb a, .breadcrumb span, .breadcrumb > .active, .breadcrumb > li + li:before, .headline h1, .headline h4'
                            )
                        ),
                        array(
                            'id'       => 'link_color',
                            'type'     => 'link_color',
                            'title'    => __( 'Links Color Option', 'redux-framework-demo' ),
                            'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                            //'regular'   => false, // Disable Regular Color
                            //'hover'     => false, // Disable Hover Color
                            'active'    => false, // Disable Active Color
                            //'visited'   => true,  // Enable Visited Color
                            'default'  => array(
                                'regular' => '#439dd0',
                                'hover'   => '#3d3d3d',
                            ),
                            'output'   => array( '.wrap a' ),
                        ),
                        array(
                            'id'       => 'heading_color',
                            'type'     => 'color',
                            'title'    => __( 'Title Colors', 'redux-framework-demo' ),
                            'desc' => __( 'Pick a header color.', 'redux-framework-demo' ),
                            'default'  => '#83959f',
                            'transparent' => false,
                            'validate' => 'color',
                            'output'   => array( 'h1, h2, h3, h4, h5, h6' ),
                        ),
                        array(
                            'id'       => 'font_header',
                            'type'     => 'typography',
                            'title'    => __( 'Title Font', 'redux-framework-demo' ),
                            'desc' => __( 'Specify the body font properties.', 'redux-framework-demo' ),
                            'google'   => true,
                            'font-size'   => false,
                            'line-height'   => false,
                            'text-align'   => false,
                            'default'  => array(
                                'color'       => '#439dcf',
                                'font-family' => 'Open Sans',
                                'font-weight' => '400',
                            ),
                            'output'   => array( '.entry-title, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a' ),
                        ),
                        array(
                            'id'       => 'font_body',
                            'type'     => 'typography',
                            'title'    => __( 'Body Font', 'redux-framework-demo' ),
                            'desc' => __( 'Specify the body font properties.', 'redux-framework-demo' ),
                            'google'   => true,
                            'default'  => array(
                                'color'       => '#000000',
                                'font-size'   => '14px',
                                'font-family' => 'Open Sans',
                                'font-weight' => '300',
                                'line-height' => '22px'
                            ),
                            'output'   => array( 'body' ),
                        ),
                        array(
                            'id'       => 'secondary_content_color',
                            'type'     => 'color',
                            'title'    => __( 'Secondary Content Colors', 'redux-framework-demo' ),
                            'desc' => __( 'Pick a header color.', 'redux-framework-demo' ),
                            'default'  => '#84949f',
                            'transparent' => false,
                            'validate' => 'color',
                            'output'   => array( '.related, .comments, #respond, .entry-summary, .article-desc, .sidebar' ),
                        ),
                        array(
                            'id'       => 'opt-color-footer',
                            'type'     => 'color',
                            'title'    => __( 'Footer Background Color', 'redux-framework-demo' ),
                            'desc' => __( 'Pick a background color for the footer (default: #dd9933).', 'redux-framework-demo' ),
                            'transparent' => false,
                            'default'  => '#f3f3f3',
                            'validate' => 'color',
                        ),
                    )
                );


                $this->sections[] = array(
                    'icon'       => 'el-icon-file',
                    'title'      => __( 'Knowledge Base', 'redux-framework-demo' ),
                    'fields'     => array(
                        array(
                            'id' => 'headline_search',
                            'type' => 'button_set',
                            'title'       => __( 'Search', 'shoestrap' ),
                            'desc'        => __( 'Display a search form in the headline.', 'shoestrap' ),
                            'options'   => array(
                                '0' => 'Disabled',
                                '1' => 'WP Search',
                                '2' => 'Live Search',
                            ),
                            'default'     => 2,
                        ),
                        array(
                            'id' => 'live_search_in',
                            'type' => 'select',
                            'title' => __('Search Titles / Content', 'pressapps' ), 
                            'desc' => __('Search in post titles only or post titles and content.', 'pressapps' ),
                            'required'    => array('headline_search','=',array('2')),
                            'options' => array(
                                '1' => 'Titles Only',
                                '2' => 'Titles and Content'
                            ), 
                            'default' => '2',
                        ),
                        array(
                            'id'       => 'print',
                            'type'     => 'switch',
                            'title'    => __( 'Print Button', 'redux-framework-demo' ),
                            'desc'     => __( 'Display print button on article page.', 'redux-framework-demo' ),
                            'default'  => '1'
                        ),


                        array(
                            'id'       => 'article_voting',
                            'type'     => 'button_set',
                            'title'    => __( 'Article Voting', 'redux-framework-demo' ),
                            'desc'     => __( 'Allow users to vote on articles.', 'redux-framework-demo' ),
                            'options'  => array(
                                '0' => 'Disabled',
                                '1' => 'Public Voting',
                                '2' => 'Logged In Users Only'
                            ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'related_articles',
                            'type'     => 'button_set',
                            'title'    => __( 'Related Articles', 'redux-framework-demo' ),
                            'desc'     => __( 'Display related articles on single page.', 'redux-framework-demo' ),
                            'options'  => array(
                                '0' => 'Disabled',
                                '1' => 'Related by Tag',
                                '2' => 'Related by Category'
                            ),
                            'default'  => '2'
                        ),
                        array(
                            'id'       => 'article_meta',
                            'type'     => 'checkbox',
                            'title'    => __( 'Display Article Meta', 'redux-framework-demo' ),
                            'desc'     => __( 'Select which aticle meta info to display.', 'redux-framework-demo' ),
                            //Must provide key => value pairs for multi checkbox options
                            'options'  => array(
                                '1' => 'Updated',
                                '2' => 'Author',
                                '3' => 'Category',
                                '4' => 'Tags',
                            ),
                            //See how std has changed? you also don't need to specify opts that are 0.
                            'default'  => array(
                                '1' => '1',
                                '2' => '0',
                                '3' => '0',
                                '4' => '1',
                            )
                        ),
                        array(
                            'id'       => 'contact_text',
                            'type'     => 'editor',
                            'title'    => __( 'Contact Info', 'redux-framework-demo' ),
                            'default'  => '<h3 style="text-align: center;">Need additional help? We are more than happy to help, <a href="#">contact us</a>!</h3>',
                        ),
                        array(
                            'id'       => 'reset_all_votes',
                            'type'     => 'button_set',
                            'title'    => __( 'Reset All Article Votes', 'redux-framework-demo' ),
                            'desc'     => __( 'Reset votes on all articles!.', 'redux-framework-demo' ),
                            'options'  => array(
                                '1' => 'Reset All Votes'
                            ),
                            'multi'    => true,
                            'default'  => '0'
                        ),
                    )
                );

                $this->sections[] = array(
                    'title'  => __( 'Import / Export', 'redux-framework-demo' ),
                    'desc'   => __( 'Import and Export your Redux Framework settings from file, text or URL.', 'redux-framework-demo' ),
                    'icon'   => 'el-icon-refresh',
                    'fields' => array(
                        array(
                            'id'         => 'opt-import-export',
                            'type'       => 'import_export',
                            'title'      => 'Import Export',
                            'subtitle'   => 'Save and restore your Redux options',
                            'full_width' => false,
                        ),
                    ),
                );

            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'redux-framework-demo' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'redux-framework-demo' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'helpdesk',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Options', 'redux-framework-demo' ),
                    'page_title'           => __( 'Theme Options', 'redux-framework-demo' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => true,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => '_options',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );

                // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-docs',
                    'href'   => 'http://docs.reduxframework.com/',
                    'title' => __( 'Documentation', 'redux-framework-demo' ),
                );

                $this->args['admin_bar_links'][] = array(
                    //'id'    => 'redux-support',
                    'href'   => 'https://github.com/ReduxFramework/redux-framework/issues',
                    'title' => __( 'Support', 'redux-framework-demo' ),
                );

                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-extensions',
                    'href'   => 'reduxframework.com/extensions',
                    'title' => __( 'Extensions', 'redux-framework-demo' ),
                );

                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                $this->args['share_icons'][] = array(
                    'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                    'title' => 'Visit us on GitHub',
                    'icon'  => 'el-icon-github'
                    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                    'title' => 'Like us on Facebook',
                    'icon'  => 'el-icon-facebook'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'http://twitter.com/reduxframework',
                    'title' => 'Follow us on Twitter',
                    'icon'  => 'el-icon-twitter'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'http://www.linkedin.com/company/redux-framework',
                    'title' => 'Find us on LinkedIn',
                    'icon'  => 'el-icon-linkedin'
                );

                // Panel Intro text -> before the form
                if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                    if ( ! empty( $this->args['global_variable'] ) ) {
                        $v = $this->args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $this->args['opt_name'] );
                    }
                    $this->args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo' ), $v );
                } else {
                    $this->args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
                }

                // Add content after the form.
                $this->args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo' );
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_sample_config();
    } else {
        echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;
