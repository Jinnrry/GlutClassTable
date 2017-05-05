/**
 * Created by jiangwei on 2017/5/3.
 */
public class ClassInfo {
    String name;
    int week;
    String no;
    String place;

    ClassInfo(String name,int week,String no,String place){
        this.name=name;
        this.week=week;
        this.no=no;
        this.place=place;

    }

    @Override
    public String toString() {
        return name+week+no+place+"\n";
    }
}
