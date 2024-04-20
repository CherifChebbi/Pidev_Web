package Controller;

import Entity.Restaurant;
import Services.ServiceRestaurant;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.image.ImageView;
import javafx.stage.FileChooser;
import javafx.stage.Stage;

import java.io.File;
import java.io.IOException;
import java.sql.SQLException;
import java.util.List;
import java.util.regex.Pattern;

public class RestaurantManagement {
    ServiceRestaurant SR = new ServiceRestaurant();

    @FXML
    private TableView<Restaurant> afficher;

    @FXML
    private TextArea description;

    @FXML
    private TableColumn<Restaurant, String> descriptioncol;

    @FXML
    private Button managePlatButton;

    @FXML
    private TextField image;
    @FXML
    private TableColumn<Restaurant, ImageView> imagecol;

    @FXML
    private TextField localisation;

    @FXML
    private TableColumn<Restaurant, Integer> idcol;

    @FXML
    private TableColumn<Restaurant, String> locationcol;

    @FXML
    private TextField nom;

    @FXML
    private TableColumn<Restaurant, String> nomcol;

    @FXML
    private Button switchToPlatButton; // Button to switch to Plat view

    private static final Pattern STRING_PATTERN = Pattern.compile("^[a-zA-Zéèêçàâôûî\\s]+$");
    private static final Pattern INTEGER_PATTERN = Pattern.compile("^\\d+$");

    @FXML
    void ajouter(ActionEvent event) throws SQLException {
        String restaurantName = nom.getText().trim();
        String restaurantLocation = localisation.getText().trim();
        String imagePath = image.getText().trim();
        String restaurantDescription = description.getText().trim();

        // Input validation
        if (!isValidInput(restaurantName, STRING_PATTERN)) {
            showAlert("Error", "Le nom du restaurant ne peut pas contenir de chiffres ou de symboles.");
            return;
        }

        if (!isValidInput(restaurantLocation, STRING_PATTERN)) {
            showAlert("Error", "La localisation du restaurant ne peut pas contenir de chiffres ou de symboles.");
            return;
        }

        if (restaurantDescription.isEmpty()) {
            showAlert("Error", "Veuillez entrer une description pour le restaurant.");
            return;
        }

        if (restaurantName.isEmpty() || restaurantLocation.isEmpty() || imagePath.isEmpty()) {
            showAlert("Error", "Veuillez remplir tous les champs pour ajouter un restaurant.");
            return;
        }

        SR.ajouter(new Restaurant(restaurantName, restaurantLocation, imagePath, restaurantDescription));
        afficher();
        showAlert("Success", "Restaurant ajouté avec succès.");
    }

    @FXML
    void selectImage(ActionEvent event) {
        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Choisir une image");
        // Set extension filters if needed
        File selectedFile = fileChooser.showOpenDialog(null);
        if (selectedFile != null) {
            image.setText(selectedFile.getAbsolutePath());
        }
    }

    @FXML
    public void afficher() throws SQLException {
        List<Restaurant> restaurantList = SR.afficher();
        ObservableList<Restaurant> observableList = FXCollections.observableArrayList(restaurantList);
        afficher.setItems(observableList);
    }

    @FXML
    void modifier(ActionEvent event) throws SQLException {
        // Check if a row is selected
        Restaurant selectedRestaurant = afficher.getSelectionModel().getSelectedItem();
        if (selectedRestaurant != null) {
            // Retrieve updated data from input fields
            String newNom = nom.getText().trim();
            String newLocalisation = localisation.getText().trim();
            String newImage = image.getText().trim();
            String newDescription = description.getText().trim();

            // Input validation
            if (!isValidInput(newNom, STRING_PATTERN)) {
                showAlert("Error", "Le nom du restaurant ne peut pas contenir de chiffres ou de symboles.");
                return;
            }

            if (!isValidInput(newLocalisation, STRING_PATTERN)) {
                showAlert("Error", "La localisation du restaurant ne peut pas contenir de chiffres ou de symboles.");
                return;
            }

            if (newDescription.isEmpty()) {
                showAlert("Error", "Veuillez entrer une description pour le restaurant.");
                return;
            }

            // Update the selected Restaurant object
            selectedRestaurant.setNom(newNom);
            selectedRestaurant.setLocalisataion(newLocalisation);
            selectedRestaurant.setImage(newImage);
            selectedRestaurant.setDescription(newDescription);

            // Call the modifier method in ServiceRestaurant
            SR.modifier(selectedRestaurant);

            // Refresh TableView
            afficher();
            showAlert("Success", "Restaurant modifié avec succès.");
        } else {
            showAlert("Error", "Veuillez sélectionner un restaurant à modifier.");
        }
    }

    @FXML
    void supprimer(ActionEvent event) throws SQLException {
        Restaurant selectedRestaurant = afficher.getSelectionModel().getSelectedItem();
        if (selectedRestaurant != null) {
            SR.supprimer(selectedRestaurant);
            refreshTableView();
            showAlert("Success", "Restaurant supprimé avec succès.");
        } else {
            showAlert("Error", "Veuillez sélectionner un restaurant à supprimer.");
        }
    }

    private void refreshTableView() {
        try {
            afficher();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    @FXML
    private void initialize() {
        idcol.setCellValueFactory(new PropertyValueFactory<>("id"));
        nomcol.setCellValueFactory(new PropertyValueFactory<>("nom"));
        locationcol.setCellValueFactory(new PropertyValueFactory<>("localisation"));
        imagecol.setCellValueFactory(new PropertyValueFactory<>("image"));
        descriptioncol.setCellValueFactory(new PropertyValueFactory<>("description"));
    }

    @FXML
    void switchToPlat(ActionEvent event) {
        try {
            // Close the current window
            Stage stage = (Stage) switchToPlatButton.getScene().getWindow();
            stage.close();

            // Load the PlatManagement view from FXML
            javafx.fxml.FXMLLoader loader = new javafx.fxml.FXMLLoader(getClass().getResource("/Plat.fxml"));
            javafx.scene.Parent root = loader.load();

            // Show the PlatManagement view
            Stage platStage = new Stage();
            platStage.setTitle("Plat Management");
            platStage.setScene(new javafx.scene.Scene(root));
            platStage.show();
        } catch (IOException e) {
            e.printStackTrace(); // Handle error loading the PlatManagement view
        }
    }

    private void showAlert(String title, String content) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }

    private boolean isValidInput(String input, Pattern pattern) {
        return pattern.matcher(input).matches();
    }
}
