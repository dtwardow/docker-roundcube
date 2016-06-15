#!/bin/bash
# inspired by https://github.com/sameersbn/docker-gitlab
set -e

source ${SCRIPTS_DIR}/functions

[[ $DEBUG == true ]] && set -x

appInit () {
  # configure database and check connection
  finalize_database_parameters
  check_database_connection

  # set timezone for PHP
  #configure_timezone

  if [ -d ${WWW_DIR}/installer ]; then
    INSTALLER_ENABLED=true
  fi

  configure_webserver
  init_roundcube

  # set permission of mail dir and create if not exist
  mkdir -p ${DATA_DIR}
  chown -R www-data:www-data ${DATA_DIR}
  chmod u+w ${DATA_DIR}
}

appStart () {
  # start supervisord
  echo "Starting Apache..."
  exec apache2-foreground
}

appHelp () {
  echo "Available options:"
  echo " app:start          - Starts postfix and dovecot (default)"
  echo " app:check          - Checks the MySQL connection"
  echo " [command]          - Execute the specified linux command eg. bash."
}

case ${1} in
  app:start|app:check|app:pwGen)

    case ${1} in
      app:start)
        appInit
        appStart
      ;;
      app:check)
        appInit
        cat ${FILE_CONFIG_WWW}
      ;;
      app:pwGen)
        appPwGen
      ;;
    esac
    
    ;;
  app:help)
    appHelp
  ;;
  *)
    exec "$@"
  ;;
esac
