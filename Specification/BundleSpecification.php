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

namespace Etcpasswd\SymfonyBundlerBundle\Specification;

/**
 * Class to handle bundle specifications
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class BundleSpecification implements \Serializable
{
    protected $name;
    protected $authors;
    protected $vendor;
    protected $dependencies;
    protected $description;
    protected $url;
    
    public function __construct($name, $description, $url)
    {
        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(
            array(
                'name'         => $this->name,
                'description'  => $this->description,
                'url'          => $this->url
            )
        );
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($data)
    {
        $values = unserialize($data);
        $this->name = $values['name'];
        $this->description = $values['description'];
        $this->url = $values['url'];
    }
    
    /**
     * Returns the description of the bundle
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Returns a list of authors of the given bundle
     *
     * @return array
     */
    public function getAuthors()
    {
        
    }
    
    /**
     * Returns the name of the bundle
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Returns the name of the vendor
     *
     * @return string
     */
    public function getVendor()
    {
        
    }
    
    /**
     * Returns any dependencies to other libraries or bundles
     *
     * @return SplObjectStorage
     */
    public function getDependencies()
    {
        
    }
}