<?php
$config['db_dsnw'] = '{{DB_DSNW}}';

$config['default_host'] = '{{IMAP_PROTOCOL}}{{IMAP_HOST}}';
$config['default_port'] = {{IMAP_PORT}};
$config['imap_conn_options'] = array(
  'ssl' => array(
    'verify_peer'       => false,
    'verify_peer_name'  => false
  ),
);

$config['smtp_server'] = '{{SMTP_PROTOCOL}}{{SMTP_HOST}}';
$config['smtp_port'] = {{SMTP_PORT}}; 
$config['smtp_user'] = '%u';
$config['smtp_pass'] = '%p';
$config['smtp_conn_options'] = array(
  'ssl' => array(
    'verify_peer'       => false,
    'verify_peer_name'  => false
  ),
);

$config['support_url'] = '';
$config['des_key'] = '{{ENC_KEY}}';

$config['plugins'] = array(
  'managesieve',
  'emoticons',
  'zipdownload'
);

$config['language'] = '{{UI_LANG}}';
$config['spellcheck_engine'] = 'atd';

$config['mail_pagesize'] = 250;
$config['addressbook_pagesize'] = 250;
$config['htmleditor'] = 2;
$config['preview_pane'] = true;

$config['login_lc'] = 2;
$config['identities_level'] = 3;
$config['auto_create_user'] = true;

$config['enable_installer'] = {{INSTALLER_ENABLED}};
$config['debug_level'] = {{DEBUG_LEVEL}};


$config['managesieve_host'] = '{{SIEVE_PROTOCOL}}{{SIEVE_HOST}}';
$config['managesieve_port'] = {{SIEVE_PORT}};
$config['managesieve_conn_options'] = array(
  'ssl' => array(
    'verify_peer'       => false,
    'verify_peer_name'  => false
  ),
);

ini_set('date.timezone', '{{TIMEZONE}}');

?>
