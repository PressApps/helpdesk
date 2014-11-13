<?php
/**
 * Register widgets
 */

function pa_widgets_init() {
  register_widget( 'Most_Popular_Widget' );
  register_widget( 'Custom_Recent_Posts_Widget' );
}

add_action( 'widgets_init', 'pa_widgets_init' );

/**
 * Most popular widget
 */
class Most_Popular_Widget extends WP_Widget {
  public function __construct() {
    parent::WP_Widget( 'most_popular_widget', 'Helpdesk Popular Articles', array( 'description' => 'Display your most popular articles on your sidebar' ) );
  }
  
  public function form( $instance ) {
    $defaults = $this->default_options( $instance );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br />
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $defaults['title']; ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of posts to show:</label><br />
      <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $defaults['number']; ?>" size="3">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'post_type' ); ?>">Choose post type:</label><br />
      <select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
        <option value="all">All post types</option>
        <?php
        $post_types = get_post_types( array( 'public' => true ), 'names' );
        foreach ($post_types as $post_type ) {
          // Exclude attachments
          if ( $post_type == 'attachment' ) continue;
          $defaults['post_type'] == $post_type ? $sel = " selected" : $sel = "";
          echo '<option value="' . $post_type . '"' . $sel . '>' . $post_type . '</option>';
        }
        ?>
      </select>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'timeline' ); ?>">Timeline:</label><br />
      <select id="<?php echo $this->get_field_id( 'timeline' ); ?>" name="<?php echo $this->get_field_name( 'timeline' ); ?>">
        <option value="all_time"<?php if ( $defaults['timeline'] == 'all_time' ) echo "selected"; ?>>All time</option>
        <option value="monthly"<?php if ( $defaults['timeline'] == 'monthly' ) echo "selected"; ?>>Past month</option>
        <option value="weekly"<?php if ( $defaults['timeline'] == 'weekly' ) echo "selected"; ?>>Past week</option>
        <option value="daily"<?php if ( $defaults['timeline'] == 'daily' ) echo "selected"; ?>>Today</option>
      </select>
    </p>
    <?php
  }
  
  private function default_options( $instance ) {
    if ( isset( $instance[ 'title' ] ) )
      $options['title'] = esc_attr( $instance[ 'title' ] );
    else
      $options['title'] = 'Popular posts';
      
    if ( isset( $instance[ 'number' ] ) )
      $options['number'] = (int) $instance[ 'number' ];
    else
      $options['number'] = 5;
    
    if ( isset( $instance[ 'post_type' ] ) )
      $options['post_type'] = esc_attr( $instance[ 'post_type' ] );
    else
      $options['post_type'] = 'all';

    if ( isset( $instance[ 'timeline' ] ) )
      $options['timeline'] = esc_attr( $instance[ 'timeline' ] );
    else
      $options['timeline'] = 'all_time';
    
    return $options;
  }
  
  public function update( $new, $old ) {
    $instance = wp_parse_args( $new, $old );
    return $instance;
  }
  
  public function widget( $args, $instance ) {
    // Find default args
    extract( $args );
    
    // Get our posts
    $defaults     = $this->default_options( $instance );
    $options['limit'] = (int) $defaults[ 'number' ];
    $options['range'] = $defaults['timeline'];

    if ( $defaults['post_type'] != 'all' ) {
      $options['post_type'] = $defaults['post_type'];
    }

    $posts = pa_get_popular( $options );
    
    // Display the widget
    echo $before_widget;
    if ( $defaults['title'] ) echo $before_title . $defaults['title'] . $after_title;
    echo '<ul>';
    global $post;
    foreach ( $posts as $post ):
      setup_postdata( $post );
      ?>
      <li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php echo pa_post_format_icon(); ?><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
      <?php
    endforeach;
    echo '</ul>';
    echo $after_widget;
    
    // Reset post data
    wp_reset_postdata();
  }
}

/**
 * Recent posts widget
 */
class Custom_Recent_Posts_Widget extends WP_Widget {
      
  function __construct() {
      $widget_ops = array(
      'classname'   => 'widget_recent_entries', 
      'description' => __('Display a list of recent article entries from one or more categories.', 'pressapps')
    );
      parent::__construct('custom-recent-posts', __('Helpdesk Recent Posts', 'pressapps'), $widget_ops);
  }


  function widget($args, $instance) {
           
      extract( $args );
    
      $title = apply_filters( 'widget_title', empty($instance['title']) ? 'Recent Posts' : $instance['title'], $instance, $this->id_base);  
      
      if ( ! $number = absint( $instance['number'] ) ) $number = 5;
            
      if( ! $cats = $instance["cats"] )  $cats='';
      
            
      // array to call recent posts.
      
      $crpw_args=array(
               
        'showposts' => $number,
      
        'category__in'=> $cats,
         
       // 'orderby' => 'comment_count'

       // 'post_type' => 'faq'
        );
      
      $crp_widget = null;
      
      $crp_widget = new WP_Query($crpw_args);
      
      
      echo $before_widget;
      
      
      // Widget title
      
      echo $before_title;
      
      echo $instance["title"];
      
      echo $after_title;
      
      
      // Post list in widget
      
      echo "<ul>\n";
      
    while ( $crp_widget->have_posts() )

    {

      $crp_widget->the_post();


    ?>

      <li class="crpw-item">

        <a  href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent link to <?php the_title_attribute(); ?>" class="crpw-title"><?php echo pa_post_format_icon(); ?><?php the_title(); ?></a>
    
      </li>

    <?php

    }

     wp_reset_query();

    echo "</ul>\n";

    echo $after_widget;

  }
  
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
          $instance['cats'] = $new_instance['cats'];
    $instance['number'] = absint($new_instance['number']);
       
            return $instance;
  }
  
  
  function form( $instance ) {
    $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts';
    $number = isset($instance['number']) ? absint($instance['number']) : 5;
    
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'pressapps'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
                  

                        
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'pressapps'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        
        
         <p>
            <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Select categories to include in the recent posts list:', 'pressapps');?> 
            
                <?php
                   $categories=  get_categories('hide_empty=0');
                     echo "<br/>";
                     foreach ($categories as $cat) {
                         $option='<input type="checkbox" id="'. $this->get_field_id( 'cats' ) .'[]" name="'. $this->get_field_name( 'cats' ) .'[]"';
                            if(isset($instance['cats'])) {
                              if (is_array($instance['cats'])) {
                                  foreach ($instance['cats'] as $cats) {
                                      if($cats==$cat->term_id) {
                                           $option=$option.' checked="checked"';
                                      }
                                  }
                              }
                            }
                            $option .= ' value="'.$cat->term_id.'" />';
        
                            $option .= $cat->cat_name;
                            
                            $option .= '<br />';
                            echo $option;
                         }
                    
                    ?>
            </label>
        </p>
        
<?php
  }
}


