package Controller;

import Entity.Reservation;
import Entity.Restaurant;
import Services.ServiceReservation;
import Services.ServiceRestaurant;
import javafx.collections.FXCollections;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.Node;
import javafx.scene.control.ComboBox;
import javafx.scene.control.DatePicker;
import javafx.scene.control.Label; // Add this import for Label
import javafx.scene.control.TextField;
import javafx.scene.layout.GridPane;

import java.sql.SQLException;
import java.util.List;
import java.time.LocalDate;
import javafx.scene.control.ScrollPane;



public class ReservationManagement {

    @FXML
    private ComboBox<Restaurant> idres;

    @FXML
    private TextField nom;

    @FXML
    private TextField email;

    @FXML
    private DatePicker datePicker;

    @FXML
    private TextField nbrpersonne;

    @FXML
    private GridPane reservationGridPane;
    @FXML
    private ScrollPane reservationScrollPane;



    private final ServiceReservation serviceReservation = new ServiceReservation();
    private final ServiceRestaurant serviceRestaurant = new ServiceRestaurant();
    private Restaurant selectedRestaurant;

    @FXML
    void ajouter(ActionEvent event) {
        try {
            // Get data from fields
            Restaurant selectedRestaurant = idres.getValue();
            String selectedRestaurantName = selectedRestaurant.getNom();
            int selectedRestaurantId = selectedRestaurant.getIdR();
            String reservationNom = nom.getText();
            String reservationEmail = email.getText();
            String reservationDate = datePicker.getValue().toString();
            int reservationNbrPersonne = Integer.parseInt(nbrpersonne.getText());

            // Create a new reservation object
            Reservation newReservation = new Reservation(0, selectedRestaurantId, reservationNom, reservationEmail, reservationDate, reservationNbrPersonne);
            newReservation.setSelectedRestaurant(selectedRestaurant); // Set the selected restaurant for this reservation

            // Add reservation to the database
            serviceReservation.ajouter(newReservation);

            // Refresh the grid view
            refreshGridView();

        } catch (SQLException | NumberFormatException e) {
            e.printStackTrace();
            // Handle SQLException or NumberFormatException
        }
    }






    // Add other methods here...

    private void refreshGridView() {
        try {
            // Clear existing content in the scroll pane
            reservationScrollPane.setContent(null);

            // Retrieve all reservations with associated restaurant names
            List<Reservation> reservations = serviceReservation.afficher();

            // Create a new GridPane for displaying reservations
            GridPane gridPane = new GridPane();
            gridPane.setStyle("-fx-border-color: #CCCCCC; -fx-border-width: 1px;");
            gridPane.setHgap(10);
            gridPane.setVgap(10);

            // Add each reservation within a container to the grid
            int row = 0;
            int col = 0;
            for (Reservation reservation : reservations) {
                // Create a new container (GridPane) for the reservation
                GridPane reservationContainer = new GridPane();
                reservationContainer.setPrefSize(300, 150); // Set preferred size of container

                // Add reservation details to the container using labels
                Label idLabel = new Label("Reservation ID: " + reservation.getId());
                Label nameLabel = new Label("Nom: " + reservation.getNom());
                Label emailLabel = new Label("Email: " + reservation.getEmail());
                Label dateLabel = new Label("Date: " + reservation.getDate());
                Label nbrPersonneLabel = new Label("Nombre Personne: " + reservation.getNbrPersonne());
                Label restaurantLabel = new Label("Restaurant: " + reservation.getRestaurant().getNom()); // Use the fetched restaurant name

                // Add labels to the container
                reservationContainer.add(idLabel, 0, 0);
                reservationContainer.add(nameLabel, 0, 1);
                reservationContainer.add(emailLabel, 0, 2);
                reservationContainer.add(dateLabel, 0, 3);
                reservationContainer.add(nbrPersonneLabel, 0, 4);
                reservationContainer.add(restaurantLabel, 0, 5); // Add the restaurant label

                // Add container to the main grid pane
                gridPane.add(reservationContainer, col, row);

                // Set user data for the container
                // Set user data for the container
                reservationContainer.setUserData(reservation);


                // Add event handler to handle selection of container
                reservationContainer.setOnMouseClicked(event -> handleContainerSelection(reservationContainer));

                // Update row and col counters
                col++;
                if (col == 3) {
                    col = 0;
                    row++;
                }
            }

            // Set the GridPane as content for the scroll pane
            reservationScrollPane.setContent(gridPane);

        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private void handleContainerSelection(GridPane selectedContainer) {
        // Get the selected reservation from the container's user data
        Reservation selectedReservation = (Reservation) selectedContainer.getUserData();

        // Set the fields with the details of the selected reservation
        nom.setText(selectedReservation.getNom());
        email.setText(selectedReservation.getEmail());
        datePicker.setValue(LocalDate.parse(selectedReservation.getDate()));
        nbrpersonne.setText(String.valueOf(selectedReservation.getNbrPersonne()));
    }

    @FXML
    void modifier(ActionEvent event) {
        try {
            // Ensure reservationScrollPane is initialized
            if (reservationScrollPane == null || reservationScrollPane.getContent() == null) {
                System.out.println("Reservation scroll pane is not initialized or has no content.");
                return;
            }

            // Get the selected reservation from the GridPane
            Reservation selectedReservation = getSelectedReservation();

            if (selectedReservation != null) {
                // Update reservation details with new values from input fields
                selectedReservation.setNom(nom.getText());
                selectedReservation.setEmail(email.getText());
                selectedReservation.setDate(datePicker.getValue().toString());
                selectedReservation.setNbrPersonne(Integer.parseInt(nbrpersonne.getText()));

                // Call the service method to update the reservation in the database
                serviceReservation.modifier(selectedReservation);

                // Refresh the grid view after modification
                refreshGridView();
            } else {
                // Handle case when no reservation is selected
                System.out.println("Please select a reservation to modify.");
            }
        } catch (SQLException | NumberFormatException e) {
            e.printStackTrace();
            // Handle SQLException or NumberFormatException
        }
    }

    @FXML
    void supprimer(ActionEvent event) {
        try {
            // Get the selected reservation from the fields
            Reservation selectedReservation = (Reservation) reservationGridPane.getChildren().stream()
                    .filter(node -> node instanceof GridPane)
                    .filter(node -> ((GridPane) node).getChildren().contains(event.getTarget()))
                    .map(Node::getUserData)
                    .findFirst()
                    .orElse(null);

            if (selectedReservation != null) {
                // Call the service method to delete the reservation from the database
                serviceReservation.supprimer(selectedReservation);

                // Refresh the grid view after deletion
                refreshGridView();
            } else {
                // Handle case when no reservation is selected
                System.out.println("Please select a reservation to delete.");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }



    @FXML
    void initialize() {
        try {
            // Initialize the ComboBox with restaurant data
            List<Restaurant> restaurants = serviceRestaurant.getAllRestaurants();
            idres.setItems(FXCollections.observableArrayList(restaurants));

            // Add a listener to ComboBox selection
            idres.getSelectionModel().selectedItemProperty().addListener((observable, oldValue, newValue) -> {
                selectedRestaurant = newValue; // Update selectedRestaurant when ComboBox selection changes
                refreshGridView(); // Refresh the grid view when selection changes
            });
        } catch (SQLException e) {
            e.printStackTrace();
        }

        // Initialize reservationGridPane
        reservationGridPane = new GridPane();

        // Refresh the grid view
        refreshGridView();
    }

    private Reservation getSelectedReservation() {
        // Get the selected container from the reservationScrollPane
        GridPane selectedContainer = (GridPane) reservationScrollPane.getContent();

        if (selectedContainer != null) {
            // Get the selected reservation from the container's user data
            Reservation selectedReservation = (Reservation) selectedContainer.getUserData();
            return selectedReservation;
        }

        return null;
    }




}