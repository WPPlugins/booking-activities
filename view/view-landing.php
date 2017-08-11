<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<div class='wrap'>
<h2><?php esc_html_e( 'Booking Activities', BOOKACTI_PLUGIN_NAME ); ?></h2>

<div id='bookacti-landing-container'>
	
	
	<div id='bookacti-add-ons'>	
		<div id='bookacti-add-ons-intro' >
			<h3><?php esc_html_e( 'Make the most of Booking Activities', BOOKACTI_PLUGIN_NAME ); ?></h3>
			<p><?php esc_html_e( 'You can extend Booking Activities functionnalities with the following great add-ons. They make your booking management easier and boost your turnover. Pick the one you are interested in and just give it a try, you have a 30-day money back guarantee, no conditions. ', BOOKACTI_PLUGIN_NAME ); ?></p>
		</div>
		
		<div id='bookacti-add-ons-container' >
		<?php
			$promo = $promo_price_29 = $promo_price_39 = $promo_price_59 = $promo_price_89 ='';
			if( empty( get_option( 'bookacti-first20-notice-dismissed' ) ) ) {
				$promo = '-20%';
				$promo_price_29 = '23.20€';
				$promo_price_39 = '31.20€';
				$promo_price_59 = '47.20€';
				$promo_price_89 = '71.20€';
			}
		
			$add_ons = array(
				'display-pack' => array( 
					'prefix' => 'badp',
					'title' => __( 'Display Pack', BOOKACTI_PLUGIN_NAME ),
					'subtitle' => '',
					'link' => __( 'http://booking-activities.fr/en/downloads/display-pack/', BOOKACTI_PLUGIN_NAME ),
					'screenshot' => true,
					'light_color' => '#c291f2',
					'dark_color' => '#332640',
					'excerpt' => __( 'Customize Booking Activities appearance with the alternate views and customization options of this pack.', BOOKACTI_PLUGIN_NAME ),
					'price' => '39.00€',
					'promo' => $promo,
					'promo_price' => $promo_price_39
				),
				'prices-and-promotions' => array( 
					'prefix' => 'bapap',
					'title' => __( 'Prices and Promotions', BOOKACTI_PLUGIN_NAME ),
					'subtitle' => '',
					'link' => __( 'http://booking-activities.fr/en/downloads/prices-and-promotions/', BOOKACTI_PLUGIN_NAME ),
					'screenshot' => true,
					'light_color' => '#91d2f2',
					'dark_color' => '#263740',
					'excerpt' => __( 'Put a special price on your events, make promotions and strategically draw the attention of your customers.', BOOKACTI_PLUGIN_NAME ),
					'price' => '59.00€',
					'promo' => $promo,
					'promo_price' => $promo_price_59
				),
				'order-for-customers' => array( 
					'prefix' => 'baofc',
					'title' => __( 'Order for Customers', BOOKACTI_PLUGIN_NAME ),
					'subtitle' => '',
					'link' => __( 'http://booking-activities.fr/en/downloads/order-for-customers/', BOOKACTI_PLUGIN_NAME ),
					'screenshot' => true,
					'light_color' => '#f2ed91',
					'dark_color' => '#403f26',
					'excerpt' => __( 'Order and book for your customers and allow them to pay later on your website. Perfect for your operators and your salespersons.', BOOKACTI_PLUGIN_NAME ),
					'price' => '29.00€',
					'promo' => $promo,
					'promo_price' => $promo_price_29
				),
				'points-of-sale' => array( 
					'prefix' => 'bapos',
					'title' => __( 'Points of Sale', BOOKACTI_PLUGIN_NAME ),
					'subtitle' => __( '(requires WooCommerce)', BOOKACTI_PLUGIN_NAME ),
					'link' => __( 'http://booking-activities.fr/en/downloads/points-of-sale/', BOOKACTI_PLUGIN_NAME ),
					'screenshot' => true,
					'light_color' => '#91f2a1',
					'dark_color' => '#26402a',
					'excerpt' => __( 'You have several points of sale and one website for all. Thanks to this plugin, your points of sale managers will be able to manage independently their own activities, calendars and bookings from this single website.', BOOKACTI_PLUGIN_NAME ),
					'price' => '89.00€',
					'promo' => $promo,
					'promo_price' => $promo_price_89
				)
			);


			foreach( $add_ons as $add_on_slug => $add_on ) {
				$license_status = get_option( $add_on[ 'prefix' ] . '_license_status' );
				if( empty( $license_status ) || $license_status !== 'valid' ) {
					$img_url = '';
					if( $add_on[ 'screenshot' ] === true ) {
						$img_url = plugins_url() . '/' . BOOKACTI_PLUGIN_NAME . '/img/add-ons/' . $add_on_slug . '.png';
					} else if( is_string( $add_on[ 'screenshot' ] ) ) {
						$img_url = plugins_url() . '/' . BOOKACTI_PLUGIN_NAME . '/img/add-ons/' . $add_on[ 'screenshot' ];
					}
				?>
					<div class='bookacti-add-on-container' >
						<div class='bookacti-add-on-inner' >
							<?php if( $add_on[ 'promo' ] !== '' ) { ?>
							<div class='bookacti-add-on-promo' >
								<span><?php echo esc_html( $add_on[ 'promo' ] ); ?></span>
							</div>
							<?php } ?>

							<?php if( $img_url !== '' ) { 
								$color1 = $add_on[ 'light_color' ];
								$color2 = $add_on[ 'dark_color' ];

								if( $color1 && $color2 ) {
								?>
									<style>
										#bookacti-add-on-image-<?php echo $add_on_slug; ?>:before {
											background: <?php echo $color1; ?>;
											background: -moz-radial-gradient(center, ellipse cover, <?php echo $color1; ?> 35%, <?php echo $color2; ?> 135%);
											background: -webkit-radial-gradient(center, ellipse cover, <?php echo $color1; ?> 35%, <?php echo $color2; ?> 135%);
											background: radial-gradient(ellipse at center, <?php echo $color1; ?> 35%, <?php echo $color2; ?> 135%);
											filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color1; ?>', endColorstr='<?php echo $color2; ?>',GradientType=1 );
										}
										#bookacti-add-on-image-<?php echo $add_on_slug; ?> {
											background: <?php echo $color2; ?>;
										}
									</style>
								<?php
								}
							?>

							<div id='bookacti-add-on-image-<?php echo esc_attr( $add_on_slug ); ?>' class='bookacti-add-on-image' >
								<a href='<?php echo esc_url( $add_on[ 'link' ] ); ?>' title='<?php echo esc_attr( $add_on[ 'title' ] ); ?>' target='_blank' >
									<img src='<?php echo esc_url( $img_url ); ?>' title='<?php echo esc_attr( $add_on[ 'title' ] ); ?>' />
								</a>
							</div>
							<?php } ?>

							<div class='bookacti-add-on-description' >
								<div class='bookacti-add-on-title' >
									<h4><?php echo esc_html( $add_on[ 'title' ] ); ?></h4>
									<?php if( $add_on[ 'subtitle' ] !== '' ) { ?>
									<em><?php echo esc_html( $add_on[ 'subtitle' ] ); ?></em>
									<?php } ?>
								</div>

								<div class='bookacti-add-on-excerpt' ><p><?php echo esc_html( $add_on[ 'excerpt' ] ); ?></p></div>

								<div class='bookacti-add-on-price' >
									<p>
									<?php 
										echo esc_html_x( 'From', 'Before add-on price', BOOKACTI_PLUGIN_NAME ) . ' ';
										$price_class = 'bookacti-add-on-price-value';
										if( $add_on[ 'promo_price' ] !== '' ) { $price_class = 'bookacti-line-through'; } 
									?>
										<span class='<?php echo $price_class ?>' >
											<?php echo esc_html( $add_on[ 'price' ] ); ?>
										</span>
									<?php if( $add_on[ 'promo_price' ] !== '' ) { ?>
										<span class='bookacti-add-on-price-value bookacti-add-on-promo-price-value' >
											<?php echo esc_html( $add_on[ 'promo_price' ] ); ?>
										</span>
									<?php } ?>
									</p>
								</div>

								<div class='bookacti-add-on-button' >
									<a href='<?php echo esc_url( $add_on[ 'link' ] ); ?>' title='<?php echo esc_attr( $add_on[ 'title' ] ); ?>' target='_blank' ><?php esc_html_e( 'More information', BOOKACTI_PLUGIN_NAME ); ?></a>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
			}
		?>
		</div>
		
		<div id='bookacti-add-ons-guarantees' >
			<div id='bookacti-add-ons-guarantees-intro' >
				<h3><?php esc_html_e( 'Benefit from the best guarantees', BOOKACTI_PLUGIN_NAME ); ?></h3>
				<p><?php esc_html_e( "Our customers satisfaction is what keep us moving in the right direction. We adapt our products according to your feedbacks in order to meet your needs. So just give a try to Booking Activities and its add-ons. If they do not meet your expectations, you will just have to tell us. This is the very reason why Booking Activities is completely free and we offer a 30-day money back guarantee on all our add-ons.", BOOKACTI_PLUGIN_NAME ); ?></p>
			</div>
			<div id='bookacti-add-ons-guarantees-container' >
				<div class='bookacti-add-ons-guarantee' >
					<div class='bookacti-add-ons-guarantee-picto' ><span class="dashicons dashicons-lock"></span></div>
					<h4><?php esc_html_e( 'Secure Payments', BOOKACTI_PLUGIN_NAME ); ?></h4>
					<div class='bookacti-add-ons-guarantee-description' ><?php esc_html_e( 'Online payments are secured by PayPal', BOOKACTI_PLUGIN_NAME ); ?></div>
				</div>
				<div class='bookacti-add-ons-guarantee' >
					<div class='bookacti-add-ons-guarantee-picto' ><span class="dashicons dashicons-money"></span></div>
					<h4><?php esc_html_e( '30-Day money back guarantee', BOOKACTI_PLUGIN_NAME ); ?></h4>
					<div class='bookacti-add-ons-guarantee-description' ><?php esc_html_e( 'If you are not satisfied you will be 100% refunded, no questions asked', BOOKACTI_PLUGIN_NAME ); ?></div>
				</div>
				<div class='bookacti-add-ons-guarantee' >
					<div class='bookacti-add-ons-guarantee-picto' ><span class="dashicons dashicons-email-alt"></span></div>
					<h4><?php esc_html_e( 'Ready to help', BOOKACTI_PLUGIN_NAME ); ?></h4>
					<div class='bookacti-add-ons-guarantee-description' ><?php esc_html_e( 'Contact us at contact@booking-activities.fr at any time for any kind of help', BOOKACTI_PLUGIN_NAME ); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>