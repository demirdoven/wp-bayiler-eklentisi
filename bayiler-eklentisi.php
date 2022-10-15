<?php
/*
Plugin Name: Bayiler Eklentisi
Plugin URI: https://github.com/demirdoven
Description: Bayiler Eklentisi
Version: 1.0
Author: Selman Demirdoven
Author URI: https://github.com/demirdoven
Licence: GPLv2 or later
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ){
	exit;
}

define( 'BAYILER_PLG_URL', plugin_dir_url( __FILE__ ) );
define( 'BAYILER_PLG_DIR', plugin_dir_path( __FILE__ ) );

function mps_new_admin_scripts( $hook ) {
	wp_enqueue_style('jquery-ui-datepicker', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css', array(), '1.8.5', 'all');
	wp_enqueue_script('mps-custom-script-admin', BAYILER_PLG_URL . 'custom_new.js', array('jquery', 'wp-color-picker', 'jquery-ui-datepicker'), '21.06.2017', 'all');
}
add_action( 'admin_enqueue_scripts', 'mps_new_admin_scripts' );
	
function il_ilce_yukle(){
	
	$json_url = plugin_dir_path(__FILE__).'il-ilce.json';
	$json = file_get_contents($json_url);
	$data = json_decode($json, TRUE);
	
	foreach( $data as $il ){
		
		$il_adi = $il['il'];
		
		$term = term_exists( $il_adi, 'sehir' );
		if ( $term !== 0 && $term !== null ) {}else{
			
			$il_eklendi = wp_insert_term(
				$il_adi,
				'sehir',
				array(
					'slug'        => sanitize_title($il_adi),
					'parent'      => 0,
				)
			);
			
			if( $il_eklendi ){
				
				$ilceler = $il['ilceleri'];
		
				foreach( $ilceler as $ilce ){
					
					$parent_term = term_exists( $il_adi, 'sehir' );
					$parent_term_id = $parent_term['term_id'];
					wp_insert_term(
						$ilce,
						'sehir',
						array(
							'slug'        => $ilce,
							'parent'      => $parent_term_id,
						)
					);
					
				}
				
			}
			
		}
	}
}

function orion_activate_user_pnl(){
	bayi_cpt();
	il_ilce_yukle();
	flush_rewrite_rules();
	
}
register_activation_hook(__FILE__, 'orion_activate_user_pnl');

function bayi_cpt(){

	$singular = 'Bayi';
	$plural = 'Bayiler';
	$labels = array(
		'name' 				 => $singular,
		'singular_name' 	 => $singular,
		'menu_name'          => $plural,
		'add_name' 			 => 'Yeni '. $singular,
		'add_new_item' 		 => 'Yeni '. $singular,
		'edit' 				 => 'Düzenle',
		'edit_item' 		 => 'Bayiyi Düzenle',
		'new_item' 			 => 'Yeni ' . $singular,
		'view' 				 => 'İncele ' . $singular,
		'view_item' 		 => 'İncele ' . $singular,
		'all_items'			 => 'Tüm '.$plural,
		'search_term' 		 => 'Ara ' . $plural,
		'parent' 			 => 'Üst ' . $singular,
		'not_found' 		 => 'Hiç ' . $singular . ' yok',
		'not_found_in_trash' => 'Hiç ' . $singular . ' yok',
	);

	$args = array(
			'labels'              => $labels,
	        'public'              => false,
	        'publicly_queryable'  => true,
	        'exclude_from_search' => false,
	        'show_in_nav_menus'   => false,
	        'show_ui'             => false,
	        'show_in_menu'        => false,
	        'show_in_admin_bar'   => false,
	        'menu_position'       => 10,
	        'menu_icon'           => 'dashicons-slides',
	        'can_export'          => true,
	        'delete_with_user'    => false,
	        'hierarchical'        => false,
	        'has_archive'         => true,
	        'query_var'           => true,
	        'capability_type'     => 'page',
	        'map_meta_cap'        => true,
	        'rewrite'             => array(
	        	'slug' 		 => 'bayiler',
	        	'with_front' => true,
	        	'pages' 	 => true,
	        	'feeds' 	 => true,
	        ),
	        'supports'            => array(
				'title',
			),
	);
	register_post_type( 'bayi', $args);
	
	register_taxonomy( 'sehir', 'bayi', array(
        'label'        => 'Şehirler',
        'rewrite'      => array( 'slug' => 'sehir' ),
        'hierarchical' => true,
        'show_admin_column' => true,
        'query_var'         => true,
    ) );
}
add_action( 'init', 'bayi_cpt' );

function orion_user_p_sc( $atts ){
	ob_start();
	include plugin_dir_path(__FILE__).'inc/bayiler_front_template.php';
	return ob_get_clean();
}
add_shortcode( 'bayiler', 'orion_user_p_sc' );

function talep_meta_box() {
    add_meta_box(
        'op-menu-meta-box-id',
        'Şehir seç',
        'talep_meta_box_cb',
        'bayi',
        'normal',
        'high',
        );
}
add_action( 'add_meta_boxes', 'talep_meta_box' );
 
function talep_meta_box_cb() {
	global $post;
	wp_nonce_field( 'theme_meta_box_nonce', 'meta_box_nonce' );
	
	echo 'kbjhbjh';
}

function has_Children($term_id){
    $children = get_terms(
        'sehir',
        array( 'parent' => $term_id, 'hide_empty' => false )
    );
    if ($children){
        return true;
    }
    return false;
}

function save_metabox_callback( $post_id ) {
 
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'theme_meta_box_nonce' ) ) {
		return;
	}
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
 
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
 
    if ( isset( $_POST['aktiflik'] ) ) {
 
        update_post_meta( $post_id, 'durum', $_POST['aktiflik'] );
 
    }
  
}
add_action( 'save_post', 'save_metabox_callback' );

function wpdocs_register_my_custom_menu_page(){
    add_menu_page( 
        'Bayiler',
        'Bayiler',
        'manage_options',
        'bayiler_panel',
        'bayiler_cb',
		'',
		11
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

function bayiler_cb() {
    include plugin_dir_path(__FILE__).'inc/admin/bayiler.php';
}

function iller_cb() {
	include plugin_dir_path(__FILE__).'inc/admin/iller.php';
}

function ilceler_cb() {
	include plugin_dir_path(__FILE__).'inc/admin/ilceler.php';
}

function ile_gore_ilceleri_getir(){
	
	$il_term_id = @$_REQUEST['il_term_id'];
	
	$term = term_exists( $il_term_id, 'sehir' );
	if ( $term !== 0 && $term !== null ) {}else{
		
		$args_ilce = array(
			'taxonomy' => 'sehir',
			'parent' => $il_term_id,
			'hide_empty' => false,
		);
		$ilceler = get_terms($args_ilce);
		
		if( !empty($ilceler) ){
			
			foreach( $ilceler as $ilce ){
				?>
				<option value="<?php echo $ilce->term_id; ?>"><?php echo $ilce->name; ?></option>
				<?php
			}
			
		}
		
	}
	
	wp_die();
}
add_action( 'wp_ajax_ile_gore_ilceleri_getir', "ile_gore_ilceleri_getir" );
add_action( 'wp_ajax_nopriv_ile_gore_ilceleri_getir', "ile_gore_ilceleri_getir" );

function ile_gore_ilceler_admin(){
	
	$il_term_id = @$_REQUEST['il_term_id'];
	
	if( $il_term_id != 0 ){
		
		$term = term_exists( $il_term_id, 'sehir' );
		if ( $term !== 0 && $term !== null ) {}else{
			
			$args_ilce = array(
				'taxonomy' => 'sehir',
				'parent' => $il_term_id,
				'hide_empty' => false,
			);
			$ilceler = get_terms($args_ilce);
			
			if( !empty($ilceler) ){
				
				foreach( $ilceler as $ilce ){
					?>
					<option value="<?php echo $ilce->term_id; ?>"><?php echo $ilce->name; ?></option>
					<?php
				}
				
			}
			
		}
		
	}
	
	wp_die();
}
add_action( 'wp_ajax_ile_gore_ilceler_admin', "ile_gore_ilceler_admin" );

function il_ve_ilceye_gore_bayiler_admin(){
	
	$il_term_id = @$_REQUEST['il_term_id'];
	$ilce_term_id = @$_REQUEST['ilce_term_id'];
	
	$bayi_args = array(
		'post_type' => 'bayi',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tax_query' => array(),
	);
	
	if( isset($il_term_id) && $il_term_id != 0 ){
	
		$il_arr = array(
			'taxonomy' => 'sehir',
			'field'    => 'id',
			'terms'    => $il_term_id,
		);
			
		array_push( $bayi_args['tax_query'], $il_arr );
	}
	
	if( isset($ilce_term_id) && $ilce_term_id != 0 ){
	
		$ilce_arr = array(
			'taxonomy' => 'sehir',
			'field'    => 'id',
			'terms'    => $ilce_term_id,
		);
			
		array_push( $bayi_args['tax_query'], $ilce_arr );
	}
	
	$the_query = new WP_Query( $bayi_args );

	if ( $the_query->have_posts() ) {
		
		$i = 1;
		
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			global $post;
			
			$il_id = '';
			$ilce_id = '';
			
			$terms = get_the_terms( $post->ID, 'sehir' );
			
			foreach( $terms as $term ){
				
				$term_id = $term->term_id;
				$term_obj = get_term_by( 'id', $term_id, 'sehir' ); 
				
				if( has_Children($term->term_id)  ){
					$il_adi = $term_obj->name;
					$il_id = $term_obj->term_id;
				}else{
					$ilce_adi = $term_obj->name;
					$ilce_id = $term_obj->term_id;
				}
			}
			
			$bayi_adres = get_post_meta( $post->ID, 'bayi_adres', true );
			$bayi_telefon = get_post_meta( $post->ID, 'bayi_telefon', true );
			$bayi_telefon2 = get_post_meta( $post->ID, 'bayi_telefon2', true );
			$bayi_eposta = get_post_meta( $post->ID, 'bayi_eposta', true );
			$bayi_yetkili = get_post_meta( $post->ID, 'bayi_yetkili', true );
						
			?>
			<tr class="tek_satir_bayi" id="<?php echo get_the_ID(); ?>" data-il="<?php echo $il_id; ?>" data-ilce="<?php echo $ilce_id; ?>">
				<td><?php echo $i; ?></td>
				<td><?php the_title(); ?></td>
				<td><?php if( $bayi_adres ){ echo $bayi_adres; } ?></td>
				<td><?php if( $bayi_telefon ){ echo $bayi_telefon; } ?></td>
				<td><?php if( $bayi_telefon2 ){ echo $bayi_telefon2; } ?></td>
				<td><?php echo $ilce_adi; ?></td>
				<td><?php echo $il_adi; ?></td>
				<td>
					<a href="<?php echo admin_url('admin.php?page=bayiler_panel&action=bayi_duzenle&bayi_id='.$post->ID); ?>" class="bayi_duzenle">DÜZENLE</a>
					<a href="<?php echo admin_url('admin.php?page=bayiler_panel&action=bayi_sil&bayi_id='.$post->ID); ?>" class="bayi_sil" onclick="return confirm('Bayi silinecek. Emin misiniz?');">SİL</a>
				</td>
			</tr>
			<?php
			$i++;
		}
	}else{
		?>
		<tr class="tek_satir_bayi" id="<?php echo get_the_ID(); ?>" data-il="<?php echo $il_id; ?>" data-ilce="<?php echo $ilce_id; ?>">
			<td colspan="7" style="text-align: center;">Bayi bulunamadı!</td>
		</tr>
		<?php
	}
	
	wp_die();
}
add_action( 'wp_ajax_il_ve_ilceye_gore_bayiler_admin', "il_ve_ilceye_gore_bayiler_admin" );

function add_fontawesome_head(){
	?>
	<script src="https://kit.fontawesome.com/64ddb822ce.js" crossorigin="anonymous"></script>
	<?php
}
add_action('wp_head', 'add_fontawesome_head');
?>