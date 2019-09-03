### 常用脚本


```sh
# 启动服务，根据 .env 配置决定是否是守护进程
php bin/swoft start

# 守护进程启动，覆盖 .env 守护进程(DAEMONIZE)的配置
php bin/swoft start -d

# 重启
php bin/swoft restart

# 重新加载
php bin/swoft reload

# 关闭服务
php bin/swoft stop
```

### 文件生成命令

`php bin/swoft gen`

## 生成http controller

使用命令 `php bin/swoft gen:controller*` 使用示例

    php bin/swoft gen:controller demo --prefix /demo -y          // Gen DemoController class to `@app/Controllers`
    php bin/swoft gen:controller user --prefix /users --rest     // Gen UserController class to `@app/Controllers`(RESTFul type)

> 更多选项信息请使用  `php bin/swoft gen:controller -h`  查看

## 生成http middleware

使用命令 `php bin/swoft gen:middleware*` 使用示例

    php bin/swoft gen:middleware demo    // Gen DemoMiddleware class to `@app/Middlewares`

> 更多选项信息请使用  `php bin/swoft gen:middleware -h`  查看

## 生成cli command

使用命令 `php bin/swoft gen:command*` 使用示例

    php bin/swoft gen:command demo     // Gen DemoCommand class to `@app/Commands`

> 更多选项信息请使用  `php bin/swoft gen:command -h`  查看

## 生成ws controller

使用命令 `php bin/swoft gen:websocket*` 使用示例

    php bin/swoft gen:websocket echo  // Gen EchoController class to `@app/WebSocket`

> 更多选项信息请使用  `php bin/swoft gen:websocket -h`  查看

## 生成事件监听器

使用命令 `php bin/swoft gen:listener*` 使用示例

    php bin/swoft gen:listener demo    // Gen DemoListener class to `@app/Listener`

## 生成自定义header头注释

使用命令：`php bin/swoft gen:controller --tpl-dir ./ --tpl-file header*` 使用示例

    php bin/swoft gen:controller abc --tpl-dir ./templates   // Gen DemoController class to `@app/Controllers`

把 `/vendor/swoft/devtool/res/templates` 目录拷贝出来放到自己想要放置的目录，本示例放在根目录。

修改 `file-header.stub` 文件，生成代码的使用 `--tpl-dir` 指定模版目录。

* `--tpl-dir` 注释文件所在目录
* `--tpl-file` 注释文件名称

> 更多选项信息请使用  `php bin/swoft gen:listener -h`  查看

