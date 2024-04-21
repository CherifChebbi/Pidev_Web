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
    private static MyDB instance;

    private MyDB() {
        try {
            Class.forName(driver);
            connection = DriverManager.getConnection(url, user, pwd);
        } catch (ClassNotFoundException | SQLException e) {
            System.err.println("Failed to connect to the database:");
            e.printStackTrace();
        }
    }

    public static MyDB getInstance() {
        if (instance == null) {
            instance = new MyDB();
        }
        return instance;
    }

    public Connection getConnection() {
        try {
            if (connection == null || connection.isClosed()) {
                connection = DriverManager.getConnection(url, user, pwd);
            }
        } catch (SQLException e) {
            System.err.println("Failed to obtain a database connection:");
            e.printStackTrace();
        }
        return connection;
    }

    public void closeConnection() {
        if (connection != null) {
            try {
                connection.close();
                System.out.println("Database connection closed.");
            } catch (SQLException e) {
                System.err.println("Error while closing database connection:");
                e.printStackTrace();
            }
        }
    }
}
