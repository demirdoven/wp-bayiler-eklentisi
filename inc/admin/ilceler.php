<div style="position: relative;">
	<style>
	table.withdrawals_tablo {
		width: 500px;
		max-width: 100%;
	}
	table.withdrawals_tablo th {
		background: #333;
		color: #fff;
		padding: 8px 10px;
	}
	table.withdrawals_tablo tbody tr {
		text-align: center;
		background: #d8d8d8;
	}
	table.withdrawals_tablo tbody tr td {
		padding: 4px 10px;
		font-weight: bold;
	}
	table.withdrawals_tablo tbody tr td:nth-child(2) {
		padding: 8px 10px;
		text-align: left;
	}
	table.withdrawals_tablo tbody tr td:nth-child(3) {
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
	</style>
		
		<div class="panel_overlay">
			<img src="<?php echo BAYILER_PLG_URL.'img/loader.gif'; ?>"/>
		</div>
		
		<div class="wrap"><div id="icon-tools" class="icon32"></div>
			<h2>İlçeler</h2>
		</div>

	<div class="manset_ayarlar">
		
				<table class="withdrawals_tablo">
					<thead>
						<th>#</th>
						<th>İLÇE</th>
						<th>İL</th>
						<th>BAYİ SAYISI</th>
					</thead>
					<tbody>
					<?php
					$i = 1;
					$k = 1;
					
					$args_il = array(
						'taxonomy' => 'sehir',
						'hide_empty' => false,
						'parent' => 0,
					);
					$iller = get_terms($args_il);
					
					if( !empty($iller) ){
						
						foreach( $iller as $il ){
							
							$il_id = $il->term_id;
							
							$args_ilce = array(
								'taxonomy' => 'sehir',
								'hide_empty' => false,
								'parent' => $il_id,
								'order_by' => 'name',
								'order' => 'asc',
							);
							$ilceler = get_terms($args_ilce);
							
							if( !empty($ilceler) ){
						
								foreach( $ilceler as $ilce ){
									
									?>
									
										<tr class="tek_satir_ilce <?php echo $k; ?>" id="<?php echo get_the_ID(); ?>">
											<td><?php echo $i; ?></td>
											<td><?php echo $ilce->name; ?></td>
											<td><?php echo $il->name; ?></td>
											<td><?php echo $ilce->count; ?></td>
										</tr>
									<?php
									$i++;
									
								}
							}
							$k++;
						}
						
					}else{
						?>
						<tr class="ozet_row">
							<td colspan="5" style="text-align: center;">Kayıtlı ilçe bulunmamakta!</td>
						</tr>
						<?php
					}
					wp_reset_postdata();
					?>				
					</tbody>
				</table>
				
				
				
				<span id="xxx"></span>
			
		</div>	

	<script>
	jQuery(function ($) {
		
		var ajaxurl = '<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php';
		
		$('.withdrawal_durum').on('change', function(){
			
			$('.panel_overlay').show();
			
			var postId = $(this).parent().parent().attr('id');
			var yeniDurum = $(this).val();
			
			var data = {
					action: 'withdrawal_durum_duzenle',
					post_id: postId,
					yeni_durum: yeniDurum,
				}
				
				$.post(ajaxurl, data, function(response){
					$('.panel_overlay').hide();
					
					if( response==0 ){
						alert('Hata oluştu! Lütfen sayfayı yenileyip tekrar deeneyiniz.');
					}
					if( response==1 ){
						alert('Durum güncellendi!');
					}
				});
				
			return false;
			
		});
	});
	</script>
</div>	