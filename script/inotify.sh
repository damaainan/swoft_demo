#!/bin/bash

# 监控文件变化 修改配置文件及权限

export AILAB_V1_DIR=/var/www/html/swoftd/

export CNROMS_SRC=/var/www/html/swoftd/config/   # 同步的路径，请根据实际情况修改
/usr/local/inotify-tools-3.14/bin/inotifywait --exclude '\.(part|swp)|/var/www/html/swoftd/runtime|/var/www/html/swoftd/vendor|/var/www/html/swoftd/config' -r -mq -e  modify,move_self,create,delete,move,close_write $AILAB_V1_DIR | while read event;        
        do
                # rm -rf /var/www/html/swoftd/config
                # cp -rf /var/www/html/swoftd/env/config_test /var/www/html/swoftd/config

                chmod -R 755 /var/www/html/swoftd

                rm -rf /var/www/html/swoftd/runtime/logs
                # rm -rf /var/www/html/swoftd/runtime/temp
		# rm -rf /var/www/html/swoftd/runtime/cache
                #cd /var/www/html/swoftd/ && composer update >> /dev/null
                chmod -R 777 /var/www/html/swoftd/runtime
        done


# export COMPOSER_JSON=/var/www/html/swoftd/composer.json
# inotifywait  -r -mq -e  modify,move_self,create,delete,move,close_write $COMPOSER_JSON |

# while read event;
#       do
#               composer update
#       done