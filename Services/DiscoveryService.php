<?php
/**
 * Default Controller
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */

namespace Etcpasswd\SymfonyBundlerBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
/**
 * Discovers bundles using the symfony2 bundles api
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */

class DiscoveryService implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * Retrieves a list of all bundles
     *
     * @return BundleDescriptor[]
     */
    public function listBundles()
    {
        return array();
    }
    
    /**
     * Searches for the given bundle and returns its description
     *
     * @return BundleDescriptor[]
     */
    public function searchBundleByName($name)
    {
        $all = $this->listBundles();
        $it = new RegexIterator($all, '.*'.$name.'.*');
    }
    
}