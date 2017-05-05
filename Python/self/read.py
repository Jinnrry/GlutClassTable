# -*- coding: UTF-8 -*-
from binaryzation import binaryzation
from split import split
from comple import compleModel
from PIL import Image

'''
识别图片
'''
def read(img):
    result=""
    img=binaryzation(img)
    #img.show()
    imgArray=split(img)
    if not imgArray:
        return "文本粘连，分割失败"
    for singImg in imgArray:
        result+=compleModel(singImg)
    return result


