<?php
namespace OCA\XMPP;

class Roster {
  const FILEPATH = "/var/lib/prosody/groups.txt";

  public static function addUser($jid, $group) {
    self::removeUser($jid, $group); // shoddy way of avoiding duplicates
    $groupHeader = "[".$group."]\n";
    $content = file_get_contents(self::FILEPATH);
    if (strpos($content, $groupHeader) !== false)
      $content = str_replace($groupHeader, $groupHeader.$jid."\n", $content);
    else
      $content = $content."\n".$groupHeader.$jid;
    file_put_contents(self::FILEPATH, $content);
  }

  public static function removeUser($jid, $group) {
    $content = file_get_contents(self::FILEPATH);
    $content = str_replace($jid."\n", "", $content);
    file_put_contents(self::FILEPATH, $content);
  }
}
