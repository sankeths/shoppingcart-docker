FROM alpine:3.7
MAINTAINER Sanketh Shanbhag<s.sanketh@gmail.com>

ENV code_root /code
ENV httpd_conf ${code_root}/httpd.conf




RUN yum install -y httpd
RUN yum install --enablerepo=epel,remi-php56,remi -y \
                              php \
                              php-cli \
                              php-gd \
                              php-intl\
                              php-xsl\
                              php-mbstring \
                              php-mcrypt \
                              php-mysqlnd \
                              php-pdo \
                              php-xml \
                              php-xdebug
RUN sed -i -e "s|^;date.timezone =.*$|date.timezone = Asia/Tokyo|" /etc/php.ini

ADD . $code_root
Add webroot/. "/var/www/html"

RUN test -e $httpd_conf && echo "Include $httpd_conf" >> /etc/httpd/conf/httpd.conf

EXPOSE 80
CMD [ cp -R "/code/webroot/." "/var/www/html"]
CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]
