<?php
namespace OCA\XMPP;

use \OCP\Util;

class Prosody {
  public static function register($jid, $password) {
    $username = explode('@', $jid)[0];
    $domain   = explode('@', $jid)[1];
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
    $username = explode('@', $jid)[0];
    $domain   = explode('@', $jid)[1];
    $folder   = str_replace(".", "%2", $domain);
    return file_exists("/var/lib/prosody/".$folder."/accounts/".$username.".dat");
  }
}
