<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Locale
 */


namespace Aimeos\MShop\Locale\Manager\Language;


/**
 * Default implementation for managing languages.
 *
 * @package MShop
 * @subpackage Locale
 */
class Standard
	extends \Aimeos\MShop\Common\Manager\Base
	implements \Aimeos\MShop\Locale\Manager\Language\Iface
{
	private $searchConfig = array(
		'locale.language.id' => array(
			'code' => 'locale.language.id',
			'internalcode' => 'mlocla."id"',
			'internaldeps' => array( 'LEFT JOIN "mshop_locale_language" AS mlocla ON (mloc."langid" = mlocla."id")' ),
			'label' => 'Locale language ID',
			'type' => 'string',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
			'public' => false,
		),
		'locale.language.siteid' => array(
			'code' => 'locale.language.siteid',
			'internalcode' => 'mlocla."siteid"',
			'label' => 'Locale language site ID',
			'type' => 'string',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
			'public' => false,
		),
		'locale.language.label' => array(
			'code' => 'locale.language.label',
			'internalcode' => 'mlocla."label"',
			'label' => 'Locale language label',
			'type' => 'string',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
		),
		'locale.language.code' => array(
			'code' => 'locale.language.code',
			'internalcode' => 'mlocla."id"',
			'label' => 'Locale language code',
			'type' => 'string',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
		),
		'locale.language.status' => array(
			'code' => 'locale.language.status',
			'internalcode' => 'mlocla."status"',
			'label' => 'Locale language status',
			'type' => 'integer',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
		),
		'locale.language.ctime'=> array(
			'code'=>'locale.language.ctime',
			'internalcode'=>'mlocla."ctime"',
			'label'=>'Locale language create date/time',
			'type'=> 'datetime',
			'internaltype'=> \Aimeos\MW\DB\Statement\Base::PARAM_STR
		),
		'locale.language.mtime'=> array(
			'code'=>'locale.language.mtime',
			'internalcode'=>'mlocla."mtime"',
			'label'=>'Locale language modification date/time',
			'type'=> 'datetime',
			'internaltype'=> \Aimeos\MW\DB\Statement\Base::PARAM_STR
		),
		'locale.language.editor'=> array(
			'code'=>'locale.language.editor',
			'internalcode'=>'mlocla."editor"',
			'label'=>'Locale language editor',
			'type'=> 'string',
			'internaltype'=> \Aimeos\MW\DB\Statement\Base::PARAM_STR
		),
	);


	/**
	 * Initializes the object.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 */
	public function __construct( \Aimeos\MShop\Context\Item\Iface $context )
	{
		parent::__construct( $context );
		$this->setResourceName( 'db-locale' );
	}


	/**
	 * Creates a new language object.
	 *
	 * @return \Aimeos\MShop\Locale\Item\Language\Standard
	 */
	public function createItem()
	{
		try {
			$values = array( 'siteid' => $this->getContext()->getLocale()->getSiteId() );
		} catch( \Exception $ex ) {
			$values = array( 'siteid' => null );
		}

		return $this->createItemBase( $values );
	}


	/**
	 * Saves the language object to the storage.
	 *
	 * @param \Aimeos\MShop\Common\Item\Iface $item Language object
	 * @param boolean $fetch True if the new ID should be returned in the item
	 */
	public function saveItem( \Aimeos\MShop\Common\Item\Iface $item, $fetch = true )
	{
		$iface = '\\Aimeos\\MShop\\Locale\\Item\\Language\\Iface';
		if( !( $item instanceof $iface ) ) {
			throw new \Aimeos\MShop\Locale\Exception( sprintf( 'Object is not of required type "%1$s"', $iface ) );
		}

		if( !$item->isModified() ) { return; }

		$context = $this->getContext();

		$dbm = $context->getDatabaseManager();
		$dbname = $this->getResourceName();
		$conn = $dbm->acquire( $dbname );

		try
		{
			$id = $item->getId();
			$date = date( 'Y-m-d H:i:s' );

			if( $id === null )
			{
				/** mshop/locale/manager/language/standard/insert
				 * Inserts a new language record into the database table
				 *
				 * The SQL statement must be a string suitable for being used as
				 * prepared statement. It must include question marks for binding
				 * the values from the log item to the statement before they are
				 * sent to the database server. The number of question marks must
				 * be the same as the number of columns listed in the INSERT
				 * statement. The order of the columns must correspond to the
				 * order in the saveItems() method, so the correct values are
				 * bound to the columns.
				 *
				 * The SQL statement should conform to the ANSI standard to be
				 * compatible with most relational database systems. This also
				 * includes using double quotes for table and column names.
				 *
				 * @param string SQL statement for inserting records
				 * @since 2014.03
				 * @category Developer
				 * @see mshop/locale/manager/language/standard/update
				 * @see mshop/locale/manager/language/standard/delete
				 * @see mshop/locale/manager/language/standard/search
				 * @see mshop/locale/manager/language/standard/count
				 */
				$path = 'mshop/locale/manager/language/standard/insert';
			}
			else
			{
				/** mshop/locale/manager/language/standard/update
				 * Updates an existing language record in the database
				 *
				 * The SQL statement must be a string suitable for being used as
				 * prepared statement. It must include question marks for binding
				 * the values from the log item to the statement before they are
				 * sent to the database server. The order of the columns must
				 * correspond to the order in the saveItems() method, so the
				 * correct values are bound to the columns.
				 *
				 * The SQL statement should conform to the ANSI standard to be
				 * compatible with most relational database systems. This also
				 * includes using double quotes for table and column names.
				 *
				 * @param string SQL statement for updating records
				 * @since 2014.03
				 * @category Developer
				 * @see mshop/locale/manager/language/standard/insert
				 * @see mshop/locale/manager/language/standard/delete
				 * @see mshop/locale/manager/language/standard/search
				 * @see mshop/locale/manager/language/standard/count
				 */
				$path = 'mshop/locale/manager/language/standard/update';
			}

			$stmt = $this->getCachedStatement( $conn, $path );

			$stmt->bind( 1, $item->getLabel() );
			$stmt->bind( 2, $item->getStatus(), \Aimeos\MW\DB\Statement\Base::PARAM_INT );
			$stmt->bind( 3, $item->getSiteId(), \Aimeos\MW\DB\Statement\Base::PARAM_INT );
			$stmt->bind( 4, $date ); // mtime
			$stmt->bind( 5, $context->getEditor() );
			// code and ID are identical after saving and ID is the flag to detect updates or inserts
			$stmt->bind( 6, $item->getCode() );

			if( $id === null ) {
				$stmt->bind( 7, $date ); // ctime
			}

			$stmt->execute()->finish();

			$item->setId( $item->getCode() ); // set modified flag to false

			$dbm->release( $conn, $dbname );
		}
		catch( \Exception $e )
		{
			$dbm->release( $conn, $dbname );
			throw $e;
		}
	}


	/**
	 * Removes multiple items specified by ids in the array.
	 *
	 * @param array $ids List of IDs
	 */
	public function deleteItems( array $ids )
	{
		/** mshop/locale/manager/language/standard/delete
		 * Deletes the items matched by the given IDs from the database
		 *
		 * Removes the language records specified by the given IDs from the
		 * locale database. The records must be from the site that is configured
		 * via the context item.
		 *
		 * The ":cond" placeholder is replaced by the name of the ID column and
		 * the given ID or list of IDs while the site ID is bound to the question
		 * mark.
		 *
		 * The SQL statement should conform to the ANSI standard to be
		 * compatible with most relational database systems. This also
		 * includes using double quotes for table and column names.
		 *
		 * @param string SQL statement for deleting items
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/locale/manager/language/standard/insert
		 * @see mshop/locale/manager/language/standard/update
		 * @see mshop/locale/manager/language/standard/search
		 * @see mshop/locale/manager/language/standard/count
		 */
		$path = 'mshop/locale/manager/language/standard/delete';
		$this->deleteItemsBase( $ids, $path );
	}


	/**
	 * Create a Language object from a given Language ID/Key.
	 *
	 * @param string $id Language id to create the Language object
	 * @return \Aimeos\MShop\Locale\Item\Language\Iface Returns the language item of the given id
	 * @throws \Aimeos\MW\DB\Exception If language object couldn't be fetched
	 * @throws \Aimeos\MShop\Exception If item couldn't be found
	 */
	public function getItem( $id, array $ref = array() )
	{
		return $this->getItemBase( 'locale.language.id', $id, $ref );
	}


	/**
	 * Returns the attributes that can be used for searching.
	 *
	 * @param boolean $withsub Return also attributes of sub-managers if true
	 * @return array List of attribute items implementing \Aimeos\MW\Common\Criteria\Attribute\Iface
	 */
	public function getSearchAttributes( $withsub = true )
	{
		/** mshop/locale/manager/language/submanagers
		 * List of manager names that can be instantiated by the locale language manager
		 *
		 * Managers provide a generic interface to the underlying storage.
		 * Each manager has or can have sub-managers caring about particular
		 * aspects. Each of these sub-managers can be instantiated by its
		 * parent manager using the getSubManager() method.
		 *
		 * The search keys from sub-managers can be normally used in the
		 * manager as well. It allows you to search for items of the manager
		 * using the search keys of the sub-managers to further limit the
		 * retrieved list of items.
		 *
		 * @param array List of sub-manager names
		 * @since 2014.03
		 * @category Developer
		 */
		$path = 'mshop/locale/manager/language/submanagers';

		return $this->getSearchAttributesBase( $this->searchConfig, $path, array(), $withsub );
	}


	/**
	 * Returns a new sub manager of the given type and name.
	 *
	 * @param string $manager Name of the sub manager type in lower case
	 * @param string|null $name Name of the implementation, will be from configuration (or Default) if null
	 * @return \Aimeos\MShop\Locale\Manager\Iface manager
	 */
	public function getSubManager( $manager, $name = null )
	{
		/** mshop/locale/manager/language/name
		 * Class name of the used locale language manager implementation
		 *
		 * Each default locale language manager can be replaced by an alternative imlementation.
		 * To use this implementation, you have to set the last part of the class
		 * name as configuration value so the manager factory knows which class it
		 * has to instantiate.
		 *
		 * For example, if the name of the default class is
		 *
		 *  \Aimeos\MShop\Locale\Manager\Language\Standard
		 *
		 * and you want to replace it with your own version named
		 *
		 *  \Aimeos\MShop\Locale\Manager\Language\Mylanguage
		 *
		 * then you have to set the this configuration option:
		 *
		 *  mshop/locale/manager/language/name = Mylanguage
		 *
		 * The value is the last part of your own class name and it's case sensitive,
		 * so take care that the configuration value is exactly named like the last
		 * part of the class name.
		 *
		 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
		 * characters are possible! You should always start the last part of the class
		 * name with an upper case character and continue only with lower case characters
		 * or numbers. Avoid chamel case names like "MyLanguage"!
		 *
		 * @param string Last part of the class name
		 * @since 2014.03
		 * @category Developer
		 */

		/** mshop/locale/manager/language/decorators/excludes
		 * Excludes decorators added by the "common" option from the locale language manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "mshop/common/manager/decorators/default" before they are wrapped
		 * around the locale language manager.
		 *
		 *  mshop/locale/manager/language/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\MShop\Common\Manager\Decorator\*") added via
		 * "mshop/common/manager/decorators/default" for the locale language manager.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/locale/manager/language/decorators/global
		 * @see mshop/locale/manager/language/decorators/local
		 */

		/** mshop/locale/manager/language/decorators/global
		 * Adds a list of globally available decorators only to the locale language manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\MShop\Common\Manager\Decorator\*") around the locale language manager.
		 *
		 *  mshop/locale/manager/language/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\MShop\Common\Manager\Decorator\Decorator1" only to the locale controller.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/locale/manager/language/decorators/excludes
		 * @see mshop/locale/manager/language/decorators/local
		 */

		/** mshop/locale/manager/language/decorators/local
		 * Adds a list of local decorators only to the locale language manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\MShop\Common\Manager\Decorator\*") around the locale language manager.
		 *
		 *  mshop/locale/manager/language/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\MShop\Common\Manager\Decorator\Decorator2" only to the locale
		 * controller.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/locale/manager/language/decorators/excludes
		 * @see mshop/locale/manager/language/decorators/global
		 */

		return $this->getSubManagerBase( 'locale', 'language/' . $manager, $name );
	}


	/**
	 * Searches for language items matching the given criteria.
	 *
	 * @param \Aimeos\MW\Common\Criteria\Iface $search Search object
	 * @param array $ref List of domains to fetch list items and referenced items for
	 * @param integer &$total Number of items that are available in total
	 * @return array List of items implementing \Aimeos\MShop\Locale\Language\Item\Iface
	 */
	public function searchItems( \Aimeos\MW\Common\Criteria\Iface $search, array $ref = array(), &$total = null )
	{
		$items = array();
		$context = $this->getContext();
		$config = $context->getConfig();

		$dbm = $context->getDatabaseManager();
		$dbname = $this->getResourceName();
		$conn = $dbm->acquire( $dbname );

		try
		{
			$attributes = $this->getSearchAttributes();
			$types = $this->getSearchTypes( $attributes );
			$translations = $this->getSearchTranslations( $attributes );

			$find = array( ':cond', ':order', ':start', ':size' );
			$replace = array(
				$search->getConditionString( $types, $translations ),
				$search->getSortationString( $types, $translations ),
				$search->getSliceStart(),
				$search->getSliceSize(),
			);

			/** mshop/locale/manager/language/standard/search
			 * Retrieves the records matched by the given criteria in the database
			 *
			 * Fetches the records matched by the given criteria from the attribute
			 * database. The records must be from one of the sites that are
			 * configured via the context item. If the current site is part of
			 * a tree of sites, the SELECT statement can retrieve all records
			 * from the current site and the complete sub-tree of sites.
			 *
			 * As the records can normally be limited by criteria from sub-managers,
			 * their tables must be joined in the SQL context. This is done by
			 * using the "internaldeps" property from the definition of the ID
			 * column of the sub-managers. These internal dependencies specify
			 * the JOIN between the tables and the used columns for joining. The
			 * ":joins" placeholder is then replaced by the JOIN strings from
			 * the sub-managers.
			 *
			 * To limit the records matched, conditions can be added to the given
			 * criteria object. It can contain comparisons like column names that
			 * must match specific values which can be combined by AND, OR or NOT
			 * operators. The resulting string of SQL conditions replaces the
			 * ":cond" placeholder before the statement is sent to the database
			 * server.
			 *
			 * If the records that are retrieved should be ordered by one or more
			 * columns, the generated string of column / sort direction pairs
			 * replaces the ":order" placeholder. In case no ordering is required,
			 * the complete ORDER BY part including the "\/*-orderby*\/...\/*orderby-*\/"
			 * markers is removed to speed up retrieving the records. Columns of
			 * sub-managers can also be used for ordering the result set but then
			 * no index can be used.
			 *
			 * The number of returned records can be limited and can start at any
			 * number between the begining and the end of the result set. For that
			 * the ":size" and ":start" placeholders are replaced by the
			 * corresponding values from the criteria object. The default values
			 * are 0 for the start and 100 for the size value.
			 *
			 * The SQL statement should conform to the ANSI standard to be
			 * compatible with most relational database systems. This also
			 * includes using double quotes for table and column names.
			 *
			 * @param string SQL statement for searching items
			 * @since 2014.03
			 * @category Developer
			 * @see mshop/locale/manager/language/standard/insert
			 * @see mshop/locale/manager/language/standard/update
			 * @see mshop/locale/manager/language/standard/delete
			 * @see mshop/locale/manager/language/standard/count
			 */
			$path = 'mshop/locale/manager/language/standard/search';

			$sql = $this->getSqlConfig( $path );
			$results = $this->getSearchResults( $conn, str_replace( $find, $replace, $sql ) );

			try
			{
				while( ( $row = $results->fetch() ) !== false ) {
					$items[$row['id']] = $this->createItemBase( $row );
				}
			}
			catch( \Exception $e )
			{
				$results->finish();
				throw $e;
			}

			if( $total !== null )
			{
				/** mshop/locale/manager/language/standard/count
				 * Counts the number of records matched by the given criteria in the database
				 *
				 * Counts all records matched by the given criteria from the attribute
				 * database. The records must be from one of the sites that are
				 * configured via the context item. If the current site is part of
				 * a tree of sites, the statement can count all records from the
				 * current site and the complete sub-tree of sites.
				 *
				 * As the records can normally be limited by criteria from sub-managers,
				 * their tables must be joined in the SQL context. This is done by
				 * using the "internaldeps" property from the definition of the ID
				 * column of the sub-managers. These internal dependencies specify
				 * the JOIN between the tables and the used columns for joining. The
				 * ":joins" placeholder is then replaced by the JOIN strings from
				 * the sub-managers.
				 *
				 * To limit the records matched, conditions can be added to the given
				 * criteria object. It can contain comparisons like column names that
				 * must match specific values which can be combined by AND, OR or NOT
				 * operators. The resulting string of SQL conditions replaces the
				 * ":cond" placeholder before the statement is sent to the database
				 * server.
				 *
				 * Both, the strings for ":joins" and for ":cond" are the same as for
				 * the "search" SQL statement.
				 *
				 * Contrary to the "search" statement, it doesn't return any records
				 * but instead the number of records that have been found. As counting
				 * thousands of records can be a long running task, the maximum number
				 * of counted records is limited for performance reasons.
				 *
				 * The SQL statement should conform to the ANSI standard to be
				 * compatible with most relational database systems. This also
				 * includes using double quotes for table and column names.
				 *
				 * @param string SQL statement for counting items
				 * @since 2014.03
				 * @category Developer
				 * @see mshop/locale/manager/language/standard/insert
				 * @see mshop/locale/manager/language/standard/update
				 * @see mshop/locale/manager/language/standard/delete
				 * @see mshop/locale/manager/language/standard/search
				 */
				$path = 'mshop/locale/manager/language/standard/count';

				$sql = $this->getSqlConfig( $path );
				$results = $this->getSearchResults( $conn, str_replace( $find, $replace, $sql ) );

				$row = $results->fetch();
				$results->finish();

				if( $row === false ) {
					throw new \Aimeos\MShop\Locale\Exception( 'No total results value found' );
				}

				$total = $row['count'];
			}

			$dbm->release( $conn, $dbname );
		}
		catch( \Exception $e )
		{
			$dbm->release( $conn, $dbname );
			throw $e;
		}

		return $items;
	}


	/**
	 * Creates a search object and sets base criteria.
	 *
	 * @param boolean $default
	 * @return \Aimeos\MW\Common\Criteria\Iface
	 */
	public function createSearch( $default = false )
	{
		if( $default === true ) {
			return $this->createSearchBase( 'locale.language' );
		}

		return parent::createSearch();
	}


	/**
	 * Returns the search results for the given SQL statement.
	 *
	 * @param \Aimeos\MW\DB\Connection\Iface $conn Database connection
	 * @param $sql SQL statement
	 * @return \Aimeos\MW\DB\Result\Iface Search result object
	 */
	protected function getSearchResults( \Aimeos\MW\DB\Connection\Iface $conn, $sql )
	{
		$statement = $conn->create( $sql );
		$this->getContext()->getLogger()->log( __METHOD__ . ': SQL statement: ' . $statement, \Aimeos\MW\Logger\Base::DEBUG );

		$results = $statement->execute();

		return $results;
	}


	/**
	 * Create new item object initialized with given parameters.
	 *
	 * @return \Aimeos\MShop\Locale\Item\Language\Iface
	 * @throws \Aimeos\MShop\Locale\Exception On failures with the language item object
	 */
	protected function createItemBase( array $data = array( ) )
	{
		return new \Aimeos\MShop\Locale\Item\Language\Standard( $data );
	}
}
