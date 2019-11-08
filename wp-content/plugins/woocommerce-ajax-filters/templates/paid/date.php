<?php 
$date_style = br_get_value_from_array($berocket_query_var_title, 'date_style');
$rand = 'br_date_'.rand();
/*add_filter( 'posts_clauses', 'berocket_reset_orderby_clauses_popularity', 999999 );
$query = new WP_Query( array(
    'post_type'         => 'product',
    'posts_per_page'    => 1,
    'order'             => 'ASC',
    'orderby'           => 'date'
) );
remove_filter( 'posts_clauses', 'berocket_reset_orderby_clauses_popularity', 999999 );*/
global $wpdb;
$query = "SELECT post_date FROM {$wpdb->posts} WHERE post_type = 'product' ORDER BY post_date ASC";
$query = $wpdb->get_var($query);
$datetime = strtotime('-30 days');
if( ! empty($query) ) {
    $datetime = new DateTime($query);
    $datetime = $datetime->getTimestamp();
}
if( ! empty($date_style) && strpos($date_style, 'Y') !== FALSE && strpos($date_style, 'm') !== FALSE && strpos($date_style, 'd') !== FALSE ) {
    $format_arr = array('php' => $date_style, 'js' => str_replace(array('Y', 'm', 'd'), array('yy', 'mm', 'dd'), $date_style));
} else {
    $format_arr = array('php' => 'm/d/Y', 'js' => 'mm/dd/yy');
}
$correct_value1 = date('m/d/Y', $datetime);
$correct_value2 = date('m/d/Y', strtotime('+1 day'));
$slider_value1 = date($format_arr['php'], $datetime);
$slider_value2 = date($format_arr['php'], strtotime('+1 day'));
$default_1 = $slider_value1;
$default_2 = $slider_value2;
if ( ! empty($_POST['limits']) && is_array($_POST['limits']) ) {
    foreach ( $_POST['limits'] as $p_limit ) {
        if ( $p_limit[0] == 'pa__date' || $p_limit[0] == '_date' ) {
            $slider_value1 = date($format_arr['php'], strtotime($p_limit[1]));
            $slider_value2 = date($format_arr['php'], strtotime($p_limit[2]));
            $correct_value1 = date('m/d/Y', strtotime($p_limit[1]));
            $correct_value2 = date('m/d/Y', strtotime($p_limit[2]));
        }
    }
}
?>
<li class="berocket_datepicker_fields field_1">
    <input class="br_date_filter br_start_date <?php echo $rand; ?>" data-taxonomy="date" data-term="start" data-default="<?php echo $default_1; ?>" value="<?php echo $slider_value1; ?>">
</li>
<li class="berocket_datepicker_fields field_2">
    <input class="br_date_filter br_end_date <?php echo $rand; ?>" data-taxonomy="date" data-term="end" data-default="<?php echo $default_2; ?>" value="<?php echo $slider_value2; ?>">
</li>
<li style="clear:both;opacity:0;">
    <div class="berocket_date_picker <?php echo $rand; ?>" 
    data-taxonomy="_date" 
    data-min="<?php echo $default_1; ?>" data-max="<?php echo $default_2; ?>" 
    data-value_1="<?php echo $slider_value1; ?>" data-value_2="<?php echo $slider_value2; ?>"
    data-value1="<?php echo str_replace('/', '', $correct_value1); ?>" data-value2="<?php echo str_replace('/', '', $correct_value2); ?>"
    data-term_slug="" data-step="1" 
    data-all_terms_name="null" 
    data-all_terms_slug="null"
    data-child_parent="" 
    data-child_parent_depth="1"
    data-fields_2="<?php echo $rand; ?>"></div>
</li>
<script>
    jQuery(document).ready(function() {
        jQuery( '.br_date_filter.<?php echo $rand; ?>' ).datepicker({
            dateFormat: '<?php echo $format_arr["js"]; ?>',
            minDate: '<?php echo $default_1; ?>',
            maxDate: '<?php echo $default_2; ?>'
        });
    });
</script>
