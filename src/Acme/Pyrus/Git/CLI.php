<?php
/**
 * Acme\Pyrus\Git\CLI
 *
 * PHP version 5.3
 *
 * @category  Acme
 * @package   Acme_Pyrus_Git
 * @author    sasezaki <sasezaki@gmail.com>
 * @copyright 2011 sasezaki
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * CLI class for Acme_Pyrus_Git
 *
 * @category  Acme
 * @package   Acme_Pyrus_Git
 * @author    sasezaki <handle@php.net>
 * @copyright 2011 sasezaki
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace Acme\Pyrus\Git;
use PEAR2\Pyrus\Config;
use PEAR2\Pyrus\PackageFile;
use PEAR2\Pyrus\Filesystem;
use PEAR2\Pyrus\Developer\PackageFile\Commands as PackageFileCommands;
use VersionControl_Git as Git;

class CLI
{
    public function __construct()
    {
        $this->setVersionControlGitIncludePath();
        require_once 'VersionControl/Git.php';
    }

    public function install($frontend, $args, $options)
    {
         die;
        $conf = Config::current();
        $clone_dir = $conf->download_dir;
        $php_dir = $conf->php_dir;

        // argv
        $repo = $options['gitrepo'];

        // clone
        $git = new Git();
        $isBare = false;

        $regex = '#git://.*/(.*).git#';
        preg_match($regex, $repo, $m);
        $repo_name = $m[1];
        $clone_repo_dir = $clone_dir . DIRECTORY_SEPARATOR . $repo_name;
        
        echo 'cloning git.. ' .$repo, PHP_EOL;
        $git->createClone($repo, $isBare, $clone_repo_dir);
        echo 'done', PHP_EOL;

        //
        $options['plugin'] = false;
        $options['force'] = false;
        $options['packagingroot'] = false;
        $options['optionaldeps'] = false;

        $packagexml = $clone_repo_dir. DIRECTORY_SEPARATOR. 'package.xml';
        //$args['package'] = $packagexml = $clone_repo_dir. DIRECTORY_SEPARATOR. 'package.xml';

        die;
        /**
        $packageFile = new PackageFile($packagexml);
        $args['package'] = $packageFile->getPackageFileObject();
        var_dump();
         */

        $frontend->install($args, $options);
    }

    /**
     * git-install from git://example.com/repo.git 's  package.xml
     * (PEAR2's directory)
     */
    //public function install($frontend, $args, $options)
    public function package($frontend, $args, $options)
    {
        $conf = Config::current();
        $clone_dir = $conf->download_dir;
        $php_dir = $conf->php_dir;

        // argv
        $repo = $options['gitrepo'];

        // clone
        $git = new Git();
        $isBare = false;

        $regex = '#git://.*/(.*).git#';
        preg_match($regex, $repo, $m);
        $repo_name = $m[1];
        $clone_repo_dir = $clone_dir . DIRECTORY_SEPARATOR . $repo_name;
        
        echo 'cloning git.. ' .$repo, PHP_EOL;
        $git->createClone($repo, $isBare, $clone_repo_dir);
        echo 'done', PHP_EOL;

        // packaging
        echo 'packaging', PHP_EOL;

        $options['phar'] = false;
        $options['tgz'] = false;
        $options['tar'] = false;
        $options['zip'] = false;
        
        $options['extrasetup'] = null;

        $packagexml = $clone_repo_dir. DIRECTORY_SEPARATOR. 'package.xml';
        $args['packagexml'] = $packagexml;

        $devCommand = new PackageFileCommands;
        $devCommand->package($frontend, $args, $options);

        echo 'done..';

        $tgz = '';

        return $tgz;
    }


    /**
     * clone & copy to php_dir
     */
    public function copy($frontend, $args, $options)
    {

        // from pear config
        $conf = Config::current();
        //$clone_dir = $conf->data_dir; // data_dir is system config
        $clone_dir = $conf->download_dir; // download_dir is user config
        $php_dir = $conf->php_dir;

        // argv
        $repo = $options['gitrepo'];
        $src_path = isset($options['src']) ? $options['src'] : 'src' ;

        // clone
        $git = new Git();
        $isBare = false;

        $regex = '#git://.*/(.*).git#';
        preg_match($regex, $repo, $m);
        $repo_name = $m[1];
        $clone_repo_dir = $clone_dir . DIRECTORY_SEPARATOR . $repo_name;
        
        echo 'cloning git.. ' . $repo;

        $git->createClone($repo, $isBare, $clone_repo_dir);

        // mv component class files (recursive copy)
        $src_dir = $clone_repo_dir . DIRECTORY_SEPARATOR . $src_path;

        echo "copy src files from $src_dir to $php_dir";

        Filesystem::copyDir($src_dir, $php_dir);

        //
        //var_dump($frontend);
        /*
        echo 'args';
            var_dump($args);
        echo 'options';
        var_dump($options);
         */
    }

    protected function setVersionControlGitIncludePath()
    {
        $dir = dirname(dirname(dirname(__DIR__)));
        set_include_path($dir . PATH_SEPARATOR . get_include_path());
    }

}
