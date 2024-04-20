package Controller;

import Entity.Plat;
import Entity.Restaurant;
import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.VBox;

public class PlatItemController {

    @FXML
    private ImageView imageView;

    @FXML
    private Label nomLabel;

    @FXML
    private Label prixLabel;

    @FXML
    private Label restaurantLabel;

    private Plat plat;

    // Constructor to accept Plat object
    public PlatItemController(Plat plat) {
        this.plat = plat;
    }

    // Method to initialize data
    public void initialize() {
        setData(plat);
    }

    // Method to set data to UI components
    public void setData(Plat plat) {
        nomLabel.setText("Nom: " + plat.getNom());
        prixLabel.setText("Prix: " + plat.getPrix());
        Image image = new Image("file:" + plat.getImage());
        imageView.setImage(image);

        // Check if the associated Restaurant is not null
        Restaurant restaurant = plat.getRestaurant();
        if (restaurant != null) {
            restaurantLabel.setText("Restaurant: " + restaurant.getNom());
        } else {
            restaurantLabel.setText("Restaurant: N/A");
        }
    }


}
