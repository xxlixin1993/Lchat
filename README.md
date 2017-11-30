# Lchat

## 依赖

1. swoole扩展大于等于1.9.11
2. php版本大于等于7.0.0

## 安装
1. `composer update`
2. 修改`.env.example`为`.env`配置文件
3. `php start.php -h 127.0.0.1 -p 8000 -t websocket`,查看8000端口被监听则成功。


## 启动命令
```
Usage:   php start.php -h HOST -p PORT -t TYPE

-h       HOST    Server hostname (ex: 127.0.0.1).
-p       PORT    Server port (ex: 9501).
-t       TYPE    Socket type (ex: websocket), support[websocket, tcp, http].
```

## CS通信协议
使用json传输数据,格式暂定。
```
{
    "action":"test",
    "controller":"index"
}
```
示例
```
{
    "name":"lx",
    "passwd":123,
    "controller":"WebSocketChatDemo",
    "action":"test"
}
```
## 数据库建立
```
create database chat;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户UID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `salt` char(6) NOT NULL DEFAULT '' COMMENT '密码盐',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `createTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  UNIQUE KEY `username` (`username`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表'
```

## 错误码
|code|message|
|:---|:------|
|101|参数错误 如请输出正确的参数|
|102|扩展错误 如“请先安装swoole扩展”|
|201|权限错误 如“配置文件没有写权限”|
|301|程序内部错误 如“路由注册失败”|
