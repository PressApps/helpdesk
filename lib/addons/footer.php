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


function my_dynamic_section( $fields ) {

    //$fields = array();
        $fields[] = array(
                        array(
                            'id'       => 'footer_text5',
                            'type'     => 'editor',
                            'title'    => __( 'Footer Text filter', 'redux-framework-demo' ),
                            'default'  => 'Powered by Redux Framework.',
                            'args'     => array(
                                'media_buttons' => false,
                                //'teeny'         => false,
                            ),
                        ),
    );

    return $fields;
}
add_filter('shoestrap_module_menus_options_modifier', 'my_dynamic_section');


