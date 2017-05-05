# coding:utf-8
import io
import time
from PIL import Image
import pytesseract
import requests
from bs4 import BeautifulSoup
from read import read


class getinfo():
    def __init__(self, sid, pwd):
        self.sid = sid
        self.pwd = pwd
        self.headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:48.0) Gecko/20100101 Firefox/48.0'}
        self.s = requests.Session()

    def getCaptcha(self):
        re = self.s.get("http://202.193.80.58:81/academic/getCaptcha.do")
        image_bytes = re.content
        data_stream = io.BytesIO(image_bytes)
        captcha = Image.open(data_stream)
        strcat = read(captcha)    #使用空间向量对比识别验证码
        # print(strcat)
        return strcat


    # 模拟用户登录，获取课表前需要先调用该函数
    # 返回值  ：  1 表示登录成功
    # -1表示密码错误
    # -2表示学号不存在
    def login(self):
        while (True):
            captcha = self.getCaptcha()
            data = {'j_username': self.sid,
                    'j_password': self.pwd,
                    'groupId': '',
                    'j_captcha': captcha
                    }
            re = self.s.post("http://202.193.80.58:81/academic/j_acegi_security_check", data, self.headers)
            if (re.url == "http://202.193.80.58:81/academic/index_new.jsp"):
                return 1
            if (re.text.find("密码不匹配") != -1):
                return -1
            if (re.text.find("不存在") != -1):
                return -2

    # 获取某周次的课表信息，返回json数组
    # zhouci输入0表示获取本周课表数据
    def classtableinfo(self, zhouci=1):
        self.s.get("http://202.193.80.58:81/academic/student/currcourse/currcourse.jsdo?groupId=&moduleId=2000")
        if zhouci != 0:
            re = self.s.get(
                "http://202.193.80.58:81/academic/manager/coursearrange/studentWeeklyTimetable.do?yearid=37&termid=1&whichWeek=" + str(
                    zhouci))
        else:
            re = self.s.get(
                "http://202.193.80.58:81/academic/manager/coursearrange/studentWeeklyTimetable.do?yearid=37&termid=1")
        htmltext = re.text
        soup = BeautifulSoup(htmltext, "html.parser")
        time = soup.find_all('td', {'name': 'td0'})
        name = soup.find_all('td', {'name': 'td1'})
        type = soup.find_all('td', {'name': 'td2'})  # 必修  选修

        week = soup.find_all('td', {'name': 'td5'})  # 星期几上课
        no = soup.find_all('td', {'name': 'td6'})  # 第几节课

        place = soup.find_all('td', {'name': 'td10'})  # 上课地点

        # 将一周的课程信息以json格式保存到数组中

        classinfo = '{"zhouci":' + str(zhouci) + ', "data" :  [';
        i = 0
        for t in time:
            classinfo += '{"name": " ' + name[i].string + '", "week": "' + week[i].string + '","type":"' + type[
                i].string + '","no":"' + no[i].string + '", "time": "' + t.string + '"}'
            # print(strs)
            i += 1
            if (i != len(time)):
                classinfo += ","

        classinfo += "]}"
        print(classinfo)
        return classinfo


g = getinfo("*******", "*******")
g.login()
g.classtableinfo(0)


