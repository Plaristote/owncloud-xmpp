<?php
namespace OCA\XMPP\Hooks;

use \OCP\Util;
use \OCA\XMPP\AuthHelper;
use \OCA\XMPP\Prosody;
use \OCA\XMPP\Roster;

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
    if (!Prosody::exists($jid)) {
      Prosody::register($jid, $password);
      Roster::addUser($jid, Prosody::domainFromJid($jid));
    }
    else
      Prosody::setPassword($jid, $password);
  }

  public static function onPostDelete($user) {
    $jid = AuthHelper::getUserEmail($user);
    if (Prosody::exists($jid)) {
      Roster::removeUser($jid, Prosody::domainFromJid($jid));
      Prosody::remove($jid);
    }
  }
}
