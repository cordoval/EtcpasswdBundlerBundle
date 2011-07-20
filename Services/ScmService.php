<?php
/**
 * SCM Services
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
namespace Etcpasswd\SymfonyBundlerBundle\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ExecutableFinder;

/**
 * Services to access different SCM systems using a more or less unified api
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class ScmService
{
    /* scm type constants */
    const TYPE_GIT = "git";
    const TYPE_SVN = "svn";

    /**
     * Checks out the reposotiry given in $url
     *
     * @param string $url     Source code url
     * @param string $target  Target Folder
     * @param strin  $scmType Type of sourcecode control system
     *
     * @throws \RuntimeException in case something went wrong
     *
     * @return void
     */
    public function checkoutRepository($url, $target, $scmType=self::TYPE_GIT, $branch = null)
    {
        switch($scmType) {
            case 'git':
                $process = $this->getGitProcess($url, $target, $branch);
                break;
            case 'svn':
                break;
            default:
                throw new \RuntimeException("Unsupported SCM Type");
        }
        $process -> run(function($output){});
        if(!$process->isSuccessful()) {
            throw new \RuntimeException('Error fetching source from the given'
                .' repository location ('.$url.')');
        }
    }
    
    
    /**
     * Returns the checkout arguments for git to update the repository
     *
     * @param string $url     repository url
     * @param string $target  destination folder
     *
     * @return Process
     */
    private function getGitProcess($url, $target, $branch = null)
    {
        if(is_null($branch)) {
            $branch = 'master';
        }
        // lookup binary
        $finder = new ExecutableFinder();
        $command = $finder->find('git', null);
        if(is_null($command)) {
            throw new \RuntimeException("Unable to find the command 'git'."
                ."Please make sure it is installed");
        }
        
        if(is_dir($target.'/.git')) {
            // simple pull
            return new Process($command.' pull . '.$branch, $target);
        }
        return new Process($command.' clone -b '.$branch.' '.$url.' '.$target);
    }
}