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


namespace Etcpasswd\BundlerBundle\Manipulator;

use Sensio\Bundle\GeneratorBundle\Manipulator\Manipulator;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Changes the PHP code of the autoloader.
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class AutoloaderManipulator extends Manipulator
{
    /**
     * Constructor.
     *
     * @param string $file File to manipulate
     */
    public function __construct($file)
    {
        $this->file = $file;
    }
    
    public function addNamespace($namespace, $folder)
    {
        $src = file($this->file);
        $this->setCode(token_get_all(implode('',$src)));
        while ($token = $this->next()) {
            // ->
            
            if (T_OBJECT_OPERATOR !== $token[0]) {
                continue;
            }
            // =
            $token = $this->next();
            
            if($token['1'] !== 'registerNamespaces') {
                return false;
            }
            // =
            $this->next();
            
            // array
            $token = $this->next();
            if (T_ARRAY !== $token[0]) {
                return false;
            }
            // add the bundle at the end of the array
            while ($token = $this->next()) {
                if("'".$namespace."'" === $this->value($token)) {
                    // already registered
                    return;
                }
                
                // look for );
                if (')' !== $this->value($token)) {
                    continue;
                }
                if (';' !== $this->value($this->peek())) {
                    continue;
                }
                
                // ;
                $this->next();
                $lines = array_merge(
                    array_slice($src, 0, $this->line),
                    array(sprintf("    '%s'          => %s,", $namespace, $folder).PHP_EOL),
                    array_slice($src, $this->line )
                );
            
                file_put_contents($this->file, implode('', $lines));
            
                return true;
            }
            
            
        }
    }
}