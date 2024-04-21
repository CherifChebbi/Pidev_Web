package test;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

import java.io.IOException;

public class MainFront extends Application {
    @Override
    public void start(Stage stage) throws Exception {
        FXMLLoader loader = new FXMLLoader(MainFX.class.getResource("/Front.fxml"));
        Parent root = loader.load();
        Scene scene = new Scene(root);
        stage.setTitle("Teranova");
        stage.setScene(scene);
        stage.show();
    }



}
