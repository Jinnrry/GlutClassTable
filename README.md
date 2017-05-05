该项目是通过爬虫去爬取桂林理工大学教务处的课表信息<br>
一共有3种语言的实现：<br>
java使用httpclient实现，但未识别验证码<br>
php使用CURL模块实现，使用OcrKing识别验证码（一个免费的在线验证码识别平台）<br>
Python使用requests模块实现，验证码使用tesseract或自己实现的算法识别<br>


ps：哎！我真是闲得蛋疼啊，同一个项目使用3种语言去实现