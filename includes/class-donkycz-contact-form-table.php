<?php
/**
 * Data listing for contact form.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 */

if ( !class_exists( 'DonkyCz_Contact_Form_Table' ) ) :

/**
 * We have to include these because of 
 * {@link http://wordpress.org/support/topic/function-convert_to_screen-no-longer-exists-in-templatephp}.
 */
require_once ABSPATH . 'wp-admin/includes/template.php';

if ( ! class_exists( 'WP_Screen' ) ) {
	require_once ABSPATH . 'wp-admin/includes/screen.php';
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Implementation of data list table.
 *
 * @since 0.1
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 * @see WP_List_Table Parent WordPress class.
 */
class DonkyCz_Contact_Form_Table extends WP_List_Table {
	/**
	 * Returns bulk actions.
	 * 
	 * @access public
	 * @see WP_List_Table::get_bulk_actions()
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Smazat', DonkyCz::SLUG ),
			'read' => __( 'Přečíst', DonkyCz::SLUG )
		);
		return $actions;
	}
	
	/**
	 * @access public
	 * @see WP_List_Table::get_columns()
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox"/>',
			'id' => __( 'ID', DonkyCz::SLUG ),
			'created' => __( 'Odesláno', DonkyCz::SLUG ),
			'sender' => __( 'Odesílatel', DonkyCz::SLUG ),
			'email' => __( 'E-mail', DonkyCz::SLUG ),
			'message' => __( 'Zpráva', DonkyCz::SLUG ),
			'toy_id' => __( 'Hračka ID', DonkyCz::SLUG ),
			'toy_spec' => __( 'Hračka spec.', DonkyCz::SLUG ),
			'read' => __( 'Přečteno', DonkyCz::SLUG )
		);
		return $columns;
	}

	/**
	 * @access protected
	 * @todo Check `$_POST['s']` if didn't performed search.
	 * @return array
	 */
	protected function get_data() {
		$data = DonkyCz_Contact_Form_Model::find_all();
		return $data;
	}

	/**
	 * @access public
	 * @see WP_List_Table::get_sortable_columns()
	 * @return array
	 */
	public function get_sortable_columns() {
		$columns = array(
			'id' => array( 'id', false ),
			'created' => array( 'created', false ),
			'sender' => array( 'sender', false ),
			'email' => array( 'email', false ),
			'message' => array( 'message', false ),
			'toy_id' => array( 'toy_id', false ),
			'toy_spec' => array( 'toy_spec', false ),
			'read' => array( 'read', false )
		);
		return $columns;
	}

	/**
	 * @access public
	 * @see WP_List_Table::prepare_items()
	 * @todo Value of `$per_page` should be loaded from plugin settings.
	 * @return void
	 */
	public function prepare_items() {
		// Column headers
		$this->_column_headers = $this->get_column_info();
		// Get data
		$data = $this->get_data();
		// Sorting
		usort( $data, array( &$this, 'usort_reorder' ) );
		// Pagination
		$per_page = $this->get_items_per_page( 'contacts_per_page', 8 );
		$this->set_pagination_args( array( 'total_items' => count( $data ), 'per_page' => $per_page ) );
		$current_page = $this->get_pagenum();
		// Set items to display
		if ( count( $data ) > $per_page ) {
			$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		}
		$this->items = $data;
	}

	/**
	 * @access public
	 * @param object $item Data row.
	 * @return string Returns HTML for the checkbox column.
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="contact[]" value="%s" />', $item->id );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `id`.
	 */
	public function column_id( $item ) {
		return sprintf( '<code>%d</code>', intval( $item->id ) );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `created`.
	 */
	public function column_created( $item ) {
		$actions = array(
			'delete' => sprintf( '<a href="?page=%s&action=%s&contact=%s">' . __( 'Smazat', DonkyCz::SLUG ) . '</a>', $_REQUEST['page'], 'delete', $item->id ),
		);

		if ( (bool) $item->read === true ) {
			$actions['read'] = sprintf( '<a href="?page=%s&action=%s&contact=%s">' . __( 'Označit jako nepřečtené', DonkyCz::SLUG ) . '</a>', $_REQUEST['page'], 'unread', $item->id );
		} else {
			$actions['read'] = sprintf( '<a href="?page=%s&action=%s&contact=%s">' . __( 'Označit jako přečtené', DonkyCz::SLUG ) . '</a>', $_REQUEST['page'], 'read', $item->id );
		}
		// XXX Format date using correct WP function!!!
		$created = date( 'j.n.Y', strtotime( $item->created ) ); 

		return sprintf( '%1$s %2$s', $created, $this->row_actions( $actions ) );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `sender`.
	 */
	public function column_sender( $item ) {
		if ( empty( $item->sender ) ) {
			return '';
		}
		return sprintf( '<b>%s</b>', $item->sender );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `email`.
	 */
	public function column_email( $item ) {
		if ( empty( $item->email ) ) {
			return '';
		}
		return sprintf( '<a href="mailto:%1$s">%1$s</a>', $item->email );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `message`.
	 */
	public function column_message( $item ) {
		if ( empty( $item->message ) ) {
			return '';
		}
		return sprintf( '<p>%s</p>', $item->message );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `toy_id`.
	 */
	public function column_toy_id( $item ) {
		if ( intval( $item->toy_id ) <= 0 ) {
			return '';
		}
		return sprintf( '<p>%d</p>', intval( $item->toy_id ) );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `toy_spec`.
	 */
	public function column_toy_spec( $item ) {
		if ( empty( $item->toy_spec ) ) {
			return '';
		}
		return sprintf( '<p>%s</p>', $item->toy_spec );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `read`.
	 */
	public function column_read( $item ) {
		if ( (bool) $item->read === true ) {
			return '<span class="read">' . __( 'Ano', DonkyCz::SLUG ) . '</span>';
		}
		return '<span class="not-read">' . __( 'Ne', DonkyCz::SLUG ) . '</span>';
	}

	/**
	 * Print message when no data items are found.
	 * 
	 * @access public
	 * @see WP_List_Table::no_items()
	 */
	public function no_items() {
		_e( 'V databázi nejsou prozatím uloženy žádné odeslané dotazy.', DonkyCz::SLUG );
	}

	/**
	 * @access public
	 * @param object $a First data row.
	 * @param object $b Second data row.
	 * @return int
	 */
	public function usort_reorder( $a, $b ) {
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
		// If no order, default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';
		// Determine sort order
		if ( $orderby == 'id' ) {
			if ( $a->id == $b->id ) {
				$result = 0;
			} else {
				$result = ( $a->id > $b->id ) ? 1 : -1;
			}
		} else {
			$result = strcmp( $a->{$orderby}, $b->{$orderby} );
		}
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}

	/**
	 * Add screen options.
	 *
	 * @access public
	 * @global DonkyCz_Contact_Form_Table $donkycz_contact_form_table
	 * @uses add_screen_option()
	 */
	public static function add_screen_options() {
		global $donkycz_contact_form_table;
		
		$option = 'per_page';
		$args = array(
			'label' => __( 'Záznamů na stránce', DonkyCz::SLUG ),
			'default' => 8,
			'option' => 'contacts_per_page'
		);
		add_screen_option( $option, $args );
		
		$donkycz_contact_form_table = new DonkyCz_Contact_Form_Table();
	}

	/**
	 * Set screen option.
	 *
	 * @access public
	 * @param string $status
	 * @param string $option
	 * @param mixed $value
	 * @return mixed
	 */
	public static function set_screen_options( $status, $option, $value ) {
		return $value;
	}

	/**
	 * Hide some columns in toys list by default.
	 *
	 * @access public
	 * @param string $user_login
	 * @param WP_User $user
	 * @uses get_user_option()
	 * @uses update_user_option()
	 */
	public static function set_default_hidden_columns( $user_login, $user ) {
		$metakey = 'managetoy_page_odwpdcz-data_pagecolumnshidden';
		$hidden_columns = get_user_option( $metakey, $user->ID );

		if ( is_array( $hidden_columns ) ) {
			return;
		}

		$hidden_columns = array();
		$hidden_columns[] = 'id';

		update_user_option( $user->ID, $metakey, $hidden_columns, true );
	}
}

endif;
