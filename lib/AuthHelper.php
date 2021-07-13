<?php
namespace OCA\XMPP;

use OCP\Util;

class AuthHelper {
  const COOKIE_XMPP_LOGIN    = "oc-xmpp-login";
  const COOKIE_XMPP_PASSWORD = "oc-xmpp-paswd";
  const COOKIE_XMPP_SID      = "oc-xmpp-sid";

  public static function postLogin($params) {
    \OCP\App::checkAppEnabled('xmpp');
    $via = \OC::$server->getRequest()->getRequestUri();
    if (preg_match(
      '#(/ocs/v\d.php|'.
      '/apps/calendar/caldav.php|'.
      '/apps/contacts/carddav.php|'.
      '/remote.php/webdav)/#', $via)
    ) {
      return false;
    }
    Util::writeLog('xmpp', __METHOD__ . ": Preparing login of XMPP user '{$params['uid']}'", Util::DEBUG);
    setcookie(self::COOKIE_XMPP_LOGIN, self::getCurrentUserEmail());
    setcookie(self::COOKIE_XMPP_PASSWORD, $params['password']);
    return true;
  }

  public static function logout() {
    \OCP\App::checkAppEnabled('xmpp');
    setcookie(self::COOKIE_XMPP_LOGIN,    "-del-", 1, "/", "", true, true);
    setcookie(self::COOKIE_XMPP_PASSWORD, "-del-", 1, "/", "", true, true);
    setcookie(self::COOKIE_XMPP_SID,      "-del-", 1, "/", "", true, true);
    return true;
  }

  /**
   * Returns the email address of user, if any.
   * If the uid is an email, it'll return it regardless of the user email.
   * If neither the uid or the user email are an email, it'll return the uid.
   */
  public static function getCurrentUserEmail() {
    $user = \OC::$server->getUserSession()->getUser();
    return self::getUserEmail($user);
  }

  public static function getUserEmail($user) {
    $uid = $user->getUID();
    if (strpos($uid, '@') !== false) {
      return $uid;
    }
    $email = $user->getEMailAddress();
    if (strpos($email, '@') !== false) {
      return $email;
    }
    return $uid; // returns a non-empty default
  }
}
