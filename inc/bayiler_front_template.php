<style>
	.bayilerimiz {
		display: flex;
	}
	.bayilerimiz > div {
		margin: 10px;
		width: 200px;
	}
	.bayilerimiz select {
		font-style: normal;
	}
	.bayi_listesi {
		margin-top: 20px;
	}
	.tek_bayi {
		margin-bottom: 20px;
	}
	.tek_bayi ul {
		margin: 0!important;
		padding: 0;
		list-style: none;
	}

	.tek_bayi ul li {
		padding: 4px 14px;
		margin: 0!important;
		background: #eee;
		border: 1px solid #fff;
		list-style: none;
	}

	.tek_bayi ul li:nth-child(1) {
		background: #ca171e;
		color: #fff;
		padding: 4px 14px;
	}
	.tek_bayi ul li i {
		width: 18px;
	}
	.tek_bayi ul li:nth-child(1),
	.tek_bayi ul li:nth-child(2) {
		text-transform: uppercase;
	}
</style>
<div class="bayilerimiz">
	<div>
		<span>İl Seçiniz</span>
		<select name="il_sec">
			<option value="tum">Tüm İller</option>
			<?php
			$args_il = array(
				'taxonomy' => 'sehir',
				'parent' => 0,
				'hide_empty' => true,
			);
			$iller = get_terms($args_il);
			if( !empty($iller) ){
				foreach( $iller as $il ){
					?>
					<option value="<?php echo $il->slug; ?>" <?php if($_GET['il']){ selected($il->slug, $_GET['il']); }else{ selected($il->slug, 'izmir'); } ?> ><?php echo $il->name; ?></option>
					<?php
					$i++;
				}
			}
			?>
		</select>
	
	</div>
	<div>
		<span>İlçe Seçiniz</span>
		<select name="ilce_sec">
			<option value="0">Tüm İlçeler</option>
			
			<?php
			if( !isset($_GET['ilce']) && !isset($_GET['il']) ){
				
				$il_obj = get_term_by('slug', 'izmir', 'sehir');
				
				$args_ilceler = array(
					'taxonomy' => 'sehir',
					'parent' => $il_obj->term_id,
					'hide_empty' => true,
				);
				$ilceler = get_terms($args_ilceler);
				if( !empty($ilceler) ){
					foreach( $ilceler as $ilce ){
						?>
						<option value="<?php echo $ilce->slug; ?>"><?php echo $ilce->name; ?></option>
						<?php
						$i++;
					}
				}
			
			}
			if( isset($_GET['il']) && $_GET['il']!='tum' ){
				
				$il_obj = get_term_by('slug', $_GET['il'], 'sehir');
				
				$args_ilceler = array(
					'taxonomy' => 'sehir',
					'parent' => $il_obj->term_id,
					'hide_empty' => true,
				);
				$ilceler = get_terms($args_ilceler);
				if( !empty($ilceler) ){
					foreach( $ilceler as $ilce ){
						?>
						<option value="<?php echo $ilce->slug; ?>" <?php if( $_GET['ilce'] ){ selected($ilce->slug, $_GET['ilce']); }      ?>><?php echo $ilce->name; ?></option>
						<?php
						$i++;
					}
				}
			}
			?>
			
		</select>
	</div>
	
</div>

<?php
if( !isset($_GET['il']) && !isset($_GET['ilce']) ){
	echo '<div class="container"><h2>İzmir</h2></div>';
}

if( isset($_GET['il']) && $_GET['il'] == 'tum' && !isset($_GET['ilce']) ){
	echo '<div class="container"><h2>[:tr]Tüm Bayiler[:en]All Dealers[:]</h2></div>';
}

if( isset($_GET['il']) && $_GET['il'] != '' && isset($_GET['ilce']) && $_GET['ilce'] != '' ){
	
	$il_slug= $_GET['il'];
	$il_obj = get_term_by('slug', $_GET['il'], 'sehir');
	$il_name = $il_obj->name;
	
	$ilce_slug= $_GET['ilce'];
	$ilce_obj = get_term_by('slug', $_GET['ilce'], 'sehir');
	$ilce_name = $ilce_obj->name;
	
	echo '<div class="container"><h2>'.$il_name.' / '.$ilce_name.' </h2></div>';
	
}

if( isset($_GET['il']) && $_GET['il'] != 'tum' && !isset($_GET['ilce']) ){
	$il_slug= $_GET['il'];
	$il_obj = get_term_by('slug', $_GET['il'], 'sehir');
	$il_name = $il_obj->name;
	
	echo '<div class="container"><h2>'.$il_name.' </h2></div>';
}

$bayi_args = array(
	'post_type' => 'bayi',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'tax_query' => array(),
);

if( isset($_GET['il']) && $_GET['il'] != 'tum' ){
	
	$il_slug = $_GET['il'];
	
	$il_arr = array(
            'taxonomy' => 'sehir',
            'field'    => 'slug',
            'terms'    => $il_slug,
        );
		
	array_push( $bayi_args['tax_query'], $il_arr );
	
}

if( isset($_GET['ilce']) && $_GET['ilce'] != '' ){
	
	$ilce_slug= $_GET['ilce'];
	$ilce_obj = get_term_by('slug', $_GET['ilce'], 'sehir');
	$ilce_id = $ilce_obj->term_id;
	
	$ilce_arr = array(
            'taxonomy' => 'sehir',
            'field'    => 'id',
            'terms'    => $ilce_id,
        );
		
	array_push( $bayi_args['tax_query'], $ilce_arr );

}

if( !isset($_GET['il']) && !isset($_GET['ilce']) ){
	
	$il_arr = array(
            'taxonomy' => 'sehir',
            'field'    => 'slug',
            'terms'    => 'izmir',
        );
		
	array_push( $bayi_args['tax_query'], $il_arr );
}

if( !isset($_GET['il']) && $_GET['il']=='tum' ){
	
	$il_arr = array(
            'taxonomy' => 'sehir',
            'field'    => 'slug',
            'terms'    => 'izmir',
        );
	$bos = array();
	array_push( $bayi_args['tax_query'], $bos );
}

$the_query = new WP_Query( $bayi_args );

if ( $the_query->have_posts() ) {
	?>
	<div class="bayi_listesi">
		<div class="container">
			<div class="row">
			
			<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				global $post;
				
				$bayi_adres = get_post_meta( $post->ID, 'bayi_adres', true );
				$bayi_telefon = get_post_meta( $post->ID, 'bayi_telefon', true );
				$bayi_telefon2 = get_post_meta( $post->ID, 'bayi_telefon2', true );
				$bayi_eposta = get_post_meta( $post->ID, 'bayi_eposta', true );
				$bayi_yetkili = get_post_meta( $post->ID, 'bayi_yetkili', true );
				
				/*
				$il_adi = '';
				$ilce_adi = '';
				
				$terms = get_the_terms( $bayi_id, 'sehir' );
				
				foreach( $terms as $term ){
					
					$term_id = $term->term_id;
					$term_obj = get_term_by( 'id', $term_id, 'sehir' ); 
					
					if( has_Children($term->term_id)  ){
						$il_adi = $term_obj->name;
					}else{
						$ilce_adi = $term_obj->name;
					}
				}
				*/
				
				$term_obj_list = get_the_terms( $post->ID, 'sehir' );
				$terms_string = join(' / ', wp_list_pluck($term_obj_list, 'name'));
				?>
				<div class="col-md-4">
					
					<div class="tek_bayi">
						
						<ul>
							<li><?php the_title(); ?></li>
							<li><i class="fas fa-user"></i> <?php if($bayi_yetkili){echo $bayi_yetkili;} ?></li>
							<?php
							if( isset($bayi_telefon) && $bayi_telefon != '' ){
								?>
								<li><a target="_blank" href="tel:<?php echo $bayi_telefon; ?>"><i class="fas fa-phone-alt"></i> <?php if($bayi_telefon){ echo $bayi_telefon; } ?></a></li>
								<?php
							}
							if( isset($bayi_telefon2) && $bayi_telefon2 != '' ){
								?>
								<li><a target="_blank" href="tel:<?php echo $bayi_telefon2; ?>"><i style="margin-left: 2px;" class="fas fa-mobile-alt"></i> <?php if($bayi_telefon2){ echo $bayi_telefon2; } ?></a></li>
								<?php
							}
							if( isset($bayi_adres) && $bayi_adres != '' ){
								?>
								<li><i class="fas fa-map-marker-alt"></i> <?php if($bayi_adres){ echo $bayi_adres; } ?></li>
								<?php
							}
							if( isset($bayi_eposta) && $bayi_eposta != '' ){
								?>
								<li><a target="_blank" href="mailto:<?php if($bayi_eposta){ echo $bayi_eposta; } ?>"><i class="fas fa-envelope"></i> <?php if($bayi_eposta){ echo $bayi_eposta; } ?></a></li>
								<?php
							}
							?>
							
							
							
							
						</ul>
						
					</div>
					
				</div>
			<?php
			}
			?>
			</div>
		</div>
		
	</div>
	<?php
}
?>

<script>
	(function($) {
	
		var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
		
		$('select[name="il_sec"]').on('change', function(){
			
			var ilSlug = $(this).val();
			
			if( ilSlug == 0 ){
				window.location.href = '<?php echo get_site_url(); ?>/bayi-ve-servis';
			}else{
				window.location.href = '<?php echo get_site_url(); ?>/bayi-ve-servis?il='+ilSlug;
			}
		});
		
		$('select[name="ilce_sec"]').on('change', function(){
			var ilSlug = $('select[name="il_sec"]').val();
			var ilceSlug = $(this).val();
			
			if( ilceSlug == 0 ){
				window.location.href = '<?php echo get_site_url(); ?>/bayi-ve-servis?il='+ilSlug;
			}else{
				window.location.href = '<?php echo get_site_url(); ?>/bayi-ve-servis?il='+ilSlug+'&ilce='+ilceSlug;
			}
			
		});
		
	})(jQuery);
</script>