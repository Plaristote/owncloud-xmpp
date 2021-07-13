# owncloud-xmpp
ownCloud app to provide XMPP-based IM to your users, using [JSXC](https://jsxc.org) and [Prosody](https://prosody.im).

## Features
- Auto login
- Synchornize user accounts and passwords

## Requirements
- ownCloud >= 10
- Prosody

## Configuration
- You will need to set up a [BOSH server](https://prosody.im/doc/setting_up_bosh);
- Your BOSH server must be served from the same hostname as your ownCloud instance;
- prosodyctl must be run by the same user as your ownCloud instance

To allow ownCloud to use prosodyctl, you must configure the prosody user to be the same as ownCloud's, by using the `prosody_user` variable
in Prosody's configuation file. For instance:

```
prosody_user = "www-data"
```

Then, change permission on Prosody's files:

```
chmod -R www-data:www-data /var/lib/prosody
```
