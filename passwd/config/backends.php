<?php
/**
 * This file provides defaults for backends people use to change their
 * passwords.
 *
 * IMPORTANT: DO NOT EDIT THIS FILE!
 * Local overrides MUST be placed in backends.local.php or backends.d/.
 * If the 'vhosts' setting has been enabled in Horde's configuration, you can
 * use backends-servername.php.
 *
 * Properties that can be set for each server:
 * ===========================================
 *
 * disabled: (boolean) If true, the config entry is disabled.
 *
 * name: (string) This is the plaintext name displayed if using the server
 *       list (also displayed on the main page).
 *
 * driver: The driver used to change the password. Valid drivers:
 *     - adsi: ADSI COM interface.
 *     - expect: Expect script.
 *     - horde: Horde authentication driver.
 *     - ldap: LDAP server.
 *     - pine: Pine-encoded file.
 *     - poppassd: Poppassd server.
 *     - servuftp: Servuftp server.
 *     - smbldap: LDAP server for both LDAP -and- Samba auth.
 *     - smbpasswd: Smbpasswd command.
 *     - sql: SQL authentication.
 *     - vmailmgr: Local vmailmgr daemon.
 *     - vpopmail: SQL based vpopmail.
 *
 *     - composite: Allows you to chain multiple drivers together (see example
 *                  below).
 *
 * policy: (array) The password policies for this backend. You are responsible
 *   for the sanity checks of these options. Options are:
 *     - maxLength: (integer) Maximum length of the password.
 *     - maxSpace: (integer) Maximum number of white space characters.
 *     - minAlpha: (integer) Minimum number of alphabetic characters.
 *     - minAlphaNum: (integer) Minimum number of alphanumeric characters.
 *     - minLength: (integer) Minimum length of the password.
 *     - minLower: (integer) Minimum number of lowercase characters.
 *     - minNumeric: (integer) Minimum number of numeric characters (0-9).
 *     - minSymbol: (integer) Minimum number of alphabetic characters.
 *     - minUpper: (integer) Minimum number of uppercase characters.
 *
 *   Alternatively/additionally, the minimum number of character classes can
 *   be configured by setting 'minClasses'. The valid range is 0 through 4
 *   character classes may be required for a password. The available classes:
 *     - lower
 *     - number
 *     - symbol
 *     - upper
 *
 *   For example: a password of 'p@ssw0rd' satisfies three classes ('number',
 *   'lower', and 'symbol'), while 'passw0rd' only satisfies two classes
 *   ('lower'and 'symbols').
 *
 * no_reset: (boolean) If true, do not reset the authenticated user's
 *           credentials on success.
 *
 * params: (array) Additional information that a driver needs. See examples
 *        below for further details.
 *
 * preferred: (string) Useful if you want to use the same backend.php file
 *            for different machines. If the hostname of the Passwd Machine is
 *            identical to one of those in the preferred list, then the
 *            corresponding option in the select box will include SELECTED,
 *            i.e. it is selected per default. Otherwise the first entry in
 *            the list is selected.
 */

$backends['hordeauth'] = array (
    'disabled' => true,
    'name' => 'Horde Authentication',
    'driver' => 'Horde',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
);

$backends['hordesql'] = array (
    // ENABLED by default
    'disabled' => false,
    'name' => 'Horde SQL Authentication',
    'driver' => 'Sql',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array_merge(
        $GLOBALS['conf']['sql'],
        array(
            'table' => 'horde_users',
            'user_col' => 'user_uid',
            'pass_col' => 'user_pass',
            'show_encryption' => false,
            'encryption' => isset($GLOBALS['conf']['auth']['params']['encryption']) ? $GLOBALS['conf']['auth']['params']['encryption'] : false
        )
    ),
);

$backends['poppassd'] = array(
    'disabled' => true,
    'name' => 'Poppassd Server',
    'driver' => 'Poppassd',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'host' => 'localhost',
        'port' => 106
    ),
);

$backends['servuftp'] = array(
    'disabled' => true,
    'name' => 'Serv-U FTP Server',
    'driver' => 'Servuftp',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'host' => 'localhost',
        'port' => 106,
        'timeout' => 30
    ),
);

$backends['expect'] = array(
    'disabled' => true,
    'name' => 'Expect Script',
    'driver' => 'Expect',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'program' => '/usr/bin/expect',
        'script' => PASSWD_BASE . '/scripts/passwd-expect',
        'params' => '-telnet -host localhost -output /tmp/passwd.log'
    ),
);

$backends['sudo_expect'] = array(
    'disabled' => true,
    'name' => 'Expect with Sudo Script',
    'driver' => 'Procopen',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'program' => '/usr/bin/expect ' . PASSWD_BASE . '/scripts/passwd_expect -sudo'
    ),
);

$backends['smbpasswd'] = array(
    'disabled' => true,
    'name' => 'Samba Server',
    'driver' => 'Smbpasswd',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'program' => '/usr/bin/smbpasswd',
        'host' => 'localhost'
    ),
);

// NOTE: to set the ldap userdn, see horde/config/hooks.php
$backends['ldap'] = array(
    'disabled' => true,
    'name' => 'LDAP Server',
    'driver' => 'Ldap',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'host' => 'localhost',
        'port' => 389,
        'basedn' => 'o=example.com',
        // LDAP object key attribute.
        'uid' => 'uid',
        // The attribute storing the password.
        'attribute' => 'userPassword',
        // These attributes will enable shadow password policies.
        // 'shadowlastchange' => 'shadowLastChange',
        // 'shadowmin' => 'shadowMin',
        // This will be appended to the username when looking for the userdn.
        'realm' => '',
        // Use this filter when searching for the user's DN.
        'filter' => '',
        // Hash method to use when storing the password
        'encryption' => 'crypt',
        // Whether to enable TLS for this LDAP connection
        // Note: make sure that the host matches cn in the server certificate.
        'tls' => false
    ),
);

// NOTE: to set the ldap userdn, see horde/config/hooks.php
$backends['ldapadmin'] = array(
    'disabled' => true,
    'name' => 'LDAP Server with Admin Bindings',
    'driver' => 'Ldap',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'host' => 'localhost',
        'port' => 389,
        'basedn' => 'o=example.com',
        'admindn' => 'cn=admin,o=example.com',
        'adminpw' => 'somepassword',
        // LDAP object key attribute.
        'uid' => 'uid',
        // The attribute storing the password.
        'attribute' => 'userPassword',
        // These attributes will enable shadow password policies.
        // 'shadowlastchange' => 'shadowLastChange',
        // 'shadowmin' => 'shadowMin',
        // This will be appended to the username when looking for the userdn.
        'realm' => '',
        // Use this filter when searching for the user's DN.
        'filter' => '',
        // Hash method to use when storing the password
        'encryption' => 'crypt',
        // If set, should be 0 or 1. See the LDAP documentation about the
        // corresponding parameter REFERRALS.
        // Windows 2003 Server require to set this parameter to 0
        // 'referrals' => 0,
        // Whether to enable TLS for this LDAP connection
        // Note: make sure that the host matches cn in the server certificate.
        'tls' => false
    ),
);

// NOTE: to set the ldap userdn, see horde/config/hooks.php
// NOTE: to make work with samba 2.x schema you must change lm_attribute and
// nt_attribute
$backends['smbldap'] = array(
    'disabled' => true,
    'name' => 'Samba/LDAP Server',
    'preferred' => '',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'driver' => 'Smbldap',
    'params' => array(
        'host' => 'localhost',
        'port' => 389,
        'basedn' => 'o=example.com',
        // LDAP object key attribute.
        'uid' => 'uid',
        // The attribute storing the password.
        'attribute' => 'userPassword',
        // This will be appended to the username when looking for the userdn.
        'realm' => '',
        // Use this filter when searching for the user's DN.
        'filter' => '',
        // Hash method to use when storing the password
        'encryption' => 'crypt',
        // Whether to enable TLS for this LDAP connection
        // Note: make sure that the host matches cn in the server certificate.
        'tls' => false,
        // If any of the following attributes are commented out, they
        // won't be set on the LDAP server.
        'lm_attribute' => 'sambaLMPassword',
        'nt_attribute' => 'sambaNTPassword',
        'pw_set_attribute' => 'sambaPwdLastSet',
        'pw_expire_attribute' => 'sambaPwdMustChange',
         // The number of days until samba passwords expire. If this
         // is commented out, passwords will never expire.
        'pw_expire_time' => 180,
    ),
);

$backends['sql'] = array (
    'disabled' => true,
    'name' => 'SQL Server',
    'driver' => 'Sql',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'phptype' => 'mysql',
        'hostspec' => 'localhost',
        'username' => 'dbuser',
        'password' => 'dbpasswd',
        'encryption' => 'md5-hex',
        'database' => 'db',
        'table' => 'users',
        'user_col' => 'user_uid',
        'pass_col' => 'user_pass',
        'show_encryption' => false
        // The following two settings allow you to specify custom queries for
        // lookup and modify functions if special functions need to be
        // performed.  In places where a username or a password needs to be
        // used, refer to this placeholder reference:
        //    %d -> gets substituted with the domain
        //    %u -> gets substituted with the user
        //    %U -> gets substituted with the user without a domain part
        //    %p -> gets substituted with the plaintext password
        //    %e -> gets substituted with the encrypted password
        //
        // 'query_lookup' => 'SELECT user_pass FROM horde_users WHERE user_uid = %u',
        // 'query_modify' => 'UPDATE horde_users SET user_pass = %e WHERE user_uid = %u',
    ),
);

$backends['mailmgr'] = array(
    'disabled' => true,
    'name' => 'VMailMgr Server',
    'driver' => 'Vmailmgr',
    'policy' => array(),
    'params' => array(
        'vmailinc' => '/your/path/to/the/vmail.inc'
    ),
);

$backends['vpopmail'] = array (
    'disabled' => true,
    'name' => 'Vpopmail Server',
    'driver' => 'Vpopmail',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'phptype' => 'mysql',
        'hostspec' => 'localhost',
        'username' => '',
        'password' => '',
        'encryption' => 'crypt',
        'database' => 'vpopmail',
        'table' => 'vpopmail',
        'name' => 'pw_name',
        'domain' => 'pw_domain',
        'passwd' => 'pw_passwd',
        'clear_passwd' => 'pw_clear_passwd',
        'use_clear_passwd' => true,
        'show_encryption' => true
    ),
);

$backends['pine'] = array(
    'disabled' => true,
    'name' => 'Pine Password File',
    'driver' => 'Pine',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'no_reset' => true,
    'params' => array(
        // FTP server information.
        'host' => 'localhost',
        'port' => '21',
        'path' => '',
        'file' => '.pinepw',
        // Connect using the just-passed-in password?
        'use_new_passwd' => false,
        // Host string to look for in the encrypted file.
        'imaphost' => 'localhost'
    ),
);

$backends['kolab'] = array(
    'disabled' => true,
    'name' => 'Local Kolab Server',
    'driver' => 'Kolab',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(),
);

$backends['myscript'] = array(
    'disabled' => true,
    'name' => 'Custom Script',
    'driver' => 'Procopen',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'program' => '/path/to/my/script + myargs'
    ),
);

// This is an example configuration for the http driver.  This allows
// connecting to an arbitrary URL that contains a password change form.
// The params 'username','oldPasswd','passwd1', and 'passwd2' params should be
// set to the name of the respective form input elements on the html form.  If
// there are additional form fields that the form requires, define them in the
// 'fields' array in the form 'formFieldName' => 'formFieldValue'.  The driver
// attempts to determine the success or failure based on searching the
// returned html page for the values listed in the 'eval_results' array.
$backends['http'] = array(
    'disabled' => true,
    'name' => 'HTTP Server',
    'driver' => 'Http',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        'url' => 'http://www.example.com/psoft/servlet/psoft.hsphere.CP',
        'username' => 'mbox',
        'oldPasswd' => 'old_password',
        'passwd1' => 'password',
        'passwd2' => 'password2',
        'fields' => array(
            'action' => 'change_mbox_password',
            'ftemplate' => 'design/mail_passw.html'
        ),
        'eval_results' => array(
            'success' => 'Password successfully changed',
            'badPass' => 'Bad old password',
            'badUser' => 'Mailbox not found'
        ),
    ),
);

$backends['soap'] = array(
    'disabled' => true,
    'name' => 'SOAP Server',
    'driver' => 'Soap',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array(
        // If this service doesn't have a WSDL, the 'location' and 'uri'
        // parameters below must be specified instead.
        'wsdl' => 'http://www.example.com/service.wsdl',
        'method' => 'changePassword',
        // This is the order of the arguments to the method specified above.
        'arguments' => array('username', 'oldpassword', 'newpassword'),
        // These parameters are directly passed to the SoapClient object, see
        // http://ww.php.net/manual/en/soapclient.soapclient.php for a
        // complete list of possible parameters.
        'soap_params' => array(
            'location' => '',
            'uri' => '',
         ),
    ),
);

// This is an example configuration for Postfix.admin 2.3.
// Set the 'password_policy' section as you wish.
// In most installations you probably only need to change the
// hostspec and/or  password fields.
$backends['postfixadmin'] = array (
    'disabled' => true,
    'name' => 'Postfix Admin server',
    'driver' => 'Sql',
    'policy' => array(
        'minLength' => 6,
        'maxLength' => 20,
        'minNumeric' => 1,
    ),
    'params' => array(
        'phptype' => 'mysql',
        'hostspec' => 'localhost',
        'username' => 'postfix',
        'password' => 'PASSWORD',
        'encryption' => 'crypt-md5',
        'database' => 'postfix',
        'table' => 'mailbox',
        'user_col' => 'username',
        'pass_col' => 'password',
        'show_encryption' => false,
        // The following two settings allow you to specify custom queries for
        // lookup and modify functions if special functions need to be
        // performed.  In places where a username or a password needs to be
        // used, refer to this placeholder reference:
        //    %d -> gets substituted with the domain
        //    %u -> gets substituted with the user
        //    %U -> gets substituted with the user without a domain part
        //    %p -> gets substituted with the plaintext password
        //    %e -> gets substituted with the encrypted password
        //
        'query_lookup' => 'SELECT password FROM mailbox WHERE username = %u and active = 1', 
        'query_modify' => 'UPDATE mailbox SET password = %e WHERE username = %u'
    ),
);

// This is an example configuration for chaining multiple drivers to allow for
// syncing of passwords across many backends using the composite driver as a
// wrapper.
//
// Each of the subdrivers may contain an optional parameter called 'required'
// that, when set to true, will cause the rest of the drivers be skipped if a
// particular one fails.
$backends['composite'] = array(
    'disabled' => true,
    'name' => 'All Services',
    'driver' => 'Composite',
    'policy' => array(
        'minLength' => 6,
        'minNumeric' => 1,
    ),
    'params' => array('drivers' => array(
        'sql' => array(
            'name' => 'Horde Authentication',
            'driver' => 'Sql',
            'required' => true,
            'params' => array(
                'phptype' => 'mysql',
                'hostspec' => 'localhost',
                'username' => 'horde',
                'password' => '',
                'encryption' => 'md5-hex',
                'database' => 'horde',
                'table' => 'horde_users',
                'user_col' => 'user_uid',
                'pass_col' => 'user_pass',
                'show_encryption' => false
                // 'query_lookup' => '',
                // 'query_modify' => '',
            ),
        ),
        'smbpasswd' => array(
            'name' => 'Samba Server',
            'driver' => 'Smbpasswd',
            'params' => array(
                'program' => '/usr/bin/smbpasswd',
                'host' => 'localhost',
            ),
        ),
    )),
);
