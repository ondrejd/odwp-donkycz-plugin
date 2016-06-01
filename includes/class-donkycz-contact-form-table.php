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
	 * @access protected
	 * @var array $available_views
	 */
	protected $available_views;

	public function __construct() {
		global $status, $page;                

		$this->available_views = array(
			'all'    => __( 'Všechny', DonkyCz::SLUG ),
			'read'   => __( 'Přečtené', DonkyCz::SLUG ),
			'unread' => __( 'Nepřečtené', DonkyCz::SLUG ),
			'trash'  => __( 'Smazané', DonkyCz::SLUG )
		);

		parent::__construct( array(
			'singular' => __( 'zpráva', DonkyCz::SLUG ),  
			'plural'   => __( 'zprávy', DonkyCz::SLUG ),
			'ajax'     => false      
		) );
	}

	/**
	 * @access public
	 * @return string Returns key of the currently selected view.
	 */
	public function get_current_view() {
		$view = filter_input( INPUT_GET, 'view' );

		if ( empty( $view ) ) {
			$view = filter_input( INPUT_POST, 'view' );

			if ( empty ( $view ) ) {
				$view = 'all';
			}
		}

		return $view;
	}

	/**
	 * @access protected
	 * @see WP_List_Table::get_views()
	 * @return array Returns available list table views.
	 * @todo Get count for all views and display it.
	 */
	protected function get_views() {
		$url = 'edit.php?post_type=toy&amp;page=odwpdcz-data_page&amp;view=';
		$current = $this->get_current_view();
		$links = array();

		foreach ( $this->available_views as $view => $label ) {
			$html = '<a href="%2$s"%3$s>%1$s <span class="count">(%4$d)</span></a>';
			$class = ( $view == $current ) ? ' class="current"' : '';
			$count = 0;

			$links[$view] = sprintf( $html, $label, admin_url( $url . $view ), $class, $count );
		}

		return $links;
	}

	/**
	 * @access public
	 * @see WP_List_Table::get_bulk_actions()
	 * @return array Returns bulk actions.
	 */
	public function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Smazat', DonkyCz::SLUG ),
			'read'   => __( 'Přečíst', DonkyCz::SLUG ),
			'unread' => __( 'Zrušit přečtení', DonkyCz::SLUG )
		);
		return $actions;
	}
	
	/**
	 * @access public
	 * @see WP_List_Table::get_columns()
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb' => '<input type="checkbox"/>',
			'id' => __( 'ID', DonkyCz::SLUG ),
			'created' => __( 'Odesláno', DonkyCz::SLUG ),
			'sender' => __( 'Odesílatel', DonkyCz::SLUG ),
			'email' => __( 'E-mail', DonkyCz::SLUG ),
			'message' => __( 'Zpráva', DonkyCz::SLUG ),
			'toy_id' => __( 'Hračka', DonkyCz::SLUG ),
			'toy_spec' => __( 'Hračka spec.', DonkyCz::SLUG ),
			'read' => __( '<abbr title="Přečteno">P.</abbr>', DonkyCz::SLUG )
		);
	}

	/**
	 * @access protected
	 * @todo Check `$_POST['s']` if didn't performed search.
	 * @return array
	 */
	protected function get_data() {
		return DonkyCz_Contact_Form_Model::find_all();
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
			//'message' => array( 'message', false ),
			'toy_id' => array( 'toy_id', false ),
			//'toy_spec' => array( 'toy_spec', false ),
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
		// TODO Format date using correct WP function!!!
		$created = date( 'j.n.Y', strtotime( $item->created ) );

		return sprintf( '<span class="date">%1$s</span>', $created );
	}

	/**
	 * @access public
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the column `sender`.
	 */
	public function column_sender( $item ) {
		$actions = $this->prepare_row_actions( $item );
		$sender = $item->sender;

		if ( empty( $sender ) ) {
			$sender = '---';
		}

		return sprintf( '<b>%1$s</b> %2$s', $sender, $actions );
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

		$toy = DonkyCz_Custom_Post_Type_Toy::find_by_id( $item->toy_id );

		if ( ( $toy instanceof WP_Post ) ) {
			$url = admin_url( 'post.php?post=' . $toy->ID . '&action=edit' );
			return sprintf( '<a href="%s">%s</a>', $url, $toy->post_title ); 
		}

		return sprintf( '<i>%d</i>', intval( $item->toy_id ) );
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
	 * Prepares row actions.
	 *
	 * @access protected
	 * @param DonkyCz_Contact_Form_Model $item Data entity.
	 * @return string Returns HTML for the row actions.
	 * @uses admin_url()
	 */
	protected function prepare_row_actions( $item ) {
		$link = '<a href="%1$s">%2$s</a>';
		$actions = array(
			'delete' => sprintf( $link, admin_url( 'edit.php?post_type=' . DonkyCz_Custom_Post_Type_Toy::TYPE . '&page=odwpdcz-data_page&action=delete&post_id=' . $item->id ), __( 'Smazat', DonkyCz::SLUG ) ),
		);

		if ( (bool) $item->read === true ) {
			$actions['unread'] = sprintf( $link, admin_url( 'edit.php?post_type=' . DonkyCz_Custom_Post_Type_Toy::TYPE . '&page=odwpdcz-data_page&action=unread&post_id=' . $item->id ), __( 'Nepřečteno', DonkyCz::SLUG ) );
		} else {
			$actions['read'] = sprintf( $link, admin_url( 'edit.php?post_type=' . DonkyCz_Custom_Post_Type_Toy::TYPE . '&page=odwpdcz-data_page&action=read&post_id=' . $item->id ), __( 'Přečíst', DonkyCz::SLUG ) );
		}

		return $this->row_actions( array_reverse( $actions ) );
	}
}

endif;
