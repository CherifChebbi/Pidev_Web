package Controller;

import Entity.Plat;
import Entity.Restaurant;
import Services.ServicePlat;
import Services.ServiceRestaurant;
import javafx.collections.FXCollections;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.VBox;
import javafx.stage.FileChooser;
import javafx.stage.Stage;

import java.io.File;
import java.io.IOException;
import java.sql.SQLException;
import java.util.List;

public class DashboardController {

    @FXML
    private TextField image;

    @FXML
    private TextField nom;

    @FXML
    private TextField prix;

    @FXML
    private VBox vboxPlats;

    @FXML
    private ComboBox<Restaurant> restaurantComboBox;

    private String imagePath;
    private ServicePlat servicePlat;
    private ServiceRestaurant serviceRestaurant;

    private Plat selectedPlat;

    private void setSelectedPlat(Plat plat) {
        this.selectedPlat = plat;
    }

    @FXML
    void ajouter(ActionEvent event) {
        try {
            String nomPlat = nom.getText();
            String imagePlat = imagePath;
            float prixPlat = Float.parseFloat(prix.getText());

            // Check if a restaurant is selected
            if (restaurantComboBox.getSelectionModel().getSelectedItem() != null) {
                Restaurant selectedRestaurant = restaurantComboBox.getSelectionModel().getSelectedItem();
                Plat plat = new Plat(nomPlat, imagePlat, prixPlat, selectedRestaurant);
                servicePlat.ajouter(plat);

                // Refresh VBox
                refreshVBox();
            } else {
                // Display an error message or handle the case where no restaurant is selected
                System.err.println("Error: No restaurant selected.");
            }
        } catch (SQLException | NumberFormatException e) {
            e.printStackTrace();
            // Handle exception
        }
    }



    // Method to add a Plat item to the VBox
    // Method to add a Plat item to the VBox


    // Method to add a Plat item to the VBox
    private void addPlatItem(Plat plat) {
        // Create labels for the Plat item
        Label nomLabel = new Label("Nom: " + plat.getNom());
        Label prixLabel = new Label("Prix: " + plat.getPrix());
        Label restaurantLabel = new Label("Restaurant: " + plat.getRestaurant().getNom()); // Access the restaurant's name

        // Create a button to show the image
        Button showImageButton = new Button("Show Image");

        // Set action for the button
        showImageButton.setOnAction(event -> {
            try {
                // Check if the image file exists
                File imageFile = new File(plat.getImage());
                if (!imageFile.exists()) {
                    System.err.println("Error: Image file does not exist.");
                    return;
                }

                // Load the image using an absolute file path
                Image image = new Image(imageFile.toURI().toString());

                // Check if the image was loaded successfully
                if (image.isError()) {
                    System.err.println("Error loading image: " + image.getException().getMessage());
                    return;
                }

                // Create and configure the dialog
                Dialog<ButtonType> dialog = new Dialog<>();
                dialog.setTitle("Plat Image");
                dialog.setHeaderText(null);

                // Create an ImageView to display the image
                ImageView dialogImageView = new ImageView(image);
                dialogImageView.setPreserveRatio(true);

                // Add the ImageView to the dialog's content pane
                dialog.getDialogPane().setContent(dialogImageView);

                // Add an OK button to close the dialog
                dialog.getDialogPane().getButtonTypes().add(ButtonType.OK);

                // Show the dialog
                dialog.showAndWait();
            } catch (Exception e) {
                e.printStackTrace();
            }
        });

        // Create a button for modifying the Plat
        Button modifierButton = new Button("Modifier");
        modifierButton.setOnAction(event -> {
            // Set the selected Plat
            setSelectedPlat(plat);
            // Populate the text fields with the selected Plat's information
            nom.setText(plat.getNom());
            prix.setText(String.valueOf(plat.getPrix()));
            image.setText(plat.getImage());
        });

        // Create a button for deleting the Plat
        Button supprimerButton = new Button("Supprimer");
        supprimerButton.setOnAction(event -> {
            try {
                // Delete the Plat from the database
                servicePlat.supprimer(plat.getId());

                // Refresh the VBox
                refreshVBox();
            } catch (SQLException e) {
                e.printStackTrace();
                // Handle exception
            }
        });

        // Create a VBox to hold the labels and buttons
        VBox platItemBox = new VBox();
        platItemBox.getChildren().addAll(nomLabel, prixLabel, restaurantLabel, showImageButton, modifierButton, supprimerButton);
        platItemBox.setSpacing(10); // Set spacing between elements
        platItemBox.setStyle("-fx-border-color: black; -fx-border-width: 1px;"); // Add border for styling

        // Add the VBox containing the Plat item to the main VBox
        vboxPlats.getChildren().add(platItemBox);
    }






    // Method to refresh the VBox with Plat items
    private void refreshVBox() {
        // Clear the existing contents of the VBox
        vboxPlats.getChildren().clear();

        // Fetch all Plat items from the database
        List<Plat> plats;
        try {
            plats = servicePlat.getAllPlats();
        } catch (SQLException e) {
            e.printStackTrace();
            // Handle SQLException
            return;
        }

        // Add each Plat item to the VBox
        for (Plat plat : plats) {
            addPlatItem(plat);
        }
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



    // Method to add a Plat item to the VBox

    @FXML
    private void initialize() {
        servicePlat = new ServicePlat();
        serviceRestaurant = new ServiceRestaurant();
        populateRestaurantComboBox();
    }

    private void populateRestaurantComboBox() {
        try {
            List<Restaurant> restaurants = serviceRestaurant.getAllRestaurants();
            restaurantComboBox.setItems(FXCollections.observableArrayList(restaurants));
        } catch (SQLException e) {
            e.printStackTrace();
            // Handle exception
        }
    }

    @FXML
    void selectRestaurant(ActionEvent event) {
        // Handle restaurant selection event if needed
    }

    @FXML
    public void modifier(ActionEvent actionEvent) {
        // Get the selected Plat from the VBox
        // Implement your logic here to retrieve the selected Plat item
        // For demonstration purposes, let's assume you have a selectedPlat variable

        // Modify the selected Plat object
        if (selectedPlat != null) {
            try {
                // Update the properties of the selected Plat object
                selectedPlat.setNom(nom.getText());
                selectedPlat.setImage(imagePath);
                selectedPlat.setPrix(Float.parseFloat(prix.getText()));

                // Update the Plat in the database
                servicePlat.modifier(selectedPlat);

                // Refresh the VBox
                refreshVBox();
            } catch (SQLException | NumberFormatException e) {
                e.printStackTrace();
                // Handle exception
            }
        } else {
            // Display an error message or handle the case where no Plat is selected
            System.err.println("Error: No Plat selected.");
        }
    }

    @FXML
    public void supprimer(ActionEvent actionEvent) {
        // Get the selected Plat from the VBox
        // Implement your logic here to retrieve the selected Plat item
        // For demonstration purposes, let's assume you have a selectedPlat variable

        // Delete the selected Plat object
        if (selectedPlat != null) {
            try {
                // Delete the Plat from the database
                servicePlat.supprimer(selectedPlat.getId());

                // Refresh the VBox
                refreshVBox();
            } catch (SQLException e) {
                e.printStackTrace();
                // Handle exception
            }
        } else {
            // Display an error message or handle the case where no Plat is selected
            System.err.println("Error: No Plat selected.");
        }
    }


    public void afficher(ActionEvent actionEvent) {

        refreshVBox();
    }

    public void goToRestaurant(ActionEvent actionEvent) {
    }

    public void navigateBack(MouseEvent mouseEvent) {
        try {
            // Load the FXML file for the restaurant view
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/restaurant.fxml"));
            Parent root = loader.load();

            // Create a new scene with the restaurant view
            Scene scene = new Scene(root);

            // Get the stage from the ImageView
            Stage stage = (Stage) ((ImageView) mouseEvent.getSource()).getScene().getWindow();

            // Set the new scene on the stage
            stage.setScene(scene);
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

}
