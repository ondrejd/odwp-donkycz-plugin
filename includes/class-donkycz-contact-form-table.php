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
	} // end get_bulk_actions()
	
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
	} // end get_columns()
	
	/**
	 * @access protected
	 * @todo Check `$_POST['s']` if didn't performed search.
	 * @return array
	 */
	protected function get_data() {
		return DonkyCz_Contact_Form_Model::find_all();
	} // end get_data()

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
	} // end get_sortable_columns()
	
	/**
	 * @access public
	 * @see WP_List_Table::prepare_items()
	 * @todo Value of `$per_page` should be loaded from plugin settings.
	 * @return void
	 */
	public function prepare_items()
	{
		//$columns = $this->get_columns();
		//$hidden = array();
		//$sortable = $this->get_sortable_columns();
		// Column headers
		//$this->_column_headers = array($columns, $hidden, $sortable);
		$this->_column_headers = $this->get_column_info();
		// Get data
		$data = $this->get_data();
		// Sorting
		usort($data, array(&$this, 'usort_reorder'));
		// Pagination
		$current_page = $this->get_pagenum();
		//$per_page = 8; // XXX Should be set by user settings.
		$per_page = $this->get_items_per_page('contacts_per_page', 8);
		$this->found_data = array_slice($data, (($current_page-1) * $per_page), $per_page);
		$this->set_pagination_args(array(
			'total_items' => count($data),
			'per_page' => $per_page
		));
		$this->items = $this->found_data;
	} // end prepare_items()

/*
'sender'   => $this->sender,
'email'    => $this->email,
'message'  => $this->message,
'toy_id'   => ( ! empty ( $this->toy_id ) ) ? (int) $this->toy_id : null,
'toy_spec' => $this->toy_spec,
'created'  => ( ! empty ( $this->created ) ) ? $this->created : date( 'Y-m-d H:i:s' ),
'read'     => (int) $this->read
*/
	
	/**
	 * @access public
	 * @param object $item Data row.
	 * @param string $column_name Column name.
	 * @see WP_List_Table::column_default()
	 * @return void
	 */
	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'created': 
			case 'sender':
			case 'email':
			case 'message':
			case 'toy_id':
			case 'toy_spec':
			case 'read':
				return $item->{$column_name};
			default:
				// Show the whole array for troubleshooting purposes
				return print_r( $item, true ) ; 
		}
	} // end column_default( $item, $column_name ) 
	
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
	} // end usort_reorder( $a, $b )
	
	/**
	 * Renders the first column with checkbox.
	 * 
	 * @access public
	 * @param object $item Data row.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="contact[]" value="%s" />', $item->id );
	} // end column_cb( $item ) 
	
	/**
	 * Renders `created` column.
	 * 
	 * @access public
	 * @param object $item Data row.
	 * @return string
	 */
	public function column_created( $item ) {
		$actions = array(
			'edit'   => sprintf( '<a href="?page=%s&action=%s&contact=%s">' . __( 'Upravit', DonkyCz::SLUG ) . '</a>', $_REQUEST['page'], 'edit', $item->id ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&contact=%s">' . __( 'Smazat', DonkyCz::SLUG ) . '</a>', $_REQUEST['page'], 'delete', $item->id ),
		);

		return sprintf( '%1$s %2$s', $item->created, $this->row_actions( $actions ) );
	} // end column_created( $item )
	
	/**
	 * Print message when no data items are found.
	 * 
	 * @access public
	 * @see WP_List_Table::no_items()
	 */
	public function no_items() {
		_e( 'V databázi nejsou prozatím uloženy žádné odeslané dotazy.', DonkyCz::SLUG );
	} // end no_items()

	/**
	 * ...
	 *
	 * @access public
	 * @global DonkyCz_Contact_Form_Table $donkycz_contact_form_table
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
	} // end add_screen_options()

	/**
	 * ...
	 *
	 * @access public
	 * @param string $status
	 * @param string $option
	 * @param mixed $value
	 */
	public static function set_screen_options( $status, $option, $value ) {
		return $value;
	} // end set_screen_options( $status, $option, $value )

} // End of DonkyCz_Contact_Form_Table

endif;
