<?php

namespace danspam\Silex\Provider;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use danspam\Doctrine\Command\LoadDataFixturesDoctrineCommand;

class DoctrineLoadFixturesProvider implements ServiceProviderInterface
{
      /**
     * The console application.
     *
     * @var Console
     */
    protected $console;

    /**
     * Creates a new doctrine migrations provider.
     *
     * @param Console $console
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    public function register(Application $app)
    {
        //nothing to do here
    }

    public function boot(Application $app)
    {
        $helperSet = new HelperSet(array(
            'connection' => new ConnectionHelper($app['db']),
            'dialog'     => new DialogHelper(),
        ));

        if (isset($app['orm.em'])) {
            $helperSet->set(new EntityManagerHelper($app['orm.em']), 'em');
        } elseif (isset($app['db.orm.em'])) {
            $helperSet->set(new EntityManagerHelper($app['db.orm.em']), 'em');
        }

        $this->console->setHelperSet($helperSet);

        $this->console->add(new LoadDataFixturesDoctrineCommand());
    }
}
