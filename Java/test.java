import org.apache.http.Consts;
import org.apache.http.NameValuePair;
import org.apache.http.client.config.CookieSpecs;
import org.apache.http.client.config.RequestConfig;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.LinkedList;
import java.util.List;
import java.util.Scanner;
import java.util.regex.Matcher;
import java.util.regex.Pattern;


public class test {


    public String gethtml(String week) throws IOException {
        RequestConfig requestConfig = RequestConfig.custom().setCookieSpec(CookieSpecs.STANDARD_STRICT).build();
        CloseableHttpClient httpClient = HttpClients.custom().setDefaultRequestConfig(requestConfig).build();
        List<NameValuePair> valuePairs = new LinkedList<NameValuePair>();
        valuePairs.add(new BasicNameValuePair("j_username", "**********"));
        valuePairs.add(new BasicNameValuePair("j_password", "**********"));
        valuePairs.add(new BasicNameValuePair("groupId", ""));

        //获取验证码
        HttpGet getCaptcha = new HttpGet("http://202.193.80.58:81/academic/getCaptcha.do");
        CloseableHttpResponse imageResponse = httpClient.execute(getCaptcha);
        FileOutputStream out = new FileOutputStream(new File("zhihu.gif"));
        byte[] bytes = new byte[8192];
        int len;
        while ((len = imageResponse.getEntity().getContent().read(bytes)) != -1) {
            out.write(bytes, 0, len);
        }
        out.close();
        //请用户输入验证码
        System.out.print("请输入验证码：");
        Scanner scanner = new Scanner(System.in);
        String captcha = scanner.next();
        valuePairs.add(new BasicNameValuePair("j_captcha", captcha));
        //完成登陆请求的构造
        UrlEncodedFormEntity entity = new UrlEncodedFormEntity(valuePairs, Consts.ASCII);
        HttpPost post = new HttpPost("http://202.193.80.58:81/academic/j_acegi_security_check");
        post.setEntity(entity);
        httpClient.execute(post);//登录
        // HttpGet g = new HttpGet("http://202.193.80.58:81/academic/showPersonalInfo.do");//获取“我的信息”页面
        //CloseableHttpResponse r = httpClient.execute(g);
        HttpGet g = new HttpGet("http://202.193.80.58:81/academic/accessModule.do?moduleId=2000&groupId=");//获取“课表页面”页面
        CloseableHttpResponse classtable = httpClient.execute(g);
        // System.out.println(EntityUtils.toString(classtable.getEntity()));
        classtable.close();
        /*
        * 获取周次课表
        * */
        String url="";
        if (week.length()!=0) {
            url = "http://202.193.80.58:81/academic/manager/coursearrange/studentWeeklyTimetable.do?yearid=37&termid=1&whichWeek=" + week;
        }
        else {
            url = "http://202.193.80.58:81/academic/manager/coursearrange/studentWeeklyTimetable.do?yearid=37&termid=1";

        }
        HttpGet weektable = new HttpGet(url);
        CloseableHttpResponse wt = httpClient.execute(weektable);
        String htmlinfo = EntityUtils.toString(wt.getEntity());

        wt.close();

        return  htmlinfo;

    }

    public List<ClassInfo> spliinfo(String htmlinfo ){
        List<String> name=new LinkedList<>();
        List<String> no=new LinkedList<>();
        List<String> place=new LinkedList<>();
        List<Integer> week =new LinkedList<>();



        String pat = "<td name=\"td1\">.+</td>";
        Pattern p = Pattern.compile(pat);
        Matcher m = p.matcher(htmlinfo);
        while (m.find())
        {
            for (int i = 0; i < m.groupCount() + 1; i++)
            {
                String info=m.group(i).substring(15);
                //System.out.println(m.group(i));
                name.add(info.substring(0,info.length()-5));

            }
            //System.out.println("====================");
        }

        /*-------获取课程名结束-------------*/
        String wpat = "<td name=\"td5\">.+</td>";
        Pattern wp = Pattern.compile(wpat);
        Matcher wm = wp.matcher(htmlinfo);
        while (wm.find())
        {
            for (int i = 0; i < wm.groupCount() + 1; i++)
            {
//                System.out.println(wm.group(i));
                switch (wm.group(i)){
                    case "<td name=\"td5\">星期一</td>":
                        week.add(1);
                        break;
                    case "<td name=\"td5\">星期二</td>":
                        week.add(2);
                        break;
                    case "<td name=\"td5\">星期三</td>":
                        week.add(3);
                        break;
                    case "<td name=\"td5\">星期四</td>":
                        week.add(4);
                        break;
                    case "<td name=\"td5\">星期五</td>":
                        week.add(5);
                        break;

                }
            }
//            System.out.println("====================");
        }
        /*-------获取星期结束-------------------*/

        String rpat = "<td name=\"td10\">.+</td>";
        Pattern rp = Pattern.compile(rpat);
        Matcher rm = rp.matcher(htmlinfo);
        while (rm.find())
        {
            for (int i = 0; i < rm.groupCount() + 1; i++)
            {
//                System.out.println(rm.group(i));
                String info =rm.group(i).substring(16);
                place.add(info.substring(0,info.length()-5));
            }
//            System.out.println("====================");
        }
        /*---------获取教室结束--------*/

        String npat = "<td name=\"td6\">.+</td>";
        Pattern np = Pattern.compile(npat);
        Matcher nm = np.matcher(htmlinfo);
        while (nm.find())
        {
            for (int i = 0; i < nm.groupCount() + 1; i++)
            {
//                System.out.println(nm.group(i));
                switch (nm.group(i)){
                    case "<td name=\"td6\">第1、2节</td>" :
                        no.add("12");
                        break;
                    case "<td name=\"td6\">第3、4节</td>" :
                        no.add("34");
                        break;
                    case "<td name=\"td6\">第5、6节</td>" :
                        no.add("56");
                        break;
                    case "<td name=\"td6\">第7、8节</td>" :
                        no.add("78");
                        break;

                }
            }
//            System.out.println("====================");
        }
/*---------获取节次结束-----------*/

        List<ClassInfo> CIist=new LinkedList<>();
        for(int i=0;i<no.size();i++){
            CIist.add(new ClassInfo(name.get(i),week.get(i),no.get(i),place.get(i)));
        }
        return CIist;

    }

    public static void main(String[] args) throws IOException {
        test t=new test();


        System.out.println(t.spliinfo(t.gethtml("10")));
    }

}
