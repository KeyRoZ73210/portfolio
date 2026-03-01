#!/bin/bash

WEBSITE=$1
VHOSTS_CONF="/Applications/MAMP/conf/apache/extra/httpd-vhosts.conf"
HOSTS_FILE="/etc/hosts"

echo "Configuration du virtual host..."

printf "\n<VirtualHost *:80>\n    DocumentRoot \"/Applications/MAMP/htdocs/$WEBSITE/web\"\n    ServerName $WEBSITE.merci\n</VirtualHost>" >> $VHOSTS_CONF

sudo sed -ie "7s/$/ $WEBSITE.merci/g" $HOSTS_FILE

echo "Virtual host $WEBSITE.merci configuré !"
echo "Redémarre MAMP pour appliquer les changements."