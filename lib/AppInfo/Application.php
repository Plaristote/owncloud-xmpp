<?php
namespace OCA\XMPP\AppInfo;

use \OCP\AppFramework\App;
use \OCA\XMPP\AuthHelper;

class UserHooks {
  public function __construct($userManager) {
    $this->userManager = $userManager;
  }

  public function register() {
    $updateCallback = function($user, $password, $recoverPassword) {
      AccountHelper::onPostSetPassword($user, $password);
    };
    $deleteCallback = function($user) {
      AccountHelper::onPostDelete($user);
    };
    $this->userManager->listen('\OC\User', 'postSetPassword', $updateCallback);
    $this->userManager->listen('\OC\User', 'postDelete', $deleteCallback);
  }

  public function onPostSetPassword($user, $password) {
    if (!hasAccount($user)) {
      $out = shell_exec("prosodyctl adduser " . $user->getEMailAddress());
      Util::writeLog('xmpp', __METHOD__ . ": Created user for " . $user->getUID() . ": " . $out, Util::INFO);
    }
    $out = shell_exec("prosodyctl passwd " . $user->getEMailAddress() . ' "' . $password . '"');
    Util::writeLog('xmpp', __METHOD__ . ": Updated password for " . $user->getUID() . ": " . $out, Util::INFO);
  }

  public function onPostDelete($user) {
    $out = shell_exec("prosodyctl deluser " . $user->getEMailAddress());
    Util::writeLog('xmpp', __METHOD__ . ": Removed account for " . $user->getUID() . ": " . $out, Util::INFO);
  }

  public static function hasAccount($user) {
    $domainName   = substr(strrchr($user->getEmailAddress(), "@"), 1);
    $domainFolder = str_replace(".", "%2", $domainName);
    return file_exists("/var/lib/prosody/".$domainFolder."/accounts/".$user->getUID().".dat");
  }
}

class Application extends App {
  public function __construct(array $urlParams = array()) {
    parent::__construct('xmpp', $urlParams);
    $container = $this->getContainer();
    $container->registerService('UserHooks', function ($c) {
      return new UserHooks($c->query('ServerContainer')->getUserManager());
    });
  }
}
