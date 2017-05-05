# -*- coding: UTF-8 -*-
from PIL import Image
from binaryzation import binaryzation
#
#字符串分割
#
#参数为二值化处理过的Image对象
def split(img):
  inletter = False
  foundletter = False
  start = 0
  end = 0
  img=img.convert("L")
  new_imgs=[]   #切割后的图片
  for x in range(img.size[0]):
      for y in range(img.size[1]):
          pix = img.getpixel((x, y))
          if pix != 255:  # 不是白色
              inletter = True
      if foundletter == False and inletter == True:
          foundletter = True
          start = x

      if foundletter == True and inletter == False:
          foundletter = False
          end = x

          if end - start >= 11:      #如果字符宽度超过这个值则判断为粘连
              parserCode = False
              return False
          else:
              new_imgs.append(img.crop((start,0,end,img.size[1])))
      inletter = False


  #开始切割



  return new_imgs


# b=binaryzation(Image.open("1.jpg"))
#
# for i in split(b):
#     i.show()