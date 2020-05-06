# mt 项目开发

## 1. 初始化项目
    git clone 
    git checkout -b dev
    git pull origin dev
    git push --set-upstream origin dev
    
## 2.使用步骤
    ##克隆项目完成之后请允许install.php完成初始化
    1. php install.php
    2. 导入database目录下面的数据库
    3. 执行composer install
    4. 将.env.testing 复制一份.env 如果不能连接数据库，请更改数据存储配置项(db/redis)
    
## 3.注意事项
    1. 请开发人员在dev分支下面开发，也可以针对每一个功能建立一个分支。开发完成之后请提交合并请求给master分支。
    2. debug情况下所有的请求都会用日志记录下来
    3. 日志和异常都记录在阿里云，请先联系相关人员开启阿里云子账号，然后到阿里云日志系统查看
    4. pull 的时候如果提示添加备注（文本界面），先按esc ，然后按住shift 输入小写x ，按回车
    
    
## 4. git迁移了

```
 sudo yum install -y curl policycoreutils-python openssh-server
 sudo systemctl enable sshd
 sudo systemctl start sshd
 sudo firewall-cmd --permanent --add-service=http
 sudo systemctl reload firewalld
 
 sudo yum install postfix
 sudo systemctl enable postfix
 sudo systemctl start postfix
 
 curl https://packages.gitlab.com/install/repositories/gitlab/gitlab-ce/script.rpm.sh | sudo bash
 
 sudo EXTERNAL_URL="http://gitlab.example.com" yum install -y gitlab-ce
```

### 4.1 
如果报错， 可能需要开启防火墙。
等等..

### 4.2
登录http://gitlab.example.com
充值密码