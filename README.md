# Hyperf 助手扩展包
## 安装
```shell
composer require death_satan/hyperf-helper:* -vvv
```
## 封装了一些常用的助手函数
# 函数列表
- redis 获取redis客户端实例 `需要载入 hyperf/redis`
- container 获取容器
- cache 获取简单缓存实例
- session 封装类似tp的session助手函数
- get_swoole_server 获取当前环境下的SwooleServer实例
- view 封装render模板渲染
- db 获取简单db对象
- logger 获取日志工厂对象`LoggerFactor`
- request 获取请求对象
- response 获取响应对象
- 更多待完善
