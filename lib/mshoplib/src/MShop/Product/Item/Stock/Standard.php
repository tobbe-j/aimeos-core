<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Product
 */


namespace Aimeos\MShop\Product\Item\Stock;


/**
 * Default product stock item implementation.
 *
 * @package MShop
 * @subpackage Product
 */
class Standard
	extends \Aimeos\MShop\Common\Item\Base
	implements \Aimeos\MShop\Product\Item\Stock\Iface
{
	private $values;

	/**
	 * Initializes the stock item object with the given values
	 */
	public function __construct( array $values = array( ) )
	{
		parent::__construct( 'product.stock.', $values );

		$this->values = $values;
	}


	/**
	 * Returns the product Id.
	 *
	 * @return integer Product Id
	 */
	public function getProductId()
	{
		return ( isset( $this->values['prodid'] ) ? (int) $this->values['prodid'] : null );
	}


	/**
	 * Sets the Product Id.
	 *
	 * @param integer $prodid New product Id
	 */
	public function setProductId( $prodid )
	{
		if( $prodid == $this->getProductId() ) { return; }

		$this->values['prodid'] = (int) $prodid;
		$this->setModified();
	}


	/**
	 * Returns the warehouse Id.
	 *
	 * @return integer Warehouse Id
	 */
	public function getWarehouseId()
	{
		return ( isset( $this->values['warehouseid'] ) ? (int) $this->values['warehouseid'] : null );
	}


	/**
	 * Sets the warehouse Id.
	 *
	 * @param integer|null $warehouseid New warehouse Id
	 */
	public function setWarehouseId( $warehouseid )
	{
		if( $warehouseid === $this->getWarehouseId() ) { return; }

		if( $warehouseid !== null ) {
			$warehouseid = (int) $warehouseid;
		}

		$this->values['warehouseid'] = $warehouseid;
		$this->setModified();
	}


	/**
	 * Returns the stock level.
	 *
	 * @return integer Stock level
	 */
	public function getStocklevel()
	{
		return ( isset( $this->values['stocklevel'] ) ? (int) $this->values['stocklevel'] : null );
	}


	/**
	 * Sets the stock level.
	 *
	 * @param integer|null $stocklevel New stock level
	 */
	public function setStocklevel( $stocklevel )
	{
		if( $stocklevel === $this->getStocklevel() ) { return; }

		if( $stocklevel !== null ) {
			$stocklevel = (int) $stocklevel;
		}

		$this->values['stocklevel'] = $stocklevel;
		$this->setModified();
	}


	/**
	 * Returns the back in stock date of the product.
	 *
	 * @return string Back in stock date of the product
	 */
	public function getDateBack()
	{
		return ( isset( $this->values['backdate'] ) ? (string) $this->values['backdate'] : null );
	}


	/**
	 * Sets the product back in stock date.
	 *
	 * @param string|null $backdate New back in stock date of the product
	 */
	public function setDateBack( $backdate )
	{
		if( $backdate === $this->getDateBack() ) { return; }

		$this->checkDateFormat( $backdate );

		if( $backdate !== null ) {
			$backdate = (string) $backdate;
		}

		$this->values['backdate'] = $backdate;
		$this->setModified();
	}


	/**
	 * Sets the item values from the given array.
	 *
	 * @param array $list Associative list of item keys and their values
	 * @return array Associative list of keys and their values that are unknown
	 */
	public function fromArray( array $list )
	{
		$unknown = array();
		$list = parent::fromArray( $list );

		foreach( $list as $key => $value )
		{
			switch( $key )
			{
				case 'product.stock.productid': $this->setProductId( $value ); break;
				case 'product.stock.warehouseid': $this->setWarehouseId( $value ); break;
				case 'product.stock.stocklevel': $this->setStocklevel( $value ); break;
				case 'product.stock.dateback': $this->setDateBack( $value ); break;
				default: $unknown[$key] = $value;
			}
		}

		return $unknown;
	}


	/**
	 * Returns the item values as array.
	 *
	 * @return Associative list of item properties and their values
	 */
	public function toArray()
	{
		$list = parent::toArray();

		$list['product.stock.productid'] = $this->getProductId();
		$list['product.stock.warehouseid'] = $this->getWarehouseId();
		$list['product.stock.stocklevel'] = $this->getStocklevel();
		$list['product.stock.dateback'] = $this->getDateBack();

		return $list;
	}

}