# -*- coding: UTF-8 -*-

from PIL import Image
import math
import os
import os.path




'''
本类用于对比图像相似度
只需要调用comple方法
'''
class VectorCompare:
    # 计算矢量大小
    def magnitude(self, concordance):
        total = 0
        for word, count in concordance.items():
            total += count ** 2
        return math.sqrt(total)

    # 计算矢量之间的 cos 值
    def relation(self, concordance1, concordance2):
        relevance = 0
        topvalue = 0
        for word, count in concordance1.items():
            if word in concordance2:
                topvalue += count * concordance2[word]
        return topvalue / (self.magnitude(concordance1) * self.magnitude(concordance2))


    def buildvector(self,im):
        d1 = {}
        count = 0
        for i in im.getdata():
            d1[count] = i
            count += 1
        return d1

#对比两张图像的相似度
    def comple(self,img1,img2):
        img1=img1.convert("P")
        img2=img2.convert("P")
        return self.relation(self.buildvector(img1),self.buildvector(img2))


#找出图片与哪个模型最相似
def compleModel(simg):
    v=VectorCompare()
    maxValue = 0  # 最大可能的概率
    maxType = ""  # 最大可能的分组
    # 加载训练集
    iconset = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']
    imageset = []
    for letter in iconset:
        for img in os.listdir('./iconset/%s/' % (letter)):
            temp = []
            if img != "Thumbs.db" and img != ".DS_Store":
                temp.append("./iconset/%s/%s" % (letter, img))
            imageset.append({letter: temp})

    for img in imageset:
        for x, y in img.items():
            simi=v.comple(Image.open(y[0]),simg)   #计算相似度
            if(simi>maxValue):
                maxValue=simi
                maxType=x[0]

    return (maxType)



#
# image1 = Image.open("./2.jpg")
#
#
# print(compleModel(image1))