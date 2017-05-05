# -*- coding: UTF-8 -*-

from PIL import Image
#
#图片二值化
#将图像转换为灰度图，并清除背景
#
def binaryzation(im):
    im = im.convert("P")       #彩图转换为灰度图
    im2 = Image.new("P", im.size, 255)
    for x in range(im.size[1]):
        for y in range(im.size[0]):
            pix = im.getpixel((y, x))
            if pix <= 70:       # 如果色度小于70，将图像设置为黑色
                im2.putpixel((y, x), 0)
    return im2

