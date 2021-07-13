<?php
namespace OCA\XMPP;

use \OCP\Util;

class Prosody {
  public static function usernameFromJid($jid) {
    return explode('@', $jid)[0];
  }

  public static function domainFromJid($jid) {
    return explode('@', $jid)[1];
  }

  public static function register($jid, $password) {
    $username = self::usernameFromJid($jid);
    $domain   = self::domainFromJid($jid);
    $out      = shell_exec("prosodyctl register ".$username." ".$domain.' "'.$password.'"');
    Util::writeLog('xmpp', "Created user for ".$jid.": ".$out, Util::INFO);
  }

  public static function setPassword($jid, $password) {
    $out = shell_exec("prosodyctl passwd ".$jid.' "'.$password.'"');
    Util::writeLog('xmpp', "Updated password for ".$jid. ": ".$out, Util::INFO);
  }

  public static function remove($jid) {
    $out = shell_exec("prosodyctl deluser ".$jid);
    Util::writeLog('xmpp', "Removed account ".$jid.": ".$out, Util::INFO);
  }

  public static function exists($jid) {
    $username = self::usernameFromJid($jid);
    $domain   = self::domainFromJid($jid);
    $folder   = str_replace(".", "%2e", $domain);
    $username = str_replace(".", "%2e", $username);
    return file_exists("/var/lib/prosody/".$folder."/accounts/".$username.".dat");
  }
}
