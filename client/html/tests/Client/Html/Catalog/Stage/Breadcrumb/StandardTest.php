<?php

namespace Aimeos\Client\Html\Catalog\Stage\Breadcrumb;


/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$context = \TestHelper::getContext();
		$paths = \TestHelper::getHtmlTemplatePaths();
		$this->object = new \Aimeos\Client\Html\Catalog\Stage\Breadcrumb\Standard( $context, $paths );

		$catalogManager = \Aimeos\MShop\Catalog\Manager\Factory::createManager( $context );
		$search = $catalogManager->createSearch();
		$search->setConditions( $search->compare( '==', 'catalog.code', 'cafe' ) );
		$catItems = $catalogManager->searchItems( $search );

		if( ( $catItem = reset( $catItems ) ) === false ) {
			throw new \Exception( 'No catalog item found' );
		}

		$view = \TestHelper::getView();

		$view->stageCatPath = $catalogManager->getPath( $catItem->getId() );

		$this->object->setView( $view );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetHeader()
	{
		$output = $this->object->getHeader();
		$this->assertNotNull( $output );
	}


	public function testGetBody()
	{
		$output = $this->object->getBody();
		$this->assertRegExp( '#Root.*.Categories.*.Kaffee.*#smU', $output );
		$this->assertStringStartsWith( '<div class="catalog-stage-breadcrumb">', $output );
	}


	public function testGetBodyNoCatId()
	{
		$this->object->setView( \TestHelper::getView() );

		$output = $this->object->getBody();
		$this->assertRegExp( '#Your search result#smU', $output );
		$this->assertStringStartsWith( '<div class="catalog-stage-breadcrumb">', $output );
	}


	public function testGetSubClient()
	{
		$this->setExpectedException( '\\Aimeos\\Client\\Html\\Exception' );
		$this->object->getSubClient( 'invalid', 'invalid' );
	}


	public function testProcess()
	{
		$this->object->process();
	}
}
