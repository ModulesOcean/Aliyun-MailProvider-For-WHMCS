# Aliyun-MailProvider-For-WHMCS
![GitHub all releases](https://img.shields.io/github/downloads/ModulesOcean/Aliyun-MailProvider-For-WHMCS/total?style=for-the-badge)
![GitHub](https://img.shields.io/github/license/ModulesOcean/Aliyun-MailProvider-For-WHMCS?style=for-the-badge)

该模块可以 WHMCS 使用 阿里云邮件推送 通过邮件服务器向客户端发送电子邮件

## 要求
1. WHMCS 8.0 或更高版本
2. PHP 7.1 或更高版本

## 安装
1. 从[最新版本](https://github.com/ModulesOcean/Aliyun-MailProvider-For-WHMCS/releases/latest)下载源代码。
2. 上传模块源码到`/yourwhmcsdir/modules/mail/`
3. 转到您的 WHMCS 管理员，然后转到“系统设置->常规设置->邮件”。
4. 点击`Configure Mail Provider`并将`Mail Provider`切换为`AliyunMail`。
5. 前往阿里云 获取 Access Key 并填写。
6. 单击`Test Connection`。如果没有错误，将向当前管理员发送一封电子邮件。
7. 此时可以保存。

## 鸣谢

此项目鸣谢 [DMIT Dev Team](https://github.com/DMIT-Inc/)

## 执照
[MIT License](https://github.com/ModulesOcean/Aliyun-MailProvider-For-WHMCS/blob/main/LICENSE)
