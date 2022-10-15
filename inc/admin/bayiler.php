<div style="position: relative;">
	<style>
	table.bayis_tablo {
		width: 1200px;
		max-width: 100%;
	}
	table.bayis_tablo th {
		background: #333;
		color: #fff;
		padding: 8px 10px;
	}
	table.bayis_tablo tbody tr {
		text-align: center;
		background: #d8d8d8;
	}
	table.bayis_tablo tbody tr td {
		padding: 4px 10px;
		font-weight: bold;
	}
	table.bayis_tablo tbody tr td:nth-child(2) {
		padding: 8px 10px;
		text-align: left;
	}
	table.bayis_tablo tbody tr td:nth-child(3) {
		padding: 8px 10px;
		text-align: center;
	}
	.panel_overlay {
		display: none;
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: #ffffffba;
		z-index: 100;
	}
	.panel_overlay img {
		width: 100px;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%,-50%);
	}
a.yeni_bayi_ekle,
.bayi_kaydet_buton {
    background: #4CAF50;
    color: #fff;
    line-height: 1;
    padding: 6px 8px;
    border-radius: 3px;
    text-decoration: none;
    vertical-align: middle;
    margin-left: 3px;
    margin-bottom: 10px;
    display: inline-block;
}
.bayi_kaydet_buton {
	margin-top: 20px;
}
table.bayi_ekle {
	width: 450px;
	max-width: 100%;
	margin-top: 20px;
}
table.bayi_ekle tr td:first-child {
    background: #333;
    color: #fff;
    padding: 11px 16px;
}
table.bayi_ekle tr td:last-child {
    background: #ddd;
    padding: 11px 16px;
}
table.bayi_ekle input[type="text"],
table.bayi_ekle select {
	width: 100%;
}
a.bayi_duzenle {
    background: #3F51B5;
    color: #fff;
    line-height: 1;
    padding: 6px 8px;
    border-radius: 3px;
    text-decoration: none;
    vertical-align: middle;
    margin-left: 3px;
    margin-bottom: 10px;
    display: inline-block;
    font-weight: normal;
}
a.bayi_sil {
    background: #F44336;
    color: #fff;
    line-height: 1;
    padding: 6px 8px;
    border-radius: 3px;
    text-decoration: none;
    vertical-align: middle;
    margin-left: 3px;
    margin-bottom: 10px;
    display: inline-block;
    font-weight: normal;
}
.loader {
	display: none;
	width: 30px; 
	vertical-align: middle; 
	margin-left: 10px;
}
table.bayis_tablo th:nth-child(1) {
    width: 30px;
}
table.bayis_tablo th:nth-child(2) {
    width: 200px;
}
table.bayis_tablo th:nth-child(3) {
    width: 300px;
}
table.bayis_tablo th:nth-child(4) {
    width: 150px;
}
table.bayis_tablo th:nth-child(5) {
    width: 70px;
}
table.bayis_tablo th:nth-child(6) {
    width: 70px;
}
table.bayis_tablo th:nth-child(7) {
    width: 150px;
}
	</style>
	
	<?php
	if( isset($_POST['bayi_kaydet']) ){
		
		$post_arr = array(
			'post_title'   => $_POST['bayi_adi'],
			'post_status'  => 'publish',
			'post_type'    => 'bayi',
		);
		
		$bayi_eklendi = wp_insert_post( $post_arr );
		
		if(!is_wp_error($bayi_eklendi)){
			wp_set_post_terms( $bayi_eklendi, array($_POST['bayi_ilce']), 'sehir' );
			wp_set_post_terms( $bayi_eklendi, array($_POST['bayi_il']), 'sehir', true );
			update_post_meta( $bayi_eklendi, 'bayi_adres', $_POST['bayi_adres'] );
			update_post_meta( $bayi_eklendi, 'bayi_telefon', $_POST['bayi_telefon'] );
			update_post_meta( $bayi_eklendi, 'bayi_telefon2', $_POST['bayi_telefon2'] );
			update_post_meta( $bayi_eklendi, 'bayi_eposta', $_POST['bayi_eposta'] );
			update_post_meta( $bayi_eklendi, 'bayi_yetkili', $_POST['bayi_yetkili'] );
			
			?>
			<script>
			window.location.assign("<?php echo admin_url('admin.php?page=bayiler_panel'); ?>")
			</script>
			<?php
			
		}else{
			echo $bayi_eklendi->get_error_message();
		}
	}
	
	if( isset($_GET['action']) && $_GET['action']=='bayi_sil' && $_GET['bayi_id'] != ''){
		
		$sil = wp_delete_post($_GET['bayi_id']);
		if(!is_wp_error($sil)){
			?>
			<script>
			window.location.assign("<?php echo admin_url('admin.php?page=bayiler_panel'); ?>")
			</script>
			<?php
		}
	}
	
	if( isset($_POST['bayi_duzenle']) ){
		
		$bayi_id = $_GET['bayi_id'];
		
		$my_post = array(
			  'ID'           => $bayi_id,
			  'post_title'   => $_POST['bayi_adi'],
		);
		wp_update_post( $my_post );
		
		update_post_meta( $bayi_id, 'bayi_adres', $_POST['bayi_adres'] );
		update_post_meta( $bayi_id, 'bayi_telefon', $_POST['bayi_telefon'] );
		update_post_meta( $bayi_id, 'bayi_telefon2', $_POST['bayi_telefon2'] );
		update_post_meta( $bayi_id, 'bayi_eposta', $_POST['bayi_eposta'] );
		update_post_meta( $bayi_id, 'bayi_yetkili', $_POST['bayi_yetkili'] );

		wp_set_post_terms( $bayi_id, array($_POST['bayi_ilce']), 'sehir' );
		wp_set_post_terms( $bayi_id, array($_POST['bayi_il']), 'sehir', true );
	}
	?>
	
	<div class="panel_overlay">
		<img src="<?php echo BAYILER_PLG_URL.'img/loader.gif'; ?>"/>
	</div>
	
	<div class="wrap"><div id="icon-tools" class="icon32"></div>
		
		
		<?php
		if( isset($_GET['action']) && $_GET['action']=='yeni_bayi'){
		?>
			<h2 style="display: inline-block;">Yeni Bayi</h2>
		<?php
		}
		if( isset($_GET['action']) && $_GET['action']=='bayi_duzenle' && $_GET['bayi_id']!='' ){
			?>
			<h2 style="display: inline-block;">Bayi Düzenle</h2>
			<?php
		}else{
		?>
			<h2 style="display: inline-block;">Bayiler</h2>
			<a href="<?php echo admin_url('admin.php?page=bayiler_panel&action=yeni_bayi'); ?>" class="yeni_bayi_ekle">YENİ BAYİ</a>
		<?php
		}
		?>
	</div>

	<?php
	if( isset($_GET['action']) && $_GET['action']=='yeni_bayi'){
	?>
		<div>
			<form action="" name="bayi_kaydet" method="post">
				<table class="bayi_ekle">
					<tr>
						<td>Bayi Adı</td>
						<td><input type="text" name="bayi_adi" value=""/></td>
					</tr>
					<tr>
						<td>Adres</td>
						<td><input type="text" name="bayi_adres" value=""/></td>
					</tr>
					<tr>
						<td>İl</td>
						<td>
							<select name="bayi_il">
								<option value="0">Lütfen il seçin</option>
								<?php
								$args_il = array(
									'taxonomy' => 'sehir',
									'parent' => 0,
									'hide_empty' => false,
								);
								$iller = get_terms($args_il);
								
								if( !empty($iller) ){
									
									foreach( $iller as $il ){
										?>
										<option value="<?php echo $il->term_id; ?>"><?php echo $il->name; ?></option>
										<?php
										$i++;
									}
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>İlçe</td>
						<td>
							<select name="bayi_ilce"></select>
						</td>
					</tr>
					<tr>
						<td>Sabit Tel</td>
						<td><input type="text" name="bayi_telefon" value=""/></td>
					</tr>
					<tr>
						<td>Cep Tel</td>
						<td><input type="text" name="bayi_telefon2" value=""/></td>
					</tr>
					<tr>
						<td>E-Posta</td>
						<td><input type="text" name="bayi_eposta" value=""/></td>
					</tr>
					<tr>
						<td>Bayi Yetkilisi</td>
						<td><input type="text" name="bayi_yetkili" value=""/></td>
					</tr>
					
				</table>
				<input type="submit" name="bayi_kaydet" value="KAYDET" class="bayi_kaydet_buton"/>
			</form>
		</div>
	<?php
	}
	if( isset($_GET['action']) && $_GET['action']=='bayi_duzenle' && $_GET['bayi_id'] != '' ){
		$bayi_id = $_GET['bayi_id'];
		$bayi_adres = get_post_meta( $bayi_id, 'bayi_adres', true );
		$bayi_telefon = get_post_meta( $bayi_id, 'bayi_telefon', true );
		$bayi_telefon2 = get_post_meta( $bayi_id, 'bayi_telefon2', true );
		$bayi_eposta = get_post_meta( $bayi_id, 'bayi_eposta', true );
		$bayi_yetkili = get_post_meta( $bayi_id, 'bayi_yetkili', true );
		
		$il_id = '';
		$ilce_id = '';
		
		$terms = get_the_terms( $bayi_id, 'sehir' );
		
		foreach( $terms as $term ){
			
			$term_id = $term->term_id;
			$term_obj = get_term_by( 'id', $term_id, 'sehir' ); 
			/*
			if($term_id->parent == 0){
				
				$il_name = $term_obj->name;
			}*/
			
			if( has_Children($term->term_id)  ){
				$il_id = $term_obj->term_id;
			}else{
				$ilce_id = $term_obj->term_id;
			}
		}
		
		
		?>
		<div>
			<form action="" name="bayi_duzenle" method="post">
				<table class="bayi_ekle">
					<tr>
						<td>Bayi Adı</td>
						<td><input type="text" name="bayi_adi" value="<?php echo get_the_title($bayi_id); ?>"/></td>
					</tr>
					<tr>
						<td>Adres</td>
						<td><input type="text" name="bayi_adres" value="<?php if( $bayi_adres ){ echo $bayi_adres; } ?>"/></td>
					</tr>
					<tr>
						<td>İl</td>
						<td>
							<select name="bayi_il">
								<option value="0">Lütfen il seçin</option>
								<?php
								$args_il = array(
									'taxonomy' => 'sehir',
									'parent' => 0,
									'hide_empty' => false,
								);
								$iller = get_terms($args_il);
								
								if( !empty($iller) ){
									
									foreach( $iller as $il ){
										?>
										<option value="<?php echo $il->term_id; ?>" <?php selected( $il->term_id, $il_id ); ?>><?php echo $il->name; ?></option>
										<?php
										$i++;
									}
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>İlçe</td>
						<td>
							<select name="bayi_ilce">
								<?php
								$args_ilce = array(
									'taxonomy' => 'sehir',
									'parent' => $il_id,
									'hide_empty' => false,
								);
								$ilceler = get_terms($args_ilce);
								
								if( !empty($ilceler) ){
									
									foreach( $ilceler as $ilce ){
										?>
										<option value="<?php echo $ilce->term_id; ?>" <?php selected( $ilce->term_id, $ilce_id ); ?>><?php echo $ilce->name; ?></option>
										<?php
										$i++;
									}
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Sabit Tel</td>
						<td><input type="text" name="bayi_telefon" value="<?php if( $bayi_telefon ){ echo $bayi_telefon; } ?>"/></td>
					</tr>
					<tr>
						<td>Cep Tel</td>
						<td><input type="text" name="bayi_telefon2" value="<?php if( $bayi_telefon2 ){ echo $bayi_telefon2; } ?>"/></td>
					</tr>
					<tr>
						<td>E-Posta</td>
						<td><input type="text" name="bayi_eposta" value="<?php if( $bayi_eposta ){ echo $bayi_eposta; } ?>"/></td>
					</tr>
					<tr>
						<td>Bayi Yetkilisi</td>
						<td><input type="text" name="bayi_yetkili" value="<?php if( $bayi_yetkili ){ echo $bayi_yetkili; } ?>"/></td>
					</tr>
					
				</table>
				<input type="submit" name="bayi_duzenle" value="KAYDET" class="bayi_kaydet_buton"/>
			</form>
		</div>
		<?php
	}else{
	?>
		
		<div style="margin: 20px 0;">
			<span>İl Seçiniz</span>
			<select name="il_sec_admin">
				<option value="0">Tüm İller</option>
				<?php
				$args_il = array(
					'taxonomy' => 'sehir',
					'parent' => 0,
					'hide_empty' => false,
				);
				$iller = get_terms($args_il);
				if( !empty($iller) ){
					foreach( $iller as $il ){
						?>
						<option value="<?php echo $il->term_id; ?>"><?php echo $il->name; ?></option>
						<?php
						$i++;
					}
				}
				?>
			</select>
			
			<span style="margin-left: 10px; display: inline-block;">İlçe Seçiniz</span>
			<select name="ilce_sec_admin">
				<option value="0">Tüm İlçeler</option>
				
			</select>
			
			<img class="loader" src="<?php echo BAYILER_PLG_URL.'/loader.gif'; ?>"/>
		</div>
	
		<div style="margin-top: 20px;">
			<table class="bayis_tablo">
				<thead>
					<th>#</th>
					<th>BAYİ ADI</th>
					<th>ADRES</th>
					<th>SABİT TEL</th>
					<th>CEP TEL</th>
					<th>İLÇE</th>
					<th>İL</th>
					<th>İŞLEMLER</th>
				</thead>
				<tbody class="bayiler_tbody">
				<?php
				$i = 1;
				$args = array(
					'post_type' => 'bayi',
					'post_status' => 'publish',
					'posts_per_page' => -1,
				);
				
				$the_query = new WP_Query( $args );

				if ( $the_query->have_posts() ) {
					
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						global $post;
						
						$bayi_adres = get_post_meta( $post->ID, 'bayi_adres', true );
						$bayi_telefon = get_post_meta( $post->ID, 'bayi_telefon', true );
						$bayi_telefon2 = get_post_meta( $post->ID, 'bayi_telefon2', true );
						
						$il_id = '';
						$ilce_id = '';
						
						$terms = get_the_terms( $post->ID, 'sehir' );
						
						foreach( $terms as $term ){
							
							$term_id = $term->term_id;
							$term_obj = get_term_by( 'id', $term_id, 'sehir' ); 
							/*
							if($term_id->parent == 0){
								
								$il_name = $term_obj->name;
							}*/
							
							if( has_Children($term->term_id)  ){
								$il_adi = $term_obj->name;
								$il_id = $term_obj->term_id;
							}else{
								$ilce_adi = $term_obj->name;
								$ilce_id = $term_obj->term_id;
							}
						}
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
					<tr class="ozet_row">
						<td colspan="7" style="text-align: center;">Kayıtlı bayi bulunmamakta!</td>
					</tr>
					<?php
				}
				wp_reset_postdata();
				?>				
				</tbody>
			</table>
			<span id="xxx"></span>
				
		</div>
	<?php
	}
	?>
	<script>
	jQuery(function ($) {
		
		var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
		
		$('select[name="bayi_il"]').on('change', function(){
			
			var il_term_id = $(this).val();
			
			var data = {
				action: 'ile_gore_ilceleri_getir',
				il_term_id: il_term_id,
			}
			
			$.post( ajaxurl, data, function(response){
				$('select[name="bayi_ilce"]').html('<option value="0">Lütfen ilçe seçin</option>'+response);
			});
			
			return false;
		});
		
		$('select[name="il_sec_admin"]').on('change', function(){
			
			var il_term_id = $(this).val();
			
			var data = {
				action: 'ile_gore_ilceler_admin',
				il_term_id: il_term_id,
			}
			
			$.post( ajaxurl, data, function(response){
				$('select[name="ilce_sec_admin"]').html('<option value="0">Lütfen ilçe seçin</option>'+response);
				
			});
			
			return false;
			
		});
		
		$('select[name="il_sec_admin"], select[name="ilce_sec_admin').on('change', function(){
			
			$('.bayiler_tbody').css('opacity','0');
			$('.loader').show();
			
			var il_term_id = $('select[name="il_sec_admin"]').val();
			var ilce_term_id = $('select[name="ilce_sec_admin"]').val();
			
			
			var data = {
				action: 'il_ve_ilceye_gore_bayiler_admin',
				il_term_id: il_term_id,
				ilce_term_id: ilce_term_id,
			}
			
			$.post( ajaxurl, data, function(response){
				//$('select[name="ilce_sec_admin"]').html('<option value="0">Lütfen ilçe seçin</option>'+response);
				
				$('.bayiler_tbody').html(response);
				$('.bayiler_tbody').css('opacity','1');
				$('.loader').hide();
			});
			
			return false;
			
		});
	});
	</script>
</div>	