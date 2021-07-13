window.jsxc = new JSXC({
  appName: "ownCloud",
  rosterVisibility: "shown",
  loadConnectionOptions: function(username, password) {
    console.log("Connecting to xmpp server", location.hostname.split('.').splice(1).join('.'), username, password);
    return Promise.resolve({ xmpp: {
      url: '/http-bind/',
      domain: location.hostname.split('.').splice(1).join('.')
    }});
  },
  connectionCallback: function(jid, status, condition) {
    console.log("Connection callback called", jid, status, condition);
  }
});

function xmppClearStore() {
  for (var i = 0 ; i < localStorage.length ;) {
    var key = localStorage.key(i);

    if (key.startsWith("jsxc2:"))
      localStorage.removeItem(key);
    else
      ++i;
  }
}

function xmppGetCookie(name) {
  const fullName = "oc-xmpp-" + name;
  const cookie   = document.cookie.split('; ').find(function(row) { return row.startsWith(fullName + '='); });

  return cookie ? decodeURIComponent(cookie.split('=')[1]) : null;
}

function xmppSignIn() {
  const login     = xmppGetCookie("login");
  const sessionId = xmppGetCookie("sid");
  const password  = xmppGetCookie("paswd");

  if (password != "") {
    jsxc.start("/http-bind/", login, password);
  }
}

$(document).ready(xmppSignIn);
$(window).unload(xmppClearStore);
