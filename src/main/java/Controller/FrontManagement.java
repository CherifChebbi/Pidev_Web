package Controller;

import Entity.Restaurant;
import Services.ServiceRestaurant;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.GridPane;

import java.sql.SQLException;
import java.util.List;

public class FrontManagement {
    @FXML
    private GridPane gridPane;

    @FXML
    private TextField searchNameField;

    @FXML
    private TextField searchLocationField;

    private ServiceRestaurant serviceRestaurant = new ServiceRestaurant();

    @FXML
    public void initialize() {
        try {
            displayRestaurants();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void displayRestaurants() throws SQLException {
        List<Restaurant> restaurantList = serviceRestaurant.afficher();

        populateGridPane(restaurantList);
    }

    private void populateGridPane(List<Restaurant> restaurants) {
        gridPane.getChildren().clear(); // Clear existing content from gridPane

        int column = 0;
        int row = 0;
        for (Restaurant restaurant : restaurants) {
            ImageView imageView = new ImageView(new Image("file:" + restaurant.getImage()));
            imageView.setFitWidth(200);
            imageView.setFitHeight(150);

            Label nameLabel = new Label(restaurant.getNom());

            gridPane.add(imageView, column, row);
            gridPane.add(nameLabel, column, row + 1); // Add the name below the image

            // Increment row and reset column if necessary
            column++;
            if (column == 3) {
                column = 0;
                row += 2; // Increment by 2 to leave space for image and name
            }
        }
    }

    @FXML
    public void search(ActionEvent actionEvent) {
        String nameFilter = searchNameField.getText();
        String locationFilter = searchLocationField.getText();

        try {
            List<Restaurant> filteredRestaurants = serviceRestaurant.afficher(nameFilter, locationFilter);
            populateGridPane(filteredRestaurants);
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
