<?php
namespace OCA\XMPP\AppInfo;
use \OCP\Util;

\OCP\Util::connectHook('OC_User', 'post_login', 'OCA\XMPP\AuthHelper', 'postLogin');

if (\OC_User::isLoggedIn()) {
  Util::addStyle ("xmpp", "jsxc.bundle");
  Util::addStyle ("xmpp", "xmpp");
  Util::addScript("xmpp", "jsxc.bundle");
  Util::addScript("xmpp", "login");
}

$app = new Application();
$app->getContainer()->query("UserHooks")->register();
