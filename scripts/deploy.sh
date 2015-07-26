
environment="centos_guest"

if [ "$#" -ge 1 ]; then
    environment=$1
fi


find . -name "*.sh" -exec chmod 755 {} \;

php bin/cli.php genEnvSettings dev /etc/profile.d/blog.sh

su blog -c "./scripts/deployAsUser.sh ${environment}"

sh ./autogen/addblog.sh