<?php
class wf_shipping_canada_post extends WC_Shipping_Method {

	private $found_rates;
	private $services    = array();
	
	public function __construct() {



		$this->services = array(
			'DOM.RP'         => __( 'Regular Parcel', 'wf-shipping-canada-post' ),
                        'DOM.EP'         => __( 'Expedited Parcel', 'wf-shipping-canada-post' ),
                        'DOM.XP'         => __( 'Xpresspost', 'wf-shipping-canada-post' ),
                        'DOM.PC'         => __( 'Priority', 'wf-shipping-canada-post' ),
			'DOM.XP.CERT'    => __( 'Xpresspost Certified', 'wf-shipping-canada-post' ),
                        'USA.EP'         => __( 'Expedited Parcel USA', 'wf-shipping-canada-post' ),
                        'USA.TP'         => __( 'Tracked Packet USA', 'wf-shipping-canada-post' ),
                        'USA.TP.LVM'     => __( 'Tracked Packet USA (LVM)', 'wf-shipping-canada-post' ),
                        'USA.PW.ENV'     => __( 'Priority Worldwide Envelope USA', 'wf-shipping-canada-post' ),
			'USA.PW.PAK'     => __( 'Priority Worldwide pak USA', 'wf-shipping-canada-post' ),
			'USA.PW.PARCEL'  => __( 'Priority Worldwide Parcel USA', 'wf-shipping-canada-post' ),
			'USA.SP.AIR'     => __( 'Small Packet USA Air', 'wf-shipping-canada-post' ),
			'USA.SP.AIR.LVM' => __( 'Small Packet USA Air (LVM)', 'wf-shipping-canada-post' ),
			'USA.SP.AIR.LVM' => __( 'Tracked Packet USA (LVM)', 'wf-shipping-canada-post' ),
			'USA.SP.SURF'    => __( 'Small Packet USA Surface', 'wf-shipping-canada-post' ),
			'USA.XP'         => __( 'Xpresspost USA', 'wf-shipping-canada-post' ),
			'INT.XP'         => __( 'Xpresspost International', 'wf-shipping-canada-post' ),
			'INT.TP'         => __( 'International Tracked Packet', 'wf-shipping-canada-post' ),
			'INT.IP.AIR'     => __( 'International Parcel Air', 'wf-shipping-canada-post' ),
			'INT.IP.SURF'    => __( 'International Parcel Surface', 'wf-shipping-canada-post' ),
			'INT.PW.ENV'     => __( 'Priority Worldwide Envelope International', 'wf-shipping-canada-post' ),
			'INT.PW.PAK'     => __( 'Priority Worldwide pak International', 'wf-shipping-canada-post' ),
			'INT.PW.PARCEL'  => __( 'Priority Worldwide parcel International', 'wf-shipping-canada-post' ),
			'INT.SP.AIR'     => __( 'Small Packet International Air', 'wf-shipping-canada-post' ),
			'INT.SP.SURF'    => __( 'Small Packet International Surface', 'wf-shipping-canada-post' )
		);

		$this->id                 = WF_CANADAPOST_ID;
		$this->method_title       = __( 'Canada Post (BASIC)', 'wf-shipping-canada-post' );
		$this->method_description = __( 'The ultimate Canada Post WooCommerce Shipping plugin. Real time shipping rates, Shipment Creation, Label and Invoice/Manifest Printing.', 'wf-shipping-canada-post' );

		$this->wf_init();
	}

    private function wf_init() {
		// Load the settings.
		$this->wf_init_form_fields();
		
		// Define user set variables
		$this->title               = $this->get_option('title');
		$this->availability        = "all";
		$this->origin              = $this->get_option('origin');
		$this->packing_method      = "per_item";
		$this->custom_services     = $this->get_option('services');
		$this->offer_rates         = $this->get_option('offer_rates');
		$this->quote_type          = $this->get_option('quote_type');
		$this->use_cost            = $this->get_option('use_cost');
		$this->options             = $this->get_option('options');
		$this->delivery_time_delay = $this->get_option('delivery_time_delay');
		$this->wf_debug            = $this->settings['debug'] == 'yes' ? true : false;
		$this->show_delivery_time  = $this->settings['show_delivery_time'] == 'yes' ? true : false;
		
		// Get merchant credentials
		$this->customer_number = $this->get_option( 'customer_number' );
		$this->username        = $this->get_option( 'merchant_username' );
		$this->password        = $this->get_option( 'merchant_password' );
		$this->contract_id     = $this->get_option( 'contract_number' );
		
                $this->production   = isset($this->settings['production']) && $this->settings['production'] == 'yes' ? true : false;
                $this->endpoint     = ($this->production) ? "https://soa-gw.canadapost.ca/rs/" : "https://ct.soa-gw.canadapost.ca/rs/";
        
		
		$this->output_format   = $this->get_option( 'output_format');
		
		$this->conversion_rate   = $this->get_option( 'conversion_rate');
		

		// Used for weight based packing only
		$this->max_weight = isset( $this->settings['max_weight'] ) ? $this->settings['max_weight'] : '30';

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'wf_clear_transients' ) );
	}

    public function wf_debug( $message, $type = 'notice' ) {
    	if ( $this->wf_debug ) {
    		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) {
    			wc_add_notice( $message, $type );
    		} else {
    			global $woocommerce;

    			$woocommerce->add_message( $message );
    		}
		}
    }

	private function wf_environment_check() {
		global $woocommerce;

		if ( get_woocommerce_currency() != "CAD" ) {
			echo '<div class="notice">
				<p>' . __( 'Please provide Conversion rate in the settings as Canada Post requires the currency in Canadian Dollars.', 'wf-shipping-canada-post' ) . '</p>
			</div>';
		}

		if ( $woocommerce->countries->get_base_country() != "CA" ) {
			echo '<div class="error">
				<p>' . __( 'Canada Post requires that the base country/region is set to Canada.', 'wf-shipping-canada-post' ) . '</p>
			</div>';
		}
		elseif ( ! $this->origin && $this->enabled == 'yes' ) {
			echo '<div class="error">
				<p>' . __( 'Canada Post is enabled, but the origin postcode has not been set.', 'wf-shipping-canada-post' ) . '</p>
			</div>';
		}
	}

	public function admin_options() {
		
		// Check users environment supports this method
		$this->wf_environment_check();
		include_once("market.php");
		?>
			
		<table class="form-table">
			<?php echo '<h3>' . ( ! empty( $this->method_title ) ? $this->method_title : __( 'Settings', 'wf-shipping-canada-post' ) ) . '</h3>';
		echo ( ! empty( $this->method_description ) ) ? wpautop( $this->method_description ) : '';
		$this->generate_settings_html(); ?>
		</table>
		<?php
		

	}

	function generate_services_html() {
		ob_start();
		?>
		<tr valign="top" id="packing_options">
			<td class="titledesc" colspan="2" style="padding-left:0px">
				<strong><?php _e( 'Services', 'wf-shipping-canada-post' ); ?></strong><br><br>
				<table class="canada_post_services widefat">
					<thead>
						<th class="sort">&nbsp;</th>
						<th></th>
						<th><?php _e( 'Service', 'wf-shipping-canada-post' ); ?></th>
					</thead>
					<tbody>
						<?php
							$sort = 0;
							$this->ordered_services = array();

							foreach ( $this->services as $code => $name ) {

								if ( isset( $this->custom_services[ $code ]['order'] ) ) {
									$sort = $this->custom_services[ $code ]['order'];
								}

								while ( isset( $this->ordered_services[ $sort ] ) )
									$sort++;

								$this->ordered_services[ $sort ] = array( $code, $name );

								$sort++;
							}

							ksort( $this->ordered_services );

							foreach ( $this->ordered_services as $value ) {
								$code = $value[0];
								$name = $value[1];
								?>
								<tr>
									<td class="sort"><input type="hidden" class="order" name="canada_post_service[<?php echo $code; ?>][order]" value="<?php echo isset( $this->custom_services[ $code ]['order'] ) ? $this->custom_services[ $code ]['order'] : ''; ?>" /></td>
									<td style="width: 2%;"><input type="checkbox" name="canada_post_service[<?php echo $code; ?>][enabled]" <?php checked( ( ! isset( $this->custom_services[ $code ]['enabled'] ) || ! empty( $this->custom_services[ $code ]['enabled'] ) ), true ); ?> /></td>
									<td><strong><?php echo $code; ?></strong></td>
								</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</td>
		</tr>
			<script type="text/javascript">

				jQuery(window).load(function(){
					// Ordering
					jQuery('.canada_post_services tbody').sortable({
						items:'tr',
						cursor:'move',
						axis:'y',
						handle: '.sort',
						scrollSensitivity:40,
						forcePlaceholderSize: true,
						helper: 'clone',
						opacity: 0.65,
						placeholder: 'wc-metabox-sortable-placeholder',
						start:function(event,ui){
							ui.item.css('background-color','#f6f6f6');
						},
						stop:function(event,ui){
							ui.item.removeAttr('style');
							canada_post_services_row_indexes();
						}
					});

					function canada_post_services_row_indexes() {
						jQuery('.canada_post_services tbody tr').each(function(index, el){
							jQuery('input.order', el).val( parseInt( jQuery(el).index('.canada_post_services tr') ) );
						});
					};

				});

			</script>
			<style type="text/css">
				.canada_post_services
				{
					width:51.5%;
				}
				.canada_post_services td {
					vertical-align: middle;
						padding: 4px 7px;
				}
				.canada_post_services th {
					padding: 9px 7px;
				}
				.canada_post_services th.sort {
					width: 16px;
				}
				.canada_post_services td.sort {
					cursor: move;
					width: 16px;
					padding: 0 16px;
					cursor: move;
					background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAAHUlEQVQYV2O8f//+fwY8gJGgAny6QXKETRgEVgAAXxAVsa5Xr3QAAAAASUVORK5CYII=) no-repeat center;
				}
			</style>
		<?php
		return ob_get_clean();
	}

	public function validate_services_field( $key ) {
		$services         = array();
		$posted_services  = $_POST['canada_post_service'];

		foreach ( $posted_services as $code => $settings ) {

			$services[ $code ] = array(
				'order'              => wc_clean( $settings['order'] ),
				'enabled'            => isset( $settings['enabled'] ) ? true : false,
			);

		}

		return $services;
	}

	public function wf_clear_transients() {
		global $wpdb;

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_cp_quote_%') OR `option_name` LIKE ('_transient_timeout_cp_quote_%')" );
	}

    public function wf_init_form_fields() {
		global $woocommerce;
                $this->form_fields  = array(
			'enabled'          => array(
				'title'           => __( 'Realtime Rates', 'wf-shipping-canada-post' ),
				'type'            => 'checkbox',
				'label'           => __( 'Enable', 'wf-shipping-canada-post' ),
				'default'         => 'no'
			),
			'title'            => array(
				'title'           => __( 'Method Title', 'wf-shipping-canada-post' ),
				'type'            => 'text',
				'description'     => __( 'This controls the title which the user sees during checkout.', 'wf-shipping-canada-post' ),
				'default'         => __( 'Canada Post', 'wf-shipping-canada-post' ),
			),
			'merchant_username' => array(
				  'title' => __( 'Merchant user name', 'wf-shipping-canada-post' ),
				  'type' => 'text',
				  'description' => __( 'Canada Post API Merchant user name.', 'wf-shipping-canada-post' ),
				  'default' => '6e93d53968881714'
				  ),
			'merchant_password' => array(
				  'title' => __( 'Merchant password', 'wf-shipping-canada-post' ),
				  'type' => 'text',
				  'description' => __( 'Canada Post API Merchant password.', 'wf-shipping-canada-post' ),
				  'default' => '0bfa9fcb9853d1f51ee57a'
				  ),
			'production'      => array(
				'title'           => __( 'Production Key', 'wf-shipping-canada-post' ),
				'label'           => __( 'This is a production key', 'wf-shipping-canada-post' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'description'     => __( 'If this is a production API key and not a developer key, check this box.', 'wf-shipping-canada-post' )
				),
			'customer_number' => array(
				  'title' => __( 'Customer number', 'wf-shipping-canada-post' ),
				  'type' => 'text',
				  'description' => __( 'Canada Post API Customer number.', 'wf-shipping-canada-post' ),
				  'default' => '2004381'
			),			
			'service_type' => array(
				  'title' => __( 'Service type', 'wf-shipping-canada-post' ),
				  'type' => 'select',
				  'default'         => 'Contract',
				  'description' => __( 'Choose the type of service you choosen from cannada post API', 'wf-shipping-canada-post' ),
				  'options'         => array(
				  	'contract'            => __( 'Contract', 'wf-shipping-canada-post' ),
				  	'non_contract'       => __( 'Non-Contract', 'wf-shipping-canada-post' ),
			      ),
			),
			'contract_number' => array(
				  'title' => __( 'Contract number', 'wf-shipping-canada-post' ),
				  'type' => 'text',
				  'description' => __( 'Canada POST API Contract Number (only for Contract service).', 'wf-shipping-canada-post' ),
				  'default' => '42708517'
			),

			'origin'           => array(
				'title'           => __( 'Origin Postcode', 'wf-shipping-canada-post' ),
				'type'            => 'text',
				'description'     => __( 'Enter the postcode for the <strong>sender</strong>.', 'wf-shipping-canada-post' ),
				'default'         => 'K6A 3H2',
			),
			'debug'      => array(
				'title'           => __( 'Debug Mode', 'wf-shipping-canada-post' ),
				'label'           => __( 'Enable debug mode', 'wf-shipping-canada-post' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'description'     => __( 'Enable debug mode to show debugging information on the cart/checkout.', 'wf-shipping-canada-post' )
			),
                        'quote_type'  => array(
				'title'           => __( 'Quote Type', 'wf-shipping-canada-post' ),
				'type'            => 'select',
				'default'         => 'commercial',
				'options'         => array(
					'commercial'     => __( 'Commercial', 'wf-shipping-canada-post' ),
					'counter'        => __( 'Counter', 'wf-shipping-canada-post' ),
				),
				'description'     => __( 'Commercial: Discounted rates & Counter: Regular rates', 'wf-shipping-canada-post' )
			),
			'use_cost' => array(
				'title'           => __( 'Rate Cost', 'wf-shipping-canada-post' ),
				'type'            => 'select',
				'default'         => 'due',
				'options'         => array(
					'due'         => __( 'Due', 'wf-shipping-canada-post' ),
					'base'        => __( 'Base', 'wf-shipping-canada-post' ),
				),
				'description'     => __( 'Due: Total cost of the shipment including the options, surcharges, discounts and taxes. & Base: Base cost of the shipment before taxes', 'wf-shipping-canada-post' )
			),
			'options' => array(
                                'title' => __( 'Additional Options', 'woothemes' ),
                                'type' => 'multiselect',
                                'class' => 'chosen_select',
                                'css' => 'width: 450px;',
                                'default' => '',
                                'options' => array(
						'COV'  => __( 'Coverage', 'wf-shipping-canada-post' ),
                        'SO' => __( 'Signature', 'wf-shipping-canada-post' )
					),
					'description'     => __( 'Additional options affect all rates.', 'wf-shipping-canada-post' ),
					'desc_tip'        => true
                        ),
                        'show_delivery_time'      => array(
				'title'           => __( 'Delivery time', 'wf-shipping-canada-post' ),
				'label'           => __( 'Show estimated delivery time next to rate name.', 'wf-shipping-canada-post' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'description'     => __( 'Rates will be labelled, for example, Rate Name - approx. 2 days.', 'wf-shipping-canada-post' )
			),
			'delivery_time_delay'  => array(
				'title'           => __( 'Delivery Delay', 'wf-shipping-canada-post' ),
				'type'            => 'text',
				'default'         => '1',
				'description'     => __( 'If showing delivery time, allow for a delay. e.g. a delay of 1 day for a method which ships in 2 days would be labelled: approx. 2-3 days', 'wf-shipping-canada-post' ),
				'desc_tip'        => true
			),
			'offer_rates'   => array(
				'title'           => __( 'Offer Rates', 'wf-shipping-canada-post' ),
				'type'            => 'select',
				'description'     => '',
				'default'         => 'all',
				'options'         => array(
				    'all'         => __( 'Offer the customer all returned rates', 'wf-shipping-canada-post' ),
				    'cheapest'    => __( 'Offer the customer the cheapest rate only, anonymously', 'wf-shipping-canada-post' ),
				)
                        ),
			'services'  => array(
				'type'            => 'services'
			),
			'conversion_rate' => array(
				  'title' => __( 'Conversion rate', 'wf-shipping-canada-post' ),
				  'label'           => __( 'Please provide the conversion rate from CAD to base currency.', 'wf-shipping-canada-post' ),
				  'type' => 'text',
				  'description' => __( 'ex: Enter CAD to USD conversion rate 0.82 if your base currency is USD.', 'wf-shipping-canada-post' ),
				  'default' => '1'
				  ),
		);
    }

    public function calculate_shipping( $package=array() ) {
    	global $woocommerce;

    	$this->rates      = array();
    	$headers          = $this->wf_get_request_header();
    	$package_requests = $this->wf_get_package_requests( $package );

    	libxml_use_internal_errors( true );

    	$this->wf_debug( 'Canada Post is in Development mode or WP DEBUG is on - note, returned services may not match those set in settings. Production mode will not have this problem. To hide these messages, turn off debug mode in the settings.' );

    	if ( $package_requests ) {

	    	foreach ( $package_requests as $key => $package_request ) {

		    	$request  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	    		$request .= '<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate">' . "\n";
	    		$request .= $package_request;
	    		$request .= $this->wf_get_request( $package );
	    		$request .= '</mailing-scenario>' . "\n";

				$transient       = 'cp_quote_' . md5( $request );
				$cached_response = get_transient( $transient );
	
				if ( $cached_response !== false ) {

			    	$response = $cached_response;

			    	$this->wf_debug( 'Canada Post CACHED REQUEST: <pre>' . print_r( htmlspecialchars( $request ), true ) . '</pre>' );
			    	$this->wf_debug( 'Canada Post CACHED RESPONSE: <pre>' . print_r( htmlspecialchars( $response ), true ) . '</pre>' );
		    	} else {
					$response = wp_remote_post( $this->endpoint.'/ship/price',
			    		array(
							'method'           => 'POST',
							'timeout'          => 70,
							'sslverify'        => 0,
							'headers'          => $headers,
							'body'			   => $request,
					    )
					); 
					
					if ( is_wp_error( $response ) ) {
                        $this->wf_debug( 'Canada Post RESPONSE ERROR: <pre>' . print_r( $response , true ) . '</pre>' );
                        $response = '';
                    }
					// Store result in case the request is made again
					else if ( ! empty( $response['body'] ) ) {
						$response = $response['body'];
						set_transient( $transient, $response, YEAR_IN_SECONDS );
					} else {
						$response = '';
					}

					$this->wf_debug( 'Canada Post REQUEST: <pre>' . print_r( htmlspecialchars( $request ), true ) . '</pre>' );
			    	$this->wf_debug( 'Canada Post RESPONSE: <pre>' . print_r( htmlspecialchars( $response ), true ) . '</pre>' );
				}

				$xml = simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response ) . '</root>' );

				if ( ! $xml ) {
					$this->wf_debug( 'Failed loading XML', 'error' );
				}

				if ( $xml && $xml->{'price-quotes'} ) {
					$price_quotes = $xml->{'price-quotes'}->children( 'http://www.canadapost.ca/ws/ship/rate' );

					if ( $price_quotes->{'price-quote'} ) {
						foreach ( $price_quotes as $quote ) {

							$rate_code = strval( $quote->{'service-code'} );
							$rate_id   = $this->id . ':' . $rate_code;
							$rate_cost = (float) $quote->{'price-details'}->{$this->use_cost};

							// Add any adjustments
							if ( 'base' == $this->use_cost ) {
								$adjustments = (array) $quote->{'price-details'}->{'adjustments'};
								if ( $adjustments ) {
									foreach ( $adjustments as $adjustment ) {
										$adjustment = (array) $adjustment;
										if ( ! empty( $adjustment['adjustment-cost'] ) ) {
											$rate_cost += $adjustment['adjustment-cost'];
										}
									}
								}
							}
							
							if ( ! empty( $this->services[ $rate_code ] ) ) {
								$rate_name = (string) $this->services[ $rate_code ];
							} else {
								$rate_name = (string) $quote->{'service-name'};
							}

							// Get time
							if ( $this->show_delivery_time ) {
								$transmit_time = $quote->{'service-standard'}->{'expected-transit-time'};

								if ( $transmit_time ) {
									if ( $this->delivery_time_delay ) {
										$rate_name = $rate_name . ' - ' . sprintf( __( 'approx. %d&ndash;%d days', 'wf-shipping-canada-post' ), $transmit_time, $transmit_time+$this->delivery_time_delay );
									} else {
										$rate_name = $rate_name . ' - ' . sprintf( _n( 'approx. %d day', 'approx. %d days', $transmit_time, 'wf-shipping-canada-post' ), $transmit_time );
									}
								}
							}
							
							$this->wf_prepare_rate( $rate_code, $rate_id, $rate_name, $rate_cost );
						}
					}
				} else {
                                        // No rates
                                        if($xml && isset($xml->messages->message->description)){
                                            $this->wf_debug( (string)$xml->messages->message->description, 'error' );
                                        }else{
                                            $this->wf_debug( 'Invalid request. Ensure a valid shipping destination has been chosen on the cart/checkout page.', 'error' );
                                        }
				}
			}
		}

		// Ensure rates were found for all packages
		if ( $this->found_rates ) {
			foreach ( $this->found_rates as $key => $value ) {
				if ( $value['packages'] < sizeof( $package_requests ) )
					unset( $this->found_rates[ $key ] );
			}
		}

		if ( ! empty( $this->lettermail ) ) {
			if ( in_array( 'standard', $this->lettermail ) ) {
				$lettermail_rate = $this->wf_calculate_lettermail_rate( $package );
				if ( $lettermail_rate )
					$this->found_rates[ $lettermail_rate['id'] ] = $lettermail_rate;
			}
			if ( in_array( 'registered', $this->lettermail ) ) {
				$lettermail_rate = $this->wf_calculate_lettermail_rate( $package, true );
				if ( $lettermail_rate )
					$this->found_rates[ $lettermail_rate['id'] ] = $lettermail_rate;
			}
		}

		// Add rates
		if ( $this->found_rates ) {

			if ( $this->offer_rates == 'all' ) {

				uasort( $this->found_rates, array( $this, 'wf_sort_rates' ) );

				foreach ( $this->found_rates as $key => $rate ) {
					$rate['cost'] = wf_convert_rate($rate['cost'],$this->conversion_rate);
					$this->add_rate( $rate );
				}

			} else {

				$cheapest_rate = '';

				foreach ( $this->found_rates as $key => $rate ) {
					if ( ! $cheapest_rate || $cheapest_rate['cost'] > $rate['cost'] )
						$cheapest_rate = $rate;
				}

				$cheapest_rate['label'] = $this->title;
				$cheapest_rate['cost'] = wf_convert_rate($cheapest_rate['cost'],$this->conversion_rate);
				$this->add_rate( $cheapest_rate );

			}
		}
    }

    private function wf_prepare_rate( $rate_code, $rate_id, $rate_name, $rate_cost ) {

	    // Name adjustment
		if ( ! empty( $this->custom_services[ $rate_code ]['name'] ) )
			$rate_name = $this->custom_services[ $rate_code ]['name'];

		// Cost adjustment %
		if ( ! empty( $this->custom_services[ $rate_code ]['adjustment_percent'] ) ) {
			$sign = substr( $this->custom_services[ $rate_code ]['adjustment_percent'], 0, 1 );

			if ( $sign == '-' ) {
				$rate_cost = $rate_cost - ( $rate_cost * ( floatval( substr( $this->custom_services[ $rate_code ]['adjustment_percent'], 1 ) ) / 100 ) );
			} else {
				$rate_cost = $rate_cost + ( $rate_cost * ( floatval( $this->custom_services[ $rate_code ]['adjustment_percent'] ) / 100 ) );
			}

			if ( $rate_cost < 0 )
				$rate_cost = 0;
		}

		// Cost adjustment
		if ( ! empty( $this->custom_services[ $rate_code ]['adjustment'] ) ) {
			$sign = substr( $this->custom_services[ $rate_code ]['adjustment'], 0, 1 );

			if ( $sign == '-' ) {
				$rate_cost = $rate_cost - floatval( substr( $this->custom_services[ $rate_code ]['adjustment'], 1 ) );
			} else {
				$rate_cost = $rate_cost + floatval( $this->custom_services[ $rate_code ]['adjustment'] );
			}

			if ( $rate_cost < 0 )
				$rate_cost = 0;
		}

		// Enabled check
		if ( isset( $this->custom_services[ $rate_code ] ) && empty( $this->custom_services[ $rate_code ]['enabled'] ) )
			return;

		// Merging
		if ( isset( $this->found_rates[ $rate_id ] ) ) {
			$rate_cost = $rate_cost + $this->found_rates[ $rate_id ]['cost'];
			$packages  = 1 + $this->found_rates[ $rate_id ]['packages'];
		} else {
			$packages = 1;
		}

		// Sort
		if ( isset( $this->custom_services[ $rate_code ]['order'] ) ) {
			$sort = $this->custom_services[ $rate_code ]['order'];
		} else {
			$sort = 999;
		}

		$this->found_rates[ $rate_id ] = array(
			'id'       => $rate_id,
			'label'    => $rate_name,
			'cost'     => $rate_cost,
			'sort'     => $sort,
			'packages' => $packages
		);
    }

    public function wf_sort_rates( $a, $b ) {
		if ( $a['sort'] == $b['sort'] ) return 0;
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
    }

    private function wf_get_request_header() {
	   return array(
			'Accept'          => 'application/vnd.cpc.ship.rate+xml',
			'Content-Type'    => 'application/vnd.cpc.ship.rate+xml',
			'Authorization'   => 'Basic ' . base64_encode( $this->username . ':' . $this->password ),
			'Accept-language' => 'en-CA'
		);
    }

    private function wf_get_request( $package ) {

		$request  = '	<origin-postal-code>' . apply_filters( 'wc_shipping_canada_post_origin', str_replace( ' ', '', strtoupper( $this->origin ) ), $package, $this ) . '</origin-postal-code>' . "\n";

		if ( $this->quote_type == 'counter' ) {
			$request .= '	<quote-type>' . $this->quote_type . '</quote-type>' . "\n";
		} else {
    		$request .= '	<customer-number>' . $this->customer_number . '</customer-number>' . "\n";

    		if ( $this->contract_id ) {
				$request .= '	<contract-id>' . $this->contract_id . '</contract-id>' . "\n";
			}
		}

		$request .= '	<destination>' . "\n";

		// The destination
		switch ( $package['destination']['country'] ) {
			case "CA" :
				$request .= '		<domestic>' . "\n";
				$request .= '			<postal-code>' . str_replace( ' ', '', strtoupper( $package['destination']['postcode'] ) ) . '</postal-code>' . "\n";
				$request .= '		</domestic>' . "\n";
			break;
			case "US" :
				$request .= '		<united-states>' . "\n";
				$request .= '			<zip-code>' . str_replace( ' ', '', strtoupper( $package['destination']['postcode'] ) ) . '</zip-code>' . "\n";
				$request .= '		</united-states>' . "\n";
			break;
			default :
				$request .= '		<international>' . "\n";
				$request .= '			<country-code>' . $package['destination']['country'] . '</country-code>' . "\n";
				$request .= '		</international>' . "\n";
			break;
		}

		$request .= '	</destination>' . "\n";
		// End destination

		return $request;
    }

    private function wf_get_package_requests( $package ) {

	    // Choose selected packing
    	switch ( $this->packing_method ) {
	    	case 'weight' :
	    		$requests = $this->wf_weight_only_shipping( $package );
	    	break;
	    	case 'box_packing' :
	    		$requests = $this->wf_box_shipping( $package );
	    	break;
	    	case 'per_item' :
	    	default :
	    		$requests = $this->wf_per_item_shipping( $package );
	    	break;
    	}

    	return $requests;
    }

    private function wf_per_item_shipping( $package ) {
	    global $woocommerce;

	    $requests = array();

    	// Get weight of order
    	foreach ( $package['contents'] as $item_id => $values ) {
    		$values['data'] = $this->wf_load_product( $values['data'] );

    		if ( ! $values['data']->needs_shipping() ) {
    			$this->wf_debug( sprintf( __( 'Product #%d is virtual. Skipping.', 'wf-shipping-canada-post' ), $item_id ), 'error' );
    			continue;
    		}

    		if ( ! $values['data']->get_weight() ) {
	    		$this->wf_debug( sprintf( __( 'Product #%d is missing weight. Aborting.', 'wf-shipping-canada-post' ), $item_id ), 'error' );
	    		return;
    		}

    		$parcel  = '<parcel-characteristics>' . "\n";
			$parcel .= '	<weight>' . round( wc_get_weight( $values['data']->get_weight(), 'kg' ), 2 ) . '</weight>' . "\n";

			if ( $values['data']->length && $values['data']->height && $values['data']->width ) {

				$dimensions = array( $values['data']->length, $values['data']->height, $values['data']->width );

				sort( $dimensions );

				$parcel .= '	<dimensions>' . "\n";
				$parcel .= '		<height>' . round( wc_get_dimension( $dimensions[0], 'cm' ), 1 ) . '</height>' . "\n";
				$parcel .= '		<width>' . round( wc_get_dimension( $dimensions[1], 'cm' ), 1 ) . '</width>' . "\n";
				$parcel .= '		<length>' . round( wc_get_dimension( $dimensions[2], 'cm' ), 1 ) . '</length>' . "\n";
				$parcel .= '	</dimensions>' . "\n";
			}

			$parcel .= '</parcel-characteristics>' . "\n";

			// Package options
			if ( ! empty( $this->options ) ) {
				$parcel .= '	<options>' . "\n";
				foreach ( $this->options as $option ) {
					$parcel .= '		<option>' . "\n";
					$parcel .= '			<option-code>' . $option . '</option-code>' . "\n";
					if ( $option == 'COV' )
						$parcel .= '			<option-amount>' . wf_convert_rate($values['data']->get_price(),$this->conversion_rate,false) . '</option-amount>' . "\n";
					$parcel .= '		</option>' . "\n";
				}
				$parcel .= '	</options>' . "\n";
			}

			for ( $i = 0; $i < $values['quantity']; $i ++ )
				$requests[] = $parcel;
    	}

		return $requests;
    }

    private function wf_load_product( $product ){
    	if( !$product ){
    		return false;
    	}
    	return ( WC()->version < '2.7.0' ) ? $product : new wf_product( $product );
    }
}
