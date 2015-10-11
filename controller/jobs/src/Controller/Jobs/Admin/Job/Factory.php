<?php

/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Controller
 * @subpackage Jobs
 */


namespace Aimeos\Controller\Jobs\Admin\Job;


/**
 * Admin job controller factory.
 *
 * @package Controller
 * @subpackage Jobs
 */
class Factory
	extends \Aimeos\Controller\Jobs\Common\Factory\Base
	implements \Aimeos\Controller\Jobs\Common\Factory\Iface
{
	/**
	 * Creates a new controller specified by the given name.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object required by controllers
	 * @param \Aimeos\Bootstrap $aimeos \Aimeos\Bootstrap object
	 * @param string|null $name Name of the controller or "Default" if null
	 * @return \Aimeos\Controller\Jobs\Iface New controller object
	 */
	public static function createController( \Aimeos\MShop\Context\Item\Iface $context, \Aimeos\Bootstrap $aimeos, $name = null )
	{
		/** controller/jobs/admin/job/name
		 * Class name of the used admin jobs scheduler controller implementation
		 *
		 * Each default job controller can be replace by an alternative imlementation.
		 * To use this implementation, you have to set the last part of the class
		 * name as configuration value so the controller factory knows which class it
		 * has to instantiate.
		 *
		 * For example, if the name of the default class is
		 *
		 *  \Aimeos\Controller\Jobs\Admin\Job\Standard
		 *
		 * and you want to replace it with your own version named
		 *
		 *  \Aimeos\Controller\Jobs\Admin\Job\Myjob
		 *
		 * then you have to set the this configuration option:
		 *
		 *  controller/jobs/admin/job/name = Myjob
		 *
		 * The value is the last part of your own class name and it's case sensitive,
		 * so take care that the configuration value is exactly named like the last
		 * part of the class name.
		 *
		 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
		 * characters are possible! You should always start the last part of the class
		 * name with an upper case character and continue only with lower case characters
		 * or numbers. Avoid chamel case names like "MyJob"!
		 *
		 * @param string Last part of the class name
		 * @since 2014.03
		 * @category Developer
		 */
		if( $name === null ) {
			$name = $context->getConfig()->get( 'controller/jobs/admin/job/name', 'Standard' );
		}

		if( ctype_alnum( $name ) === false )
		{
			$classname = is_string( $name ) ? '\\Aimeos\\Controller\\Jobs\\Admin\\Job\\' . $name : '<not a string>';
			throw new \Aimeos\Controller\Jobs\Exception( sprintf( 'Invalid characters in class name "%1$s"', $classname ) );
		}

		$iface = '\\Aimeos\\Controller\\Jobs\\Iface';
		$classname = '\\Aimeos\\Controller\\Jobs\\Admin\\Job\\' . $name;

		$controller = self::createControllerBase( $context, $aimeos, $classname, $iface );

		/** controller/jobs/admin/job/decorators/excludes
		 * Excludes decorators added by the "common" option from the admin job controllers
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "controller/jobs/common/decorators/default" before they are wrapped
		 * around the job controller.
		 *
		 *  controller/jobs/admin/job/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Controller\Jobs\Common\Decorator\*") added via
		 * "controller/jobs/common/decorators/default" to this job controller.
		 *
		 * @param array List of decorator names
		 * @since 2015.09
		 * @category Developer
		 * @see controller/jobs/common/decorators/default
		 * @see controller/jobs/admin/job/decorators/global
		 * @see controller/jobs/admin/job/decorators/local
		 */

		/** controller/jobs/admin/job/decorators/global
		 * Adds a list of globally available decorators only to the admin job controllers
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Controller\Jobs\Common\Decorator\*") around the job controller.
		 *
		 *  controller/jobs/admin/job/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Controller\Jobs\Common\Decorator\Decorator1" only to this job controller.
		 *
		 * @param array List of decorator names
		 * @since 2015.09
		 * @category Developer
		 * @see controller/jobs/common/decorators/default
		 * @see controller/jobs/admin/job/decorators/excludes
		 * @see controller/jobs/admin/job/decorators/local
		 */

		/** controller/jobs/admin/job/decorators/local
		 * Adds a list of local decorators only to the admin job controllers
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Controller\Jobs\Admin\Job\Decorator\*") around this job controller.
		 *
		 *  controller/jobs/admin/job/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Controller\Jobs\Admin\Job\Decorator\Decorator2" only to this job
		 * controller.
		 *
		 * @param array List of decorator names
		 * @since 2015.09
		 * @category Developer
		 * @see controller/jobs/common/decorators/default
		 * @see controller/jobs/admin/job/decorators/excludes
		 * @see controller/jobs/admin/job/decorators/global
		 */
		return self::addControllerDecorators( $context, $aimeos, $controller, 'admin/job' );
	}
}