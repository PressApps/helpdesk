<?php
/**
 * Return an array of the social links the user has entered.
 * This is simply a helper function for other functions.
 */
function pa_social_links() {
  global $helpdesk;
  // An array of the available networks
  $networks   = array();

  // Started on the new stuff, not done yet.
  $networks[] = array( 'url' => $helpdesk['dribbble_link'],     'icon' => 'dribbble',   'fullname' => 'Dribbble' );
  $networks[] = array( 'url' => $helpdesk['facebook_link'],     'icon' => 'facebook',   'fullname' => 'Facebook' );
  $networks[] = array( 'url' => $helpdesk['flickr_link'],       'icon' => 'flickr',     'fullname' => 'Flickr' );
  $networks[] = array( 'url' => $helpdesk['github_link'],       'icon' => 'github',     'fullname' => 'GitHub' );
  $networks[] = array( 'url' => $helpdesk['google_plus_link'],  'icon' => 'googleplus', 'fullname' => 'Google+' );
  $networks[] = array( 'url' => $helpdesk['email_link'],    'icon' => 'mail',  'fullname' => 'Email' );
  $networks[] = array( 'url' => $helpdesk['linkedin_link'],     'icon' => 'linkedin',   'fullname' => 'LinkedIn' );
  $networks[] = array( 'url' => $helpdesk['pinterest_link'],    'icon' => 'pinterest',  'fullname' => 'Pinterest' );
  $networks[] = array( 'url' => $helpdesk['picassa_link'],       'icon' => 'picassa',     'fullname' => 'Picassa' );
  $networks[] = array( 'url' => $helpdesk['rss_link'],          'icon' => 'feed',        'fullname' => 'RSS' );
  $networks[] = array( 'url' => $helpdesk['skype_link'],        'icon' => 'skype',      'fullname' => 'Skype' );
  $networks[] = array( 'url' => $helpdesk['soundcloud_link'],   'icon' => 'soundcloud', 'fullname' => 'SoundCloud' );
  $networks[] = array( 'url' => $helpdesk['stackoverflow_link'],   'icon' => 'stackoverflow', 'fullname' => 'Stack Overflow' );
  $networks[] = array( 'url' => $helpdesk['wordpress_link'],       'icon' => 'wordpress',     'fullname' => 'WordPress' );
  $networks[] = array( 'url' => $helpdesk['twitter_link'],      'icon' => 'twitter',    'fullname' => 'Twitter' );
  $networks[] = array( 'url' => $helpdesk['vimeo_link'],        'icon' => 'vimeo',      'fullname' => 'Vimeo' );
  $networks[] = array( 'url' => $helpdesk['youtube_link'],      'icon' => 'youtube',    'fullname' => 'YouTube' );

  return $networks;
}

function get_social_bar() {
  global $helpdesk;
  $networks = pa_social_links();
  $social = $helpdesk['footer_social'];
  $html = '';
  if ( $social && ! is_null( $networks ) && count( $networks ) > 0 ) {
    $html .= '<div class="footer-social">';

    foreach ( $networks as $network ) {
      // Check if the social network URL has been defined
      if ( isset( $network['url'] ) && ! empty( $network['url'] ) && strlen( $network['url'] ) > 7 ) {
        $html .= '<a href="' . $network['url'] . '" title="' . $network['fullname'] . '"><i class="si-' . $network['icon'] . '"></i></a>';
      }
    }

    $html .= '</div>';
    return $html;
  }
}


function my_dynamic_section( $sections ) {
    $sections[] = array(
      'title'   => __( 'Footer', 'shoestrap' ),
      'icon' => 'el-icon-website',
      'fields'  => array(
          array(
              'title'       => __( 'Show social icons in footer', 'shoestrap' ),
              'desc'        => __( 'Show social icons in the footer.', 'shoestrap' ),
              'id'          => 'footer_social',
              'type'        => 'switch',
              'default'     => 0,
          ),
      )
    );

    $sections[] = array(
      'title'     => __( 'Social Links', 'shoestrap' ),
      'icon'      => 'el-icon-heart',
      'subsection' => true,
      'fields'  => array(
          array(
              'id'        => 'social_sharing_help_3',
              'title'     => __( 'Social Links used in Menus && Footer. Enter full profile URL. To remove, just leave blank.', 'shoestrap' ),
              'type'      => 'info'
          ),
          array(
              'title'     => __( 'Dribbble', 'shoestrap' ),
              'id'        => 'dribbble_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Facebook', 'shoestrap' ),
              'id'        => 'facebook_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Flickr', 'shoestrap' ),
              'id'        => 'flickr_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'GitHub', 'shoestrap' ),
              'id'        => 'github_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Google+', 'shoestrap' ),
              'id'        => 'google_plus_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Email', 'shoestrap' ),
              'id'        => 'email_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'LinkedIn', 'shoestrap' ),
              'id'        => 'linkedin_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Picassa', 'shoestrap' ),
              'id'        => 'picassa_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Pinterest', 'shoestrap' ),
              'id'        => 'pinterest_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'RSS', 'shoestrap' ),
              'id'        => 'rss_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Skype', 'shoestrap' ),
              'id'        => 'skype_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'SoundCloud', 'shoestrap' ),
              'id'        => 'soundcloud_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Stack Overflow', 'shoestrap' ),
              'id'        => 'stackoverflow_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Twitter', 'shoestrap' ),
              'id'        => 'twitter_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'Vimeo', 'shoestrap' ),
              'id'        => 'vimeo_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => __( 'WordPress', 'shoestrap' ),
              'id'        => 'wordpress_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
          array(
              'title'     => 'YouTube',
              'id'        => 'youtube_link',
              'validate'  => 'url',
              'default'   => '',
              'type'      => 'text'
          ),
      )
    );
    return $sections;
}
add_filter('redux/options/' . OPT_NAME . '/sections', 'my_dynamic_section');







/**
 * other files
 *
 *
 * 
 */


  register_sidebar(array(
    'name'          => __('Footer', 'roots'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<div class="widget %1$s %2$s'. pa_count_widgets( 'sidebar-footer' ) .'">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ));





                        array(
                            'id'       => 'footer_sidebar_border',
                            'type'     => 'border',
                            'title'    => __( 'Sidebar Top Border', 'redux-framework-demo' ),
                            'output'   => array( '.sidebar-footer' ),
                            'left'     => false,
                            'right'     => false,
                            'bottom'     => false,
                            'default'  => array(
                                'border-color'  => '#efefef',
                                'border-style'  => 'solid',
                                'border-top'    => '1px',
                            ),
                        ),
                        array(
                            'id'       => 'footer_sidebar_bg',
                            'type'     => 'color',
                            'title'    => __( 'Sidebar Background Color', 'redux-framework-demo' ),
                            'desc' => __( 'Pick a background color for the footer (default: #dd9933).', 'redux-framework-demo' ),
                            'transparent' => false,
                            'default'  => '#fbfbfb',
                            'validate' => 'color',
                            'mode'     => 'background',
                            'output'   => '.sidebar-footer',
                        ),
                        array(
                            'id'       => 'footer_sidebar_color',
                            'type'     => 'color',
                            'title'    => __( 'Sidebar Text Color', 'redux-framework-demo' ),
                            'desc' => __( 'Pick a header color.', 'redux-framework-demo' ),
                            'default'  => '#84949f',
                            'transparent' => false,
                            'validate' => 'color',
                            'output'   => array( '.content-info, .content-info a, .content-info h3, .content-info a .icon-wrap' ),
                        ),





.footer-social {
  margin-top: 10px;
  i {
    margin: 0 5px;
  }
}

.sidebar-footer {
  padding-top: @section-spacing;
  padding-bottom: @section-spacing;
  border-top: 1px solid @element-border-color;
}
.copyright, .navbar-footer, .footer-social {
  text-align: center;
}
@media (min-width: @screen-md-min) {
  .navbar-footer, .footer-social {
    text-align: right;
    margin-top: 0;
  }
  .copyright {
    text-align: left;
  }
}