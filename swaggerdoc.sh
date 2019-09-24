

# 生成 swagger 文档
php vendor/zircote/swagger-php/bin/openapi app/Doc/ -o public/swagger/swagger.json
# php vendor/zircote/swagger-php/bin/openapi app/Doc/ -o static/swagger-doc/swagger.json

# 写一个 php 脚本处理 json 文件为符合要求的格式
php deal