SymfonyBundlerBundle
==========
This bundle allows you to easily mangage and install other Symfony2 Bundles.

Besides from just installing the bundles it also handles 3rd party dependencies and 
fetches them for you if necessary.

Installation
==========
Clone the bundler package by adding it as a submodule

    git submodule add git@github.com:mazen/SymfonyBundlerBundle.git vendor/bundles/Etcpasswd

Configure the autoloader

    app/autoload.php
    
    $loader->registerNamespaces(array(
        'Symfony'          => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
        'Sensio'           => __DIR__.'/../vendor/bundles',
        'Etcpasswd'        => __DIR__.'/../vendor/bundles',
        //...
    ));
 
And register the bundle itself. I promise that this will be the last time you have done 
it this way.

Usage
==========
The Bundler can be used with symfonys console application

    app/console bundler:list             shows installed bundles
    app/console bundler:search           searches for remote bundles by the given name
    app/console bundler:available        shows available bundles for installation
    app/console bundler:install          installs the given bundle
    app/console bundler:update           update all installed bundles to their latest version
    app/console bundler:spec:update-all  updates all spec files
    app/console bundler:spec:create      Creates a new spec file for your bundle  
 
To Bundle Developers
==========
If you want to have your bundle added to this tool, please provide a spec file for it. 
You can either add it to your own bundles repository if it is hosted on github (there 
is a scraper running regularly to update these), or alternativly create a pull 
request with your bundles specs.
 
Credits
==========
Thanks go to:

Fabien for his great framework

knp labs for the symfony2bundles.org website and its api
