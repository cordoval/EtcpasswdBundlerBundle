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

use Etcpasswd\SymfonyBundlerBundle\Specification\BundleSpecification;

use Etcpasswd\SymfonyBundlerBundle\Specification\SpecificationCollection;

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
     * @var Cache
     */
    protected $cache;
    
    public function __construct($cache)
    {
        $this->cache = $cache;
    }
    
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
     * @return SpecificationCollection
     */
    public function listBundles()
    {
        if($this->cache->has('bundles.list'))
        {
            return $this->cache->get('bundles.list');
        }
        $list = $this->getJson('http://symfony2bundles.org/best?format=json');
        $entries = new SpecificationCollection();
        foreach($list as $entry)
        {
            $entries->attach($this->createDescriptorFromJson($entry));
        }
        $this->cache->set('bundles.list', $entries, 3600);
        
        return $entries;
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
    
    /**
     * Creates a bundle descriptor for the provided json
     *
     * @param object $json Json result
     *
     * @return BundleSpecification
     */
    private function createDescriptorFromJson($object)
    {
        $spec = new BundleSpecification($object->name, $object->description, $object->homepage);
        return $spec;
    }
    
    
    /**
     * Returns json data from a given url
     *
     * @param string $url url to retrieve
     *
     * @return object
     */
    private function getJson($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        
        if(!$result) {
            throw new \RuntimeExecption("Unable to fetch contents from $url");
        }
        return json_decode($result);
    }
}