<?php

if(!is_admin())
    return ;

add_action( 'admin_init'    , 'pa_admin_init' );
add_action( 'admin_menu'    , 'pa_admin_menu' );

function pa_admin_menu(){
    $page = add_menu_page(__('Analytics','pressapps'), __('Analytics','pressapps'), 'manage_options', 'pa_analytics', 'pa_analytics_page','dashicons-chart-bar',63);
    
    add_action('admin_print_scripts-' . $page, 'pa_analytics_enqueue_script');
}

function pa_admin_init(){
    wp_register_script( 'pa_chart_js', get_template_directory_uri() . '/assets/js/vendor/chart.min.js');
}

function pa_analytics_enqueue_script(){
    wp_enqueue_script('pa_chart_js');
}

function pa_get_posts_votes($args = array()){
    
    global $wpdb;
    
    $default_args = array(
        'orderby'                   => 'likes',
        'order'                     => 'DESC',
        'posts_per_page'            => 5,
        'page_no'                   => 1,
    );
    
    $args = array_merge($default_args,$args);
    
    $prefix = ($args['orderby']=='likes')?'B':'C';
    
    $qry['A']  = " SELECT COUNT(ID) as total FROM {$wpdb->posts} ";
    $qry['A'] .= " WHERE post_type = 'post' AND post_status = 'publish'";
    
    $results['A'] = $wpdb->get_row($qry['A']);
    
    $qry['B']  = " SELECT A.ID,A.post_title,B.meta_value as 'likes' , C.meta_value as 'dislikes' ";
    $qry['B'] .= " FROM {$wpdb->posts} A ";
    $qry['B'] .= " LEFT JOIN wp_postmeta B ON (B.post_id = A.ID AND B.meta_key='_votes_likes' ) ";
    $qry['B'] .= " LEFT JOIN wp_postmeta C ON (C.post_id = A.ID AND C.meta_key='_votes_dislikes' ) ";
    $qry['B'] .= " WHERE A.post_type = 'post' AND A.post_status = 'publish' "  ;
    $qry['B'] .= " ORDER BY {$prefix}.meta_value {$args['order']} ";
    $qry['B'] .= " LIMIT " . ($args['page_no']-1)*$args['posts_per_page'] . ",{$args['posts_per_page']} ";
    
    $results['B'] = $wpdb->get_results($qry['B'],ARRAY_A);
    
    for($i=0;$i<count($results['B']);$i++){
        $results['B'][$i]['likes']       =  (empty($results['B'][$i]['likes'])?0:$results['B'][$i]['likes']);
        $results['B'][$i]['dislikes']    =  (empty($results['B'][$i]['dislikes'])?0:$results['B'][$i]['dislikes']);
    }
    
    return array(
        'total'         => $results['A']->total,
        'total_pages'   => ceil($results['A']->total/$args['posts_per_page']),
        'records'       => $results['B'],
    );
    
}

function pa_get_posts_views($args){
    global $wpdb;
    
    $default_args = array(
        'order'                     => 'DESC',
        'posts_per_page'            => 5,
        'page_no'                   => 1,
    );
    
    $args = array_merge($default_args,$args);
    
    $qry['A']  = " SELECT COUNT(ID) as total FROM {$wpdb->posts} ";
    $qry['A'] .= " WHERE post_type = 'post' AND post_status = 'publish'";
    
    $results['A'] = $wpdb->get_row($qry['A']);
    
    $qry['B']  = " SELECT A.ID,A.post_title,B.all_time_stats as 'states' ";
    $qry['B'] .= " FROM {$wpdb->posts} A ";
    $qry['B'] .= " LEFT JOIN {$wpdb->prefix}most_popular B ON (B.post_id = A.ID ) ";
    $qry['B'] .= " WHERE A.post_type = 'post' AND A.post_status = 'publish' "  ;
    $qry['B'] .= " ORDER BY B.all_time_stats {$args['order']} ";
    $qry['B'] .= " LIMIT " . ($args['page_no']-1)*$args['posts_per_page'] . ",{$args['posts_per_page']} ";
    
    $results['B'] = $wpdb->get_results($qry['B'],ARRAY_A);
    
    for($i=0;$i<count($results['B']);$i++){
        $results['B'][$i]['states']       =  (empty($results['B'][$i]['states'])?0:$results['B'][$i]['states']);
    }
    
    return array(
        'total'         => $results['A']->total,
        'total_pages'   => ceil($results['A']->total/$args['posts_per_page']),
        'records'       => $results['B'],
    );
}

function pa_get_search_views($args){
    global $wpdb;
    
    $default_args = array(
        'order'                     => 'DESC',
        'posts_per_page'            => 5,
        'page_no'                   => 1,
        'period'                    => 7,
    );
    
    $args = array_merge($default_args,$args);
    
    $qry['A']  = " SELECT count(DISTINCT(search_term)) as total FROM {$wpdb->prefix}search_terms ";
    if($args['period'] != 'all_time')
        $qry['A'] .=  " WHERE DATEDIFF(CURDATE(),search_time)<={$args['period']}";
    
    
    $results['A'] = $wpdb->get_row($qry['A']);
    
    $qry['B']  = " SELECT search_term,count(id) as total ";
    $qry['B'] .= " FROM {$wpdb->prefix}search_terms ";
    if($args['period'] != 'all_time')
        $qry['B'] .=  " WHERE DATEDIFF(CURDATE(),search_time)<={$args['period']}";
    $qry['B'] .= " GROUP BY search_term ";
    $qry['B'] .= " ORDER BY total {$args['order']} ";
    $qry['B'] .= " LIMIT " . ($args['page_no']-1)*$args['posts_per_page'] . ",{$args['posts_per_page']} ";
    
    $results['B'] = $wpdb->get_results($qry['B'],ARRAY_A);
    
    return array(
        'total'         => $results['A']->total,
        'total_pages'   => ceil($results['A']->total/$args['posts_per_page']),
        'records'       => $results['B'],
    );
}

function pa_get_popular_searches($days = 7,$number_of_terms = 3){
    global $wpdb;
    
    $qry  = " SELECT search_term,count(id) as total ";
    $qry .= " FROM {$wpdb->prefix}search_terms ";
    $qry .= " WHERE DATEDIFF(CURDATE(),search_time)<={$days}";
    $qry .= " GROUP BY search_term ";
    $qry .= " ORDER BY total DESC ";
    $qry .= " LIMIT 0,{$number_of_terms} ";
    
    $results = $wpdb->get_results($qry,ARRAY_A);
    
    return $results;
    
}

function pa_the_popular_searches($days = 7,$number_of_terms = 3){
    global $helpdesk;
    
    if(empty($helpdesk['search_analytics']))
            return ;
    
    $searches = pa_get_popular_searches($days,$number_of_terms); 
    if(count($searches)>0){
        foreach($searches as $search){
            $term[] = "<a href='" . esc_url(home_url('?s=' . $search['search_term'] )) . "'>{$search['search_term']}</a>";
        }
        echo implode(",", $term);
    }
}

function pa_analytics_page(){
    global $helpdesk;
    $current_tab            = isset($_REQUEST['pa_analytics_case'])?$_REQUEST['pa_analytics_case']:'votes';
    $page_no                = isset($_REQUEST['page_no'])?$_REQUEST['page_no']:1;
    $current_period         = isset($_REQUEST['pa_analytics_period'])?$_REQUEST['pa_analytics_period']:'all_time';
    $has_sufficient_data    = FALSE;
    $is_period_enable       = FALSE;
    
    $col = $labels =  $datasets = array();
    
    $tabs = array(
        'votes'             => __('Votes'       ,'pressapps'),
        'views'             => __('Views'       ,'pressapps'),
    );
    
    if($helpdesk['search_analytics'] == 1){
        $tabs['searches'] = __('Searches'    ,'pressapps');
    }
    
    switch($current_tab){
        case 'votes':
            
            $order_options = array(
                'likes'        => __('Most Liked'      ,'pressapps'),
                'dislikes'     => __('Most Disliked'   ,'pressapps'),
            );
            
            $current_option = ((isset($_REQUEST['pa_analytics_tab_option']))?$_REQUEST['pa_analytics_tab_option']:'likes');
            $is_disliked    = ($current_option == 'dislikes')?TRUE:FALSE;
            $chart_title    = (!$is_disliked)?__('Most Liked Articles','pressapps'):__('Most Disliked Articles','pressapps');
            $args           = array(
                'orderby'   => ($is_disliked)?'dislikes':'likes',
                'page_no'   => $page_no,
            );           
            $query_args     = array(
                'page'                      => 'pa_analytics',
                'pa_analytics_tab_option'   => $current_option,
            );
            
            $datas      = pa_get_posts_votes($args);
            
            if($datas['total']>0){
            
                foreach($datas['records'] as $data){
                    $labels[]               = $data['post_title'];
                    $col[0]['data'][]       = $data['likes'];
                    $col[1]['data'][]       = $data['dislikes'];
                }

                $datasets[] = array(
                    'label'                   =>  __('Likes','pressapps'),
                    'fillColor'               => "rgba(220,220,220,0.2)",
                    'strokeColor'             => "rgba(220,220,220,1)",
                    'pointColor'              => "rgba(220,220,220,1)",
                    'pointStrokeColor'        => "#fff",
                    'pointHighlightFill'      => "#fff",
                    'pointHighlightStroke'    => "rgba(220,220,220,1)",
                    'data'                    => $col[0]['data'],
                );

                $datasets[] = array(
                    'label'                   =>  __('Dislikes','pressapps'),
                    'fillColor'               => "rgba(151,187,205,0.2)",
                    'strokeColor'             => "rgba(151,187,205,1)",
                    'pointColor'              => "rgba(151,187,205,1)",
                    'pointStrokeColor'        => "#fff",
                    'pointHighlightFill'      => "#fff",
                    'pointHighlightStroke'    => "rgba(151,187,205,1)",
                    'data'                    => $col[1]['data'],
                );
                $has_sufficient_data = TRUE;
            }else{
                $has_sufficient_data = FALSE;
            }
                
            
            break;
        case 'views':
            
            $order_options = array(
                'ASC'        => __('Low => High'        ,'pressapps'),
                'DESC'       => __('High => Low'        ,'pressapps'),
            );
            
            $current_option     = ((isset($_REQUEST['pa_analytics_tab_option']))?$_REQUEST['pa_analytics_tab_option']:'DESC');
            $is_asc             = ($current_option == 'ASC')?TRUE:FALSE;
            $chart_title        = ($is_asc)?__('Post views Low => High','pressapps'):__('Post views High => Low','pressapps');
            $args               = array(
                'order'   => ($is_asc)?'ASC':'DESC',
                'page_no'   => $page_no,
            );  
            
            $query_args     = array(
                'page'                      => 'pa_analytics',
                'pa_analytics_case'         => 'views',
                'pa_analytics_tab_option'   => $current_option,
            );
            
            $datas  = pa_get_posts_views($args);
            
            if($datas['total']>0) {
            
                foreach($datas['records'] as $data){
                    $labels[]               = $data['post_title'];
                    $col[0]['data'][]       = $data['states'];
                }

                $datasets[] = array(
                    'label'                   =>  __('States','pressapps'),
                    'fillColor'               => "rgba(220,220,220,0.2)",
                    'strokeColor'             => "rgba(220,220,220,1)",
                    'pointColor'              => "rgba(220,220,220,1)",
                    'pointStrokeColor'        => "#fff",
                    'pointHighlightFill'      => "#fff",
                    'pointHighlightStroke'    => "rgba(220,220,220,1)",
                    'data'                    => $col[0]['data'],
                );
                $has_sufficient_data = TRUE;
            }else{
                $has_sufficient_data = FALSE;
            }
            break;
        case 'searches':
            $is_period_enable   = TRUE;
            $order_options      = array(
                'ASC'        => __('Low => High'        ,'pressapps'),
                'DESC'       => __('High => Low'        ,'pressapps'),
            );
            
            $periods            = array(
                '1'         => __('Last 1 Day'   ,'pressapps'),
                '7'         => __('Last 7 Days'  ,'pressapps'),
                '30'        => __('Last 30 Days' ,'pressapps'),
                'all_time'  => __('All Time','pressapps'),
            );
            
            $current_option     = ((isset($_REQUEST['pa_analytics_tab_option']))?$_REQUEST['pa_analytics_tab_option']:'DESC');
            $is_asc             = ($current_option == 'ASC')?TRUE:FALSE;
            $chart_title        = ($is_asc)?__('Searches Low => High From %s','pressapps'):__('Searches High => Low From %s','pressapps');
            $args               = array(
                'order'     => ($is_asc)?'ASC':'DESC',
                'page_no'   => $page_no,
                'period'    => $current_period,
            );  
            
            $chart_title = sprintf($chart_title,$periods[$current_period]);
            
            $query_args     = array(
                'page'                      => 'pa_analytics',
                'pa_analytics_case'         => 'searches',
                'pa_analytics_tab_option'   => $current_option,
                'pa_analytics_period'       => $current_period,
            );
            
            $datas  = pa_get_search_views($args);
            if($datas['total']>0){
                foreach($datas['records'] as $data){
                    $labels[]               = $data['search_term'];
                    $col[0]['data'][]       = $data['total'];
                }

                $datasets[] = array(
                    'label'                   =>  __('States','pressapps'),
                    'fillColor'               => "rgba(220,220,220,0.2)",
                    'strokeColor'             => "rgba(220,220,220,1)",
                    'pointColor'              => "rgba(220,220,220,1)",
                    'pointStrokeColor'        => "#fff",
                    'pointHighlightFill'      => "#fff",
                    'pointHighlightStroke'    => "rgba(220,220,220,1)",
                    'data'                    => $col[0]['data'],
                );
                $has_sufficient_data = TRUE;
            }else{
                $has_sufficient_data = FALSE;
            }
            
            break;
            
            
    }
    
    ?>
<script type="text/javascript">
    jQuery().ready(function(){
       <?php 
       if($has_sufficient_data) :
       ?> 
       var data = {
            labels      : <?php echo json_encode($labels); ?>,
            datasets    : <?php echo json_encode($datasets); ?>   
        };
        
        Chart.defaults.global.responsive = true;
        var ctx         = document.getElementById("myChart").getContext("2d");
        var myBarChart  = new Chart(ctx).Bar(data);
        
        jQuery('.tab_select_opt').change(function(){
            jQuery('.tab_frm').submit();
        });
        
        <?php
        endif;
        ?>
    });
</script>
<style type="text/css">
    #chat_ct,.pa_note_ct {
        text-align:center;
        padding:20px 0px; 
    }
    .tab_frm{
        float:right; 
    }
    .custom-tablenav{
        text-align:center;  
    }
    .tablenav-pages{
        float:none !important; 
    }
</style>
<div class="wrap">
    <h2><?php _e('Analytics','pressapps'); ?></h2>
    <h2 class="nav-tab-wrapper">
        <?php
        foreach($tabs as $tab_key => $tab){
            ?>
            <a href="<?php echo admin_url("admin.php?page=pa_analytics&pa_analytics_case={$tab_key}"); ?>" class="nav-tab <?php echo (($current_tab == $tab_key)?'nav-tab-active':''); ?>">
                <?php echo $tab?>
            </a>
            <?php
        }
        ?>
    </h2>
    <div id="chat_ct">
        <h3><?php echo $chart_title; ?></h3>
        <form method="GET" class="tab_frm">
            <input type="hidden" name="page" value="pa_analytics" />
            <input type="hidden" name="pa_analytics_case" value="<?php echo $current_tab; ?>" />
            <?php
            if($is_period_enable) :
                ?>
                <select name="pa_analytics_period" class="tab_select_opt">
                <?php
                foreach ($periods as $period => $label){
                    echo "<option value=\"{$period}\"" . (($current_period==$period)?'selected="selected"':'') . ">{$label}</option>";
                }
                ?>
                </select>
                <?php
            endif;
            ?>
            <select name="pa_analytics_tab_option" class="tab_select_opt">
                <?php 
                foreach($order_options as $key => $label){
                    echo "<option value=\"{$key}\" " . (($current_option == $key)?'selected="selected"':'') . ">{$label}</option>";
                }
                ?>
            </select>
        </form>
        <br/>
        <?php 
        if($has_sufficient_data):    
            ?><canvas id="myChart" width="700" height="400"></canvas><?php
        else:
            ?>
            <div class="pa_note_ct">
                <h2><?php _e('Sorry Sufficient Data is Not Available To generate the Graph','pressapps'); ?></h2>
            </div>
                <?php
        endif;
        ?>
        
        <div class="tablenav custom-tablenav">
        <div class="tablenav-pages">
        <span class="pagination-links">
            <?php
            echo paginate_links(array(
                'base'      => admin_url('admin.php') . '%_%',
                'format'    => '?page_no=%#%',
                'total'     => $datas['total_pages'],
                'add_args'  => $query_args,
                'current'   => $page_no,
            ));
            ?>
        </span>
        </div>
        </div>
    </div>
</div>
    <?php
}

