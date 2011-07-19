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

namespace Etcpasswd\SymfonyBundlerBundle\Loader;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Loader\FileLoader;

class SpecFileLoader extends FileLoader
{
    /**
     * Contents of the spec file
     *
     * @var array
     */
    private $content = array();
    
    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     */
    public function load($resource, $type = null)
    {
        $path = $this->locator->locate($resource);
        $this->content = $this->loadFile($path);
    }
    
    /**
     * Returns the contents of the spec file
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        // @todo make something nice ;)
        return (substr($resource,-4) == '.yml');
    }
    
    /**
     * Loads a YAML file.
     *
     * @param string $file
     * @return array The file content
     */
    private function loadFile($file)
    {
        return $this->validate(Yaml::parse($file), $file);
    }
    
    /**
     * Validates a YAML file.
     *
     * @param mixed $content
     * @param string $file
     * @return array
     *
     * @throws \InvalidArgumentException When spec file is not valid
     */
    private function validate($content, $file)
    {
        if (null === $content) {
            return $content;
        }
    
        if (!is_array($content)) {
            throw new \InvalidArgumentException(sprintf('The spec file "%s" is not valid.', $file));
        }
        return $content;
    }
}