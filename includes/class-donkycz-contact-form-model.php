<?php
/**
 * The file with database model for contact form.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 */

if ( !class_exists( 'DonkyCz_Contact_Form_Model' ) ) :

/**
 * Model class for contact form.
 *
 * @since 0.1
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz_Contact_Form_Model {
	/**
	 * @const string Holds our database table name.
	 */
	const TABLE_NAME = 'contact_form';

	/**
	 * @var integer $id
	 */
	public $id;

	/**
	 * @var string $sender Full name of the sender.
	 */
	public $sender;

	/**
	 * @var string $email Email address of the sender.
	 */
	public $email;

	/**
	 * @var string $message
	 */
	public $message;

	/**
	 * @var integer $toy_id ID of requested toy.
	 */
	public $toy_id;

	/**
	 * @var string $toy_spec Specification of requested toy (if `$toy_id` is not set).
	 */
	public $toy_spec;

	/**
	 * @var string $created Date time of record creation.
	 */
	public $created;

	/**
	 * @var boolean $read `TRUE` if administrator read the record.
	 */
	public $read;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param array|object $data (Optional.)
	 * @return void
	 * @since 0.1
	 */
	public function __construct( $data = null ) {
		if ( is_array( $data ) ) {
			$this->exchange_data_array( $data );
		}
		else if ( is_object( $data ) ) {
			$this->exchange_data_object( $data );
		}
	}

	/**
	 * @access private
	 * @param array $data
	 * @since 0.1
	 */
	private function exchange_data_array( $data ) {
		if ( array_key_exists( 'id', $data ) ) {
			$this->id = (int) $data['id'];
		}

		if ( array_key_exists( 'sender', $data ) ) {
			$this->sender = $data['sender'];
		}

		if ( array_key_exists( 'email', $data ) ) {
			$this->email = $data['email'];
		}

		if ( array_key_exists( 'message', $data ) ) {
			$this->message = $data['message'];
		}

		if ( array_key_exists( 'toy_id', $data ) ) {
			$this->toy_id = ( ! empty ( $data['toy_id'] ) ) ? (int) $data['toy_id'] : null;
		}

		if ( array_key_exists( 'toy_spec', $data ) ) {
			$this->toy_spec = $data['toy_spec'];
		}

		if ( array_key_exists( 'created', $data ) ) {
			$this->created = $data['created'];
		}

		if ( array_key_exists( 'read', $data ) ) {
			$this->read = (bool) $data['read'];
		}
	}

	/**
	 * @access private
	 * @param object $data
	 * @since 0.1
	 */
	private function exchange_data_object( $data ) {
		if ( property_exists( $data, 'id' ) ) {
			$this->id = (int) $data->id;
		}

		if ( property_exists( $data, 'sender' ) ) {
			$this->sender = $data->sender;
		}

		if ( property_exists( $data, 'email' ) ) {
			$this->email = $data->email;
		}

		if ( property_exists( $data, 'message' ) ) {
			$this->message = $data->message;
		}

		if ( property_exists( $data, 'toy_id' ) ) {
			$this->toy_id = ( ! empty ( $data->toy_id ) ) ? (int) $data->toy_id : null;
		}

		if ( property_exists( $data, 'toy_spec' ) ) {
			$this->toy_spec = $data->toy_spec;
		}

		if ( property_exists( $data, 'created' ) ) {
			$this->created = $data->created;
		}

		if ( property_exists( $data,  'read' ) ) {
			$this->read = (bool) $data->read;
		}
	}

	/**
	 * Save record into the database.
	 * 
	 * @access public
	 * @global wpdb $wpdb
	 * @return boolean
	 * @since 0.1
	 */
	public function save() {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;

		if ( ( int ) $this->id <= 0 ) {
			$wpdb->insert(
				$table_name,
				array(
					'sender'   => $this->sender,
					'email'    => $this->email,
					'message'  => $this->message,
					'toy_id'   => ( ! empty ( $this->toy_id ) ) ? (int) $this->toy_id : null,
					'toy_spec' => $this->toy_spec,
					'created'  => ( ! empty ( $this->created ) ) ? $this->created : date( 'Y-m-d H:i:s' ),
					'read'     => (int) $this->read
				),
				array( '%s', '%s', '%s', '%d', '%s', '%s', '%d' )
			);

			$this->id = $wpdb->insert_id;

			return ( ( int ) $this->id > 0 );
		}

		$res = $wpdb->update(
			$table_name,
			array(
				'sender'   => $this->sender,
				'email'    => $this->email,
				'message'  => $this->message,
				'toy_id'   => ( ! empty ( $this->toy_id ) ) ? (int) $this->toy_id : null,
				'toy_spec' => $this->toy_spec,
				'created'  => ( ! empty ( $this->created ) ) ? $this->created : date( 'Y-m-d H:i:s' ),
				'read'     => (int) $this->read
			),
			array( 'id' => $this->id ),
			array( '%s', '%s', '%s', '%d', '%s', '%s', '%d' ),
			array( '%d' )
		);

		return ( $res !== false );
	}

	/**
	 * Create our table.
	 * 
	 * @access public
	 * @global wpdb $wpdb
	 * @return void
	 * @since 0.1
	 * @static
	 * @uses dbDelta()
	 */
	public static function create_table() {
		global $wpdb;

		// Create our database table if needed
		$table_name = $wpdb->prefix . self::TABLE_NAME;
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( 'SHOW TABLES LIKE "'.$table_name.'" ' ) != $table_name ) {
			$sql = <<<EOT
CREATE TABLE `$table_name` (
	`id` INTEGER ( 20 ) NOT NULL AUTO_INCREMENT ,
	`sender` VARCHAR ( 255 ) NOT NULL ,
	`email` VARCHAR ( 255 ) NOT NULL ,
	`message` TEXT NULL ,
	`toy_id` INTEGER ( 20 ) NULL ,
	`toy_spec` TEXT NULL ,
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	`read` INTEGER ( 1 ) NOT NULL DEFAULT 0 COMMENT "1 if record is read by administrator, otherwise 0." ,
	PRIMARY KEY `id` ( `id` )
) $charset_collate;
EOT;

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}
	}

	/**
	 * Find all records.
	 * 
	 * @access public
	 * @global wpdb $wpdb
	 * @param boolean $read (Optional.)
	 * @return array
	 * @since 0.1
	 * @static
	 */
	public static function find_all( $read = null ) {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;
		$sql = "SELECT * FROM `$table_name` WHERE 1 ";

		if ( $read === false ) {
			$sql .= ' AND ( `read` IS NULL OR `read` = 0 ) ';
		}

		$arr = $wpdb->get_results( $sql );
		$ret = array();

		if ( ! is_array( $arr ) ) {
			return $ret;
		}

		foreach ( $arr as $obj ) {
			$ret[] = new self( $obj );
		}

		return $ret;
	}

	/**
	 * Find record by its ID.
	 * 
	 * @access public
	 * @global wpdb $wpdb
	 * @param integer $id
	 * @return mixed
	 * @since 0.1
	 * @static
	 */
	public static function findy_by_id( $id ) {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;
		$sql = "SELECT * FROM `$table_name` WHERE `id` = %d ";
		$obj = $wpdb->get_row( $wpdb->prepare( $sql, $id ) );

		if ( ! is_object( $obj ) ) {
			return null;
		}

		return new self( $obj );
	}

	/**
	 * Find record by the sender column.
	 * 
	 * @access public
	 * @global wpdb $wpdb
	 * @param string $sender
	 * @param boolean $read (Optional.)
	 * @return mixed
	 * @since 0.1
	 * @static
	 */
	public static function find_by_sender( $sender, $read = null ) {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;
		$sql  = "SELECT * FROM `$table_name` WHERE `sender` LIKE \"%s\" ";

		if ( $read === false ) {
			$sql .= ' AND ( `read` IS NULL OR `read` = 0 ) ';
		}

		$rows = $wpdb->get_results( $wpdb->prepare( $sql, $sender ) );
		$ret  = array();

		if ( $rows ) {
			foreach ( $rows as $row ) {
				$ret[] = new self( $row );
			}
		}

		return $ret;
	}

	/**
	 * Find record by the email column.
	 * 
	 * @access public
	 * @global wpdb $wpdb
	 * @param string $sender
	 * @param boolean $read (Optional.)
	 * @return mixed
	 * @since 0.1
	 * @static
	 */
	public static function find_by_email( $email, $read = null ) {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;
		$sql  = "SELECT * FROM `$table_name` WHERE `email` LIKE \"%s\" ";

		if ( $read === false ) {
			$sql .= ' AND ( `read` IS NULL OR `read` = 0 ) ';
		}

		$rows = $wpdb->get_results( $wpdb->prepare( $sql, $email ) );
		$ret  = array();

		if ( $rows ) {
			foreach ( $rows as $row ) {
				$ret[] = new self( $row );
			}
		}

		return $ret;
	}

	/**
	 * Find record by the toy_id column.
	 * 
	 * @access public
	 * @global wpdb $wpdb
	 * @param string $sender
	 * @param boolean $read (Optional.)
	 * @return mixed
	 * @since 0.1
	 * @static
	 */
	public static function find_by_toy_id( $toy_id, $read = null ) {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;
		$sql  = "SELECT * FROM `$table_name` WHERE `toy_id` = %s ";

		if ( $read === false ) {
			$sql .= ' AND ( `read` IS NULL OR `read` = 0 ) ';
		}

		$rows = $wpdb->get_results( $wpdb->prepare( $sql, $email ) );
		$ret  = array();

		if ( $rows ) {
			foreach ( $rows as $row ) {
				$ret[] = new self( $row );
			}
		}

		return $ret;
	}
}

endif;