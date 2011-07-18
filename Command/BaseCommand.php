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

namespace Etcpasswd\SymfonyBundlerBundle\Command;

use Symfony\Component\Console\Command\Command;
abstract class BaseCommand extends Command
{
    /**
     * Returns the service container
     *
     * @return Symfony\Component\DependencyInjection\Container
     */
    protected function getContainer()
    {
        return $this->getApplication()->getKernel()->getContainer();
    }
}