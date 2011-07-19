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

use Etcpasswd\SymfonyBundlerBundle\Services\ScmService;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Updates the spec files from the remote repository
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class UpdateAllSpecsCommand extends BaseCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('bundler:spec:update-all');
        $this->setDescription('updates all spec files');
    }
    
    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->getContainer()->getParameter('symfony_bundler.spec.repository');
        $vendorPath = $this->getContainer()->getParameter('symfony_bundler.vendor_path');
        $installPath = $vendorPath.'/Etcpasswd/SymfonyBundles';
        
        /* @var $scm ScmService */
        $scm = $this->getContainer()->get('symfony_bundler.scmservice');
        $scm -> checkoutRepository($repository, $installPath);
        
        $output->writeln("Specs updated successfully");
    }
}