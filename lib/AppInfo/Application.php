<?php
namespace OCA\XMPP\AppInfo;

use \OCP\AppFramework\App;
use \OCA\XMPP\Hooks\UserHooks;

class Application extends App {
  public function __construct(array $urlParams = array()) {
    parent::__construct('xmpp', $urlParams);
    $container = $this->getContainer();
    $container->registerService('UserHooks', function ($c) {
      return new UserHooks($c->query('ServerContainer')->getUserManager());
    });
  }
}
