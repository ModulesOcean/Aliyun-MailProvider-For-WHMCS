# Aliyun-MailProvider-For-WHMCS
该模块可以 WHMCS 使用 阿里云邮件推送 通过邮件服务器向客户端发送电子邮件

## 要求
1. WHMCS 8.0 或更高版本
2. PHP 7.1 或更高版本

## 安装
1. 从[最新版本](https://github.com/ModulesOcean/Aliyun-MailProvider-For-WHMCS/releases/latest)下载源代码。
2. 上传模块源码到`/yourwhmcsdir/modules/mail/`
3. 转到您的 WHMCS 管理员，然后转到“系统设置->常规设置->邮件”。
4. 点击`Configure Mail Provider`并将`Mail Provider`切换为`PostalMail`。
5. 用`https://`前缀填写你的邮政服务器的URL ，例如`https://yourserver.com`
6. 填写您的 Postal HTTP API 密钥并单击“测试配置”。如果没有错误，邮政将向当前管理员发送电子邮件。
7. 此时可以保存。

## 鸣谢

此项目鸣谢 [DMIT Dev Team](https://github.com/DMIT-Inc/)

## 执照
[MIT License](https://github.com/ModulesOcean/Aliyun-MailProvider-For-WHMCS/blob/main/LICENSE)
