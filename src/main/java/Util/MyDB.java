package Util;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class MyDB {
    private final String user = "root";
    private final String pwd = "";
    private final String url = "jdbc:mysql://localhost/newversion";
    private final String driver = "com.mysql.cj.jdbc.Driver";
    private Connection connection;
    public static MyDB instance;

    private MyDB(){
        try {
            Class.forName(driver);
            connection = DriverManager.getConnection(url, user, pwd);
        } catch (ClassNotFoundException | SQLException e) {
            System.out.println(e.getMessage());
        }
    }

    public static MyDB getInstance(){
        if(instance == null){
            instance = new MyDB();
        }
        return instance;
    }

    public Connection getConnection() {
        return connection;
    }
}
