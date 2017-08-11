<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

// INIT VARIABLES
	// Templates options list
	$templates = bookacti_fetch_templates();
	$templates_options = '';
	foreach( $templates as $template ) {
		$templates_options .= '<option value="' . esc_attr( $template->id ) . '" >' . esc_html( stripslashes( $template->title ) ) . '</option>';
	}

	// Users options list
	$in_roles		= apply_filters( 'bookacti_managers_roles', array() );
	$not_in_roles	= apply_filters( 'bookacti_managers_roles_exceptions', array( 'administrator' ) );
	$user_query		= new WP_User_Query( array( 'role__in' => $in_roles, 'role__not_in' => $not_in_roles ) );
	$users			= $user_query->get_results();
	$users_options_for_activities	= '';
	$users_options_for_templates	= '';
	if ( ! empty( $users ) ) {
		foreach( $users as $user ) {
			if( $user->has_cap( 'bookacti_edit_activities' ) || $user->has_cap( 'bookacti_edit_templates' ) || $user->has_cap( 'bookacti_read_templates' ) ) {
				$user_info = get_userdata( $user->ID );
				$display_name = $user_info->user_login;
				if( $user_info->first_name && $user_info->last_name ){
					$display_name = $user_info->first_name  . ' ' . $user_info->last_name . ' (' . $user_info->user_login . ')';
				}
				$display_name = apply_filters( 'bookacti_managers_name_display', $display_name, $user_info );
				
				if( $user->has_cap( 'bookacti_edit_activities' ) ) {
					$users_options_for_activities .= '<option value="' . esc_attr( $user->ID ) . '" >' . esc_html( $display_name ) . '</option>';
				}
				
				if( $user->has_cap( 'bookacti_edit_templates' ) || $user->has_cap( 'bookacti_read_templates' ) ) {
					$users_options_for_templates .= '<option value="' . esc_attr( $user->ID ) . '" >' . esc_html( $display_name ) . '</option>';
				}
			}
		}
	}
?>

<!-- Delete event -->
<div id='bookacti-delete-event-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs' >
    <div><?php esc_html_e( 'Are you sure to delete this event permanently?', BOOKACTI_PLUGIN_NAME ); ?></div>
</div>

<!-- Delete template -->
<div id='bookacti-delete-template-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs' >
    <div><?php esc_html_e( 'Are you sure to delete this calendar?', BOOKACTI_PLUGIN_NAME ); ?></div>
</div>

<!-- Delete activity -->
<div id='bookacti-delete-activity-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs' >
    <div>
        <?php esc_html_e( 'Are you sure to delete this activity permanently?', BOOKACTI_PLUGIN_NAME ); ?><br/>
        <em><?php esc_html_e( 'This will not delete the related events on the calendars but you will never be able to place new events from this activity anymore.', BOOKACTI_PLUGIN_NAME ); ?></em>
    </div>
</div>

<!-- Edit event dialog -->
<div id='bookacti-event-data-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs' data-event-id='0'  >
	<form id='bookacti-event-data-form' >
		<?php wp_nonce_field( 'bookacti_update_event_data', 'nonce_update_event_data' ); ?>
		<input type='hidden' name='event-id'	id='bookacti-event-data-form-event-id'		value='' />
		<input type='hidden' name='event-start'	id='bookacti-event-data-form-event-start'	value='' />
		<input type='hidden' name='event-end'	id='bookacti-event-data-form-event-end'		value='' />
		<input type='hidden' name='action'		id='bookacti-event-data-form-action'		value='' />
		
		<div id='bookacti-event-dialog-lang-switcher' class='bookacti-lang-switcher' ></div>
		
		<?php
		//Fill the array of tabs with their label, callback for content and display order
		$event_tabs = apply_filters( 'bookacti_event_dialog_tabs', array (
			array(	'label'			=> __( 'General', BOOKACTI_PLUGIN_NAME ),
					'id'			=> 'general',
					'callback'		=> 'bookacti_fill_event_tab_general',
					'parameters'	=> array(),
					'order'			=> 10 ),
			array(	'label'			=> __( 'Repetition', BOOKACTI_PLUGIN_NAME ),
					'id'			=> 'repetition',
					'callback'		=> 'bookacti_fill_event_tab_repetition',
					'parameters'	=> array(),
					'order'			=> 20 )
		) );

		// Display tabs
		bookacti_display_tabs( $event_tabs, 'event' );


		function bookacti_fill_event_tab_general( $params ) {
			do_action( 'bookacti_event_tab_general_before', $params );
		?>
			<div>
				<label for='bookacti-event-title' ><?php esc_html_e( 'Title', BOOKACTI_PLUGIN_NAME ); ?></label>
				<input type='text' name='event-title' id='bookacti-event-title' />
			</div>
			<div>
				<label for='bookacti-event-availability' ><?php esc_html_e( 'Availability', BOOKACTI_PLUGIN_NAME ); ?></label>
				<input type='number' min='0' value='0' id='bookacti-event-availability' 
					   data-verified='false'
					   onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='event-availability' />
			</div>
		<?php
			do_action( 'bookacti_event_tab_general_after', $params );
		}

		function bookacti_fill_event_tab_repetition( $params ) {
			do_action( 'bookacti_event_tab_repetition_before', $params );
		?>
			<div id='bookacti-event-repeat-freq-container'>
				<label for='bookacti-event-repeat-freq' ><?php esc_html_e( 'Repetition Frequency', BOOKACTI_PLUGIN_NAME ); ?></label>
				<select name='event-repeat-freq' id='bookacti-event-repeat-freq' data-initial-freq='none' >
					<option value='none' selected='selected'>   <?php esc_html_e( 'Do not repeat', BOOKACTI_PLUGIN_NAME ); ?>  </option>
					<option value='daily'>                      <?php esc_html_e( 'Daily', BOOKACTI_PLUGIN_NAME ); ?>          </option>
					<option value='weekly'>                     <?php esc_html_e( 'Weekly', BOOKACTI_PLUGIN_NAME ); ?>         </option>
					<option value='monthly'>                    <?php esc_html_e( 'Monthly', BOOKACTI_PLUGIN_NAME ); ?>        </option>
				</select>
			</div>
			<div id='bookacti-event-repeat-period-container'>
				<div>
					<label for='bookacti-event-repeat-from' ><?php esc_html_e( 'Repeat from', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='date' name='event-repeat-from' id='bookacti-event-repeat-from' data-verified='false' />
				</div>
				<div>
					<label for='bookacti-event-repeat-to' ><?php esc_html_e( 'Repeat to', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='date' name='event-repeat-to' id='bookacti-event-repeat-to' data-verified='false' />
				</div>
			</div>
			<div id='bookacti-event-exceptions-container'>
				<label class='bookacti-fullwidth-label'><?php esc_html_e( 'Exceptions', BOOKACTI_PLUGIN_NAME ); ?></label>
				<div id='bookacti-event-add-exception-container' >
					<input type='date' id='bookacti-event-exception-date-picker' >
					<button type='button' id='bookacti-event-add-exception-button' ><?php esc_html_e( 'Add', BOOKACTI_PLUGIN_NAME ); ?></button>
				</div>
				<div>
					<select multiple id='bookacti-event-exceptions-selectbox' name='event-repeat-excep[]' ></select>
					<button type='button' id='bookacti-event-delete-exceptions-button' ><?php esc_html_e( 'Delete selected', BOOKACTI_PLUGIN_NAME ); ?></button>
				</div>
			</div>
		<?php 
			do_action( 'bookacti_event_tab_repetition_after', $params );
		} 
		?>
	</form>
</div>

<!-- Template params -->
<div id='bookacti-template-data-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs tabs' >
	<form id='bookacti-template-data-form' >
		<?php wp_nonce_field( 'bookacti_insert_or_update_template', 'nonce_insert_or_update_template' ); ?>
		<input type='hidden' name='template-id'	id='bookacti-template-data-form-template-id'	value='' />
		<input type='hidden' name='action'		id='bookacti-template-data-form-action'			value='' />
		<div id='bookacti-template-dialog-lang-switcher' class='bookacti-lang-switcher' ></div>
		
		<?php 
			//Fill the array of tabs with their label, callback for content and display order
			$template_tabs = apply_filters( 'bookacti_template_dialog_tabs', array (
				array(	'label'			=> __( 'General', BOOKACTI_PLUGIN_NAME ),
						'callback'		=> 'bookacti_fill_template_tab_general',
						'parameters'	=> array( 'template_options' => $templates_options ),
						'order'			=> 10 ),
				array(	'label'			=> __( 'Agenda', BOOKACTI_PLUGIN_NAME ),
						'callback'		=> 'bookacti_fill_template_tab_agenda',
						'order'			=> 40 ),
				array(	'label'			=> __( 'Permissions', BOOKACTI_PLUGIN_NAME ),
						'callback'		=> 'bookacti_fill_template_tab_permissions',
						'parameters'	=> array( 'users_options_for_templates' => $users_options_for_templates ),
						'order'			=> 100 )
			) );
			
			// Display tabs
			bookacti_display_tabs( $template_tabs, 'template' );

			//Tab content for template general tab
			function bookacti_fill_template_tab_general( $params ) {
				$templates_options = $params[ 'template_options' ];
				do_action( 'bookacti_template_tab_general_before', $params );
			?>
				<div>
					<label for='bookacti-template-title' ><?php esc_html_e( 'Title', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='text' name='template-title' id='bookacti-template-title' />
				</div>
				<div id='bookacti-duplicate-template-fields'>
					<label for='bookacti-template-duplicated-template-id' ><?php esc_html_e( 'Duplicate from', BOOKACTI_PLUGIN_NAME ); ?></label>
					<select name='duplicated-template-id' id='bookacti-template-duplicated-template-id' class='bookacti-template-select-box' >
						<option value='0' ><?php esc_html_e( 'Don\'t duplicate', BOOKACTI_PLUGIN_NAME ); ?></option>
						<?php echo $templates_options; ?>
					</select>
				</div>
				<div>
					<label for='bookacti-template-opening' ><?php esc_html_e( 'Opening', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='date' name='template-opening' id='bookacti-template-opening' >
				</div>
				<div>
					<label for='bookacti-template-closing' ><?php esc_html_e( 'Closing', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='date' name='template-closing' id='bookacti-template-closing' >
				</div>
			<?php 
					do_action( 'bookacti_template_tab_general_after', $params );
			} 
			
			
			function bookacti_fill_template_tab_agenda() {
				do_action( 'bookacti_template_tab_agenda_before' );
			?>
				<div>
					<label for='bookacti-template-data-minTime' >
						<?php 
						/* translators: Refers to the first hour displayed on calendar. More information: http://fullcalendar.io/docs/agenda/minTime/ */
						_e( 'Day begin', BOOKACTI_PLUGIN_NAME );
						?>
					</label>
					<input type="time" name="templateOptions[minTime]" id='bookacti-template-data-minTime' value='08:00'>
					<?php
					$tip = __( "Set when you want the days to begin on the calendar. Ex: '06:00' Days will begin at 06:00am.", BOOKACTI_PLUGIN_NAME );
					$tip .= ' ' . __( "See more at", BOOKACTI_PLUGIN_NAME );
					$tip .= ' <a href="http://fullcalendar.io/docs/agenda/minTime/" target="_blank" >minTime</a>.';
					bookacti_help_tip( $tip );
					?>
				</div>
				<div>
					<label for='bookacti-template-data-maxTime' >
						<?php 
						/* translators: Refers to the last hour displayed on calendar. More information: http://fullcalendar.io/docs/agenda/maxTime/ */
						_e( 'Day end', BOOKACTI_PLUGIN_NAME );  
						?>
					</label>
					<input type="time" name="templateOptions[maxTime]" id='bookacti-template-data-maxTime' value='20:00' >
					<?php
					$tip = __( "Set when you want the days to end on the calendar. Ex: '18:00' Days will end at 06:00pm.", BOOKACTI_PLUGIN_NAME );
					$tip .= ' ' . __( "See more at", BOOKACTI_PLUGIN_NAME );
					$tip .= ' <a href="http://fullcalendar.io/docs/agenda/maxTime/" target="_blank" >maxTime</a>.';
					bookacti_help_tip( $tip );
					?>
				</div>
			<?php
				do_action( 'bookacti_template_tab_agenda_after' );
				
				$license_status = get_option( 'badp_license_status' );
				if( empty( $license_status ) || $license_status !== 'valid' ) {
					?>
					<div class='bookacti-addon-promo' >
						<?php esc_html_e( 'Get other essential customization options with Display Pack add-on!', BOOKACTI_PLUGIN_NAME ); ?>
						<div><a href='<?php echo esc_url( __( 'http://booking-activities.fr/en/downloads/display-pack/', BOOKACTI_PLUGIN_NAME ) ); ?>' class='button' target='_blank' ><?php esc_html_e( 'Learn more', BOOKACTI_PLUGIN_NAME ); ?></a></div>
					</div>
					<?php
				}
			}
			
			
			//Tab content for permission general tab
			function bookacti_fill_template_tab_permissions( $params ) {
				$users_options_for_templates = $params[ 'users_options_for_templates' ];
				do_action( 'bookacti_template_tab_permissions_before', $params );
			?>	
				<div id='bookacti-template-managers-container' class='bookacti-items-container' data-type='users' >
					<label id='bookacti-template-managers-title' class='bookacti-fullwidth-label' for='bookacti-add-new-template-managers-select-box' >
						<?php esc_html_e( 'Who can manage this calendar?', BOOKACTI_PLUGIN_NAME ); ?>
					</label>
					<div id='bookacti-add-template-managers-container' class='bookacti-add-items-container' >
						<select id='bookacti-add-new-template-managers-select-box' class='bookacti-add-new-items-select-box' >
							<?php echo $users_options_for_templates; ?>
						</select>
						<button type='button' id='bookacti-add-template-managers' class='bookacti-add-items' ><?php esc_html_e( 'Add manager', BOOKACTI_PLUGIN_NAME ); ?></button>
					</div>
					<div id='bookacti-template-managers-list-container' class='bookacti-items-list-container' >
						<select name='template-managers[]' id='bookacti-template-managers-select-box' class='bookacti-items-select-box' multiple >
						</select>
						<button type='button' id='bookacti-remove-template-managers' class='bookacti-remove-items' ><?php esc_html_e( 'Remove selected', BOOKACTI_PLUGIN_NAME ); ?></button>
					</div>
				</div>
			<?php 
				do_action( 'bookacti_template_tab_permissions_after', $params );
			} ?>
	</form>
</div>

<!-- Activity param -->
<div id='bookacti-activity-data-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs' >
	<form id='bookacti-activity-data-form' >
		<?php wp_nonce_field( 'bookacti_insert_or_update_activity', 'nonce_insert_or_update_activity' ); ?>
		<input type='hidden' name='activity-id' id='bookacti-activity-activity-id' />
		<input type='hidden' name='action'		id='bookacti-activity-action' />
		<input type='hidden' name='template-id' id='bookacti-activity-template-id' />
		
		<div id='bookacti-activity-dialog-lang-switcher' class='bookacti-lang-switcher' ></div>
			
			<?php
			//Fill the array of tabs with their label, callback for content and display order
				$activity_tabs = apply_filters( 'bookacti_activity_dialog_tabs', array (
					array(	'label'			=> __( 'General', BOOKACTI_PLUGIN_NAME ),
							'callback'		=> 'bookacti_fill_activity_tab_general',
							'parameters'	=> array(),
							'order'			=> 10 ),
					array(	'label'			=> __( 'Terminology', BOOKACTI_PLUGIN_NAME ),
							'callback'		=> 'bookacti_fill_activity_tab_terminology',
							'parameters'	=> array(),
							'order'			=> 20 ),
					array(	'label'			=> __( 'Permissions', BOOKACTI_PLUGIN_NAME ),
							'callback'		=> 'bookacti_fill_activity_tab_permissions',
							'parameters'	=> array(	'users_options_for_activities' => $users_options_for_activities,
														'templates_options' => $templates_options ),
							'order'			=> 100 )
				) );
				
				// Display tabs
				bookacti_display_tabs( $activity_tabs, 'activity' );
			?>
			
			<?php
			function bookacti_fill_activity_tab_general( $params ) {
				do_action( 'bookacti_activity_tab_general_before', $params );
			?>
				<div>
					<label for='bookacti-activity-title' ><?php esc_html_e( 'Title', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='text'		name='activity-title'		id='bookacti-activity-title' class='bookacti-translate-input' />
					<input type='hidden'	name='activity-old-title'	id='bookacti-activity-old-title' />
				</div>
				<div>
					<label for='bookacti-activity-availability' ><?php esc_html_e( 'Availability', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='number' 
						   name='activity-availability' 
						   id='bookacti-activity-availability' 
						   min='0' step='1' value='0' 
						   onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
				</div>
				<div>
					<input type='hidden' name='activity-duration' id='bookacti-activity-duration' />
					<label><?php esc_html_e( 'Duration', BOOKACTI_PLUGIN_NAME ); ?></label>
					<div class='bookacti-display-inline-block' >
						<input type='number' 
							   name='activity-duration-days'
							   id='bookacti-activity-duration-days' 
							   min='0' max='365' step='1' value='000'
							   onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
						<?php /* translators: 'd' stand for days */ 
						echo esc_html( _x( 'd', 'd for days', BOOKACTI_PLUGIN_NAME ) ); ?>
						<input type='number' 
							   name='activity-duration-hours' 
							   id='bookacti-activity-duration-hours' 
							   min='0' max='23' step='1' value='01'
							   onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
						<?php /* translators: 'h' stand for hours */ 
						echo esc_html( _x( 'h', 'h for hours', BOOKACTI_PLUGIN_NAME ) ); ?>
						<input type='number' 
							   name='activity-duration-minutes' 
							   id='bookacti-activity-duration-minutes' 
							   min='0' max='59' step='5' value='00'
							   onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
						<?php /* translators: 'm' stand for minutes */ 
						echo esc_html( _x( 'm', 'm for minutes', BOOKACTI_PLUGIN_NAME ) ); ?>
					</div>
				</div>
				<div>
					<label for='bookacti-activity-resizable' ><?php esc_html_e( 'Change duration on calendar', BOOKACTI_PLUGIN_NAME ); ?></label>
					<?php
						$name	= 'activity-resizable';
						$id		= 'bookacti-activity-resizable';
						bookacti_onoffswitch( $name, 0, $id );
					
						$tip = __( "Allow to resize an event directly on calendar.", BOOKACTI_PLUGIN_NAME );
						$tip .= ' ' . __( "See more at", BOOKACTI_PLUGIN_NAME );
						$tip .= ' <a href="http://fullcalendar.io/docs/event_ui/eventDurationEditable/" target="_blank" >eventDurationEditable</a>';
						bookacti_help_tip( $tip );
					?>
				</div>
				<div>
					<label for='bookacti-activity-color' ><?php esc_html_e( 'Color', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='color' name='activity-color' id='bookacti-activity-color' value='#3a87ad' />
				</div>
			<?php
				do_action( 'bookacti_activity_tab_general_after', $params );
			}
			
			function bookacti_fill_activity_tab_terminology( $params ) {
				do_action( 'bookacti_activity_tab_terminology_before', $params );
			?>
				<div>
					<label for='bookacti-activity-unit-name-singular' ><?php esc_html_e( 'Unit name (singular)', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='text' name='activityOptions[unit_name_singular]' id='bookacti-activity-unit-name-singular' />
					<?php
						$tip = __( "Name of the unit the customers will actually book for this activity. Set the singular here. Leave blank to hide this piece of information. Ex: 'You have booked 1 <strong><em>unit</em></strong>'.", BOOKACTI_PLUGIN_NAME );
						bookacti_help_tip( $tip );
					?>
				</div>
				<div>
					<label for='bookacti-activity-unit-name-plural' ><?php esc_html_e( 'Unit name (plural)', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='text' name='activityOptions[unit_name_plural]' id='bookacti-activity-unit-name-plural' />
					<?php
						$tip = __( "Name of the unit the customers will actually book for this activity. Set the plural here. Leave blank to hide this piece of information. Ex: 'You have booked 2 <strong><em>units</em></strong>'.", BOOKACTI_PLUGIN_NAME );
						bookacti_help_tip( $tip );
					?>
				</div>
				<div>
					<?php /* translators: We are asking here if the user want to display the unit next to the total availability on the event. Ex: '14 units' instead of '14' */ ?>
					<label for='bookacti-activity-show-unit-in-availability' ><?php esc_html_e( 'Show unit in availability', BOOKACTI_PLUGIN_NAME ); ?></label>
					<?php
						$name	= 'activityOptions[show_unit_in_availability]';
						$id		= 'bookacti-activity-show-unit-in-availability';
						bookacti_onoffswitch( $name, 0, $id );
					
						$tip = __( "Show the unit in the availability boxes. Ex: '2 <strong><em>units</em></strong> available' instead of '2'.", BOOKACTI_PLUGIN_NAME );
						bookacti_help_tip( $tip );
					?>
				</div>
				<div>
					<label for='bookacti-activity-places-number' ><?php esc_html_e( 'Number of places per booking', BOOKACTI_PLUGIN_NAME ); ?></label>
					<input type='number' name='activityOptions[places_number]' id='bookacti-activity-places-number' min='0' />
					<?php
						$tip = __( "The number of persons who can do the activity with 1 booking. Set 0 to hide this piece of information. Ex: 'You have booked 1 unit for <em>2</em> persons'.", BOOKACTI_PLUGIN_NAME );
						bookacti_help_tip( $tip );
					?>
				</div>
			<?php
				do_action( 'bookacti_activity_tab_terminology_after', $params );
			}
		
			function bookacti_fill_activity_tab_permissions( $params ) {
				do_action( 'bookacti_activity_tab_permissions_before', $params );
			?>
				<div id='bookacti-activity-managers-container' class='bookacti-items-container' data-type='users' >
					<label id='bookacti-activity-managers-title' class='bookacti-fullwidth-label' >
						<?php esc_html_e( 'Who can manage this activity?', BOOKACTI_PLUGIN_NAME ); ?>
					</label>
					<div id='bookacti-add-activity-managers-container' >
						<select id='bookacti-add-new-activity-managers-select-box' class='bookacti-add-new-items-select-box' >
							<?php echo $params[ 'users_options_for_activities' ]; ?>
						</select>
						<button type='button' id='bookacti-add-activity-managers' class='bookacti-add-items' ><?php esc_html_e( 'Add manager', BOOKACTI_PLUGIN_NAME ); ?></button>
					</div>
					<div id='bookacti-activity-managers-list-container' class='bookacti-items-list-container' >
						<select name='activity-managers[]' id='bookacti-activity-managers-select-box' class='bookacti-items-select-box' multiple >
						</select>
						<button type='button' id='bookacti-remove-activity-managers' class='bookacti-remove-items' ><?php esc_html_e( 'Remove selected', BOOKACTI_PLUGIN_NAME ); ?></button>
					</div>
				</div>
				<div id='bookacti-activity-templates-container' class='bookacti-items-container' data-type='templates' >
					<label id='bookacti-activity-templates-title' class='bookacti-fullwidth-label' >
						<?php esc_html_e( 'Make this activity available on calendars:', BOOKACTI_PLUGIN_NAME ); ?>
					</label>
					<div id='bookacti-add-activity-templates-container' >
						<select id='bookacti-add-new-activity-templates-select-box' class='bookacti-add-new-items-select-box bookacti-template-select-box' >
							<?php echo $params[ 'templates_options' ]; ?>
						</select>
						<button type='button' id='bookacti-add-activity-templates' class='bookacti-add-items' ><?php esc_html_e( 'Add calendar', BOOKACTI_PLUGIN_NAME ); ?></button>
					</div>
					<div id='bookacti-activity-templates-list-container' class='bookacti-items-list-container' >
						<select name='activity-templates[]' id='bookacti-activity-templates-select-box' class='bookacti-items-select-box' multiple >
						</select>
						<button type='button' id='bookacti-remove-activity-templates' class='bookacti-remove-items' ><?php esc_html_e( 'Remove selected', BOOKACTI_PLUGIN_NAME ); ?></button>
					</div>
				</div>
			<?php
				do_action( 'bookacti_activity_tab_permissions_after', $params );
			}
			?>
	</form>
</div>

<!-- Locked event error -->
<div id='bookacti-unbind-booked-event-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs' >
    <div id='bookacti-unbind-booked-event-error-list-container' >
        <?php 
			/* translators: This is followed by "You can't:", and then a list of bans. */
			esc_html_e( 'There are bookings on at least one of the occurence of this event.', BOOKACTI_PLUGIN_NAME ); 
			/* translators: This is preceded by 'There are bookings on at least one of the occurence of this event.', and flollowed by a list of bans. */
			echo '<br/><b>' . esc_html__( 'You can\'t:', BOOKACTI_PLUGIN_NAME ) . '</b>'; 
		?>
        <ul></ul>
    </div>
    <div>
        <?php 
			/* translators: This is preceded by 'There are bookings on at least one of the occurence of this event. You can't: <list of bans>' and followed by "You can:", and then a list of capabilities. */
			esc_html_e( 'If you want to edit independantly the occurences of the event that are not booked:', BOOKACTI_PLUGIN_NAME );
			/* translators: This is preceded by 'There are bookings on at least one of the occurence of this event.', and flollowed by a list of capabilities. */
			echo '<br/><b>' . esc_html__( 'You can:', BOOKACTI_PLUGIN_NAME ) . '</b><br/>';
		?>
        <ul>
            <?php 
						/* translators: This is one of the capabilities following the text 'There are bookings on at least one of the occurence of this event. You can:'. */
				echo  '<li>' . esc_html__( 'Unbind the selected occurence only.', BOOKACTI_PLUGIN_NAME ) . '</li>'
						/* translators: This is one of the capabilities following the text 'There are bookings on at least one of the occurence of this event. You can:'. */
					. '<li>' . esc_html__( 'Unbind all the booked occurences.', BOOKACTI_PLUGIN_NAME ) . '</li>';
//						/* translators: This is one of the capabilities following the text 'There are bookings on at least one of the occurence of this event. You can:'. */
//				echo  '<li>' . __( 'Unbind all occurences.', BOOKACTI_PLUGIN_NAME ) . '</li>';
			?>
        </ul>
        <b><?php esc_html_e( 'Warning: These actions will be irreversibles after the first booking.', BOOKACTI_PLUGIN_NAME ); ?></b>
    </div>
</div>


<!-- Choose between creating a brand new activity or import an existing one -->
<div id='bookacti-activity-create-method-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs' >
    <div id='bookacti-activity-create-method-container' >
        <?php 
			/* translators: This is followed by "You can't:", and then a list of bans. */
			esc_html_e( 'Do you want to create a brand new activity or use on that calendar an activity you already created on an other calendar ?', BOOKACTI_PLUGIN_NAME ); 
		?>
    </div>
</div>


<!-- Import an existing activity -->
<div id='bookacti-activity-import-dialog' class='bookacti-backend-dialogs bookacti-template-dialogs' >
    <div id='bookacti-activity-import-container' >
		<div>
			<?php esc_html_e( 'Import an activity that you have already created on an other calendar:', BOOKACTI_PLUGIN_NAME ); ?>
		</div>
        <div id='bookacti-template-import-bound-activities' >
			<label for='template-import-bound-activities' >
				<?php 
				/* translators: the user is asked to select a calendar to display its bound activities. This is the label of the select box. */
				esc_html_e( 'From calendar', BOOKACTI_PLUGIN_NAME ); 
				?>
			</label>
			<select name="template-import-bound-activities" id='template-import-bound-activities' class='bookacti-template-select-box' >
				<?php echo $templates_options; ?>
			</select>
		</div>
        <div id='bookacti-activities-bound-to-template' >
			<label for='activities-to-import' >
				<?php 
				/* translators: the user is asked to select an activity he already created on an other calendar in order to use it on the current calendar. This is the label of the select box. */
				esc_html_e( 'Activities to import', BOOKACTI_PLUGIN_NAME ); 
				?>
			</label>
			<select name="activities-to-import" id='activities-to-import' multiple >
			</select>
		</div>
    </div>
</div>