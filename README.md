# -
桂林理工大学课表爬虫系统（清华大学教务系统）

使用PHP的CURL爬虫爬取教务系统课表并使用OCRKing识别验证码，用户第一次使用需要输入教务处密码，输入后即将密码保存到数据库，以后使用不必再次输入。
在布置的时候注意修改mysqli文件

改项目验证码部分使用了OcrKing。代码中删除了OcrKing的key，请自己申请。

OcrKing的Gihub地址：https://github.com/AvensLab/OcrKing

OcrKing官网：http://lab.ocrking.com/

该项目暂时还没写界面部分，仅仅完成了其功能

V1.0版本，实现基本功能，未使用缓存，导致程序的运行很慢

V1.1版本，新增对Linux服务器的支持

注意：使用时先在数据库运行userpassword.sql文件，并且根据你的服务器系统，修改index.php文件内容
