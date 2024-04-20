package Controller;

import Entity.Plat;
import Entity.Restaurant;
import Services.ServicePlat;
import Services.ServiceRestaurant;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.*;
import javafx.scene.layout.VBox;
import javafx.stage.FileChooser;

import java.io.File;
import java.io.IOException;
import java.sql.SQLException;
import java.util.List;

public class PlatManagement {
    @FXML
    private TextField image;

    @FXML
    private TextField nom;

    @FXML
    private TextField prix;

    @FXML
    private Button platbutton;

    @FXML
    private VBox vboxPlats;

    @FXML
    private ComboBox<Restaurant> restaurantComboBox;

    private ServicePlat servicePlat;
    private ServiceRestaurant serviceRestaurant;

    private String imagePath;

    @FXML
    void ajouter(ActionEvent event) {
        try {
            String nomPlat = nom.getText();
            String imagePlat = imagePath;
            float prixPlat = Float.parseFloat(prix.getText());
            Restaurant selectedRestaurant = restaurantComboBox.getValue();
            Plat plat = new Plat(nomPlat, imagePlat, prixPlat, selectedRestaurant);
            servicePlat.ajouter(plat);

            // Clear input fields after adding
            clearFields();

            // Refresh VBox
            refreshVBox();
        } catch (SQLException e) {
            e.printStackTrace();
            // Handle SQLException
        } catch (NumberFormatException e) {
            e.printStackTrace();
            // Handle NumberFormatException
        }
    }

    // Method to display an alert

    @FXML
    void selectrestaurant(ActionEvent event) {
        // Implement method to handle selection of restaurant
    }

    @FXML
    private void initialize() {
        servicePlat = new ServicePlat();
        serviceRestaurant = new ServiceRestaurant();
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/Plat.fxml"));
            loader.setController(this); // Set the controller for the FXMLLoader
            Parent root = loader.load();
            // Additional initialization code...
        } catch (IOException e) {
            e.printStackTrace();
            // Handle the IOException
        }
        populateRestaurantComboBox();
        refreshVBox();
    }


    private void populateRestaurantComboBox() {
        try {
            List<Restaurant> restaurants = serviceRestaurant.getAllRestaurants();
            restaurantComboBox.setItems(FXCollections.observableArrayList(restaurants));
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void refreshVBox() {
        vboxPlats.getChildren().clear();
        try {
            List<Plat> plats = servicePlat.getAllPlats();
            for (Plat plat : plats) {
                FXMLLoader loader = new FXMLLoader(getClass().getResource("/Plat.fxml"));
                PlatItemController controller = new PlatItemController(plat); // Create an instance of PlatItemController
                loader.setController(controller); // Set the controller for the FXMLLoader
                Parent platItem = loader.load();
                controller.setData(plat);
                vboxPlats.getChildren().add(platItem);
            }
        } catch (SQLException | IOException e) {
            e.printStackTrace();
        }
    }

    private void clearFields() {
        nom.clear();
        prix.clear();
        imagePath = null;
    }



    @FXML
    void moove(ActionEvent event) {
        // Your existing code for opening RestaurantManagement window
    }

    public void supprimer(ActionEvent actionEvent) {
        // Your method for deleting a Plat
    }

    public void modifier(ActionEvent actionEvent) {
        // Your method for modifying a Plat
    }

    @FXML
    private void selectImage(ActionEvent event) {
        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Choisir une image");
        fileChooser.getExtensionFilters().addAll(
                new FileChooser.ExtensionFilter("Image Files", "*.png", "*.jpg", "*.gif")
        );
        File selectedFile = fileChooser.showOpenDialog(null);
        if (selectedFile != null) {
            imagePath = selectedFile.getAbsolutePath();
            image.setText(imagePath); // Set the selected image path to the text field
        }
    }

}
