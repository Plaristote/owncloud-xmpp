<?php
namespace OCA\XMPP\Hooks;

use \OCP\Util;
use \OCA\XMPP\AuthHelper;
use \OCA\XMPP\Prosody;

class UserHooks {
  public function __construct($userManager) {
    $this->userManager = $userManager;
  }

  public function register() {
    $updateCallback = function($user, $password, $recoverPassword) {
      UserHooks::onPostSetPassword($user, $password);
    };
    $deleteCallback = function($user) {
      UserHooks::onPostDelete($user);
    };
    $this->userManager->listen('\OC\User', 'postSetPassword', $updateCallback);
    $this->userManager->listen('\OC\User', 'postDelete', $deleteCallback);
  }

  public static function onPostSetPassword($user, $password) {
    $jid = AuthHelper::getUserEmail($user);
    if (!Prosody::exists($jid))
      Prosody::register($jid, $password);
    else
      Prosody::setPassword($jid, $password);
  }

  public static function onPostDelete($user) {
    $jid = AuthHelper::getUserEmail($user);
    if (Prosody::exists($jid))
      Prosody::remove($jid);
  }
}
