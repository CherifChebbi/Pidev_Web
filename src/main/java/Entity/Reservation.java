package Entity;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;
import javafx.beans.property.StringProperty;
import javafx.beans.property.SimpleStringProperty;

public class Reservation {
    private int id;
    private IntegerProperty restaurantId;
    private StringProperty nom;
    private StringProperty email;
    private StringProperty date;
    private int nbrPersonne;
    private final IntegerProperty reservationId;
    private Restaurant selectedRestaurant;

    public Restaurant getSelectedRestaurant() {
        return selectedRestaurant;
    }

    public void setSelectedRestaurant(Restaurant selectedRestaurant) {
        this.selectedRestaurant = selectedRestaurant;
    }
    private Restaurant restaurant;

    public Restaurant getRestaurant() {
        return restaurant;
    }

    public void setRestaurant(Restaurant restaurant) {
        this.restaurant = restaurant;
    }

    // Constructors
    public Reservation(int reservationId, int restaurantId) {
        this.reservationId = new SimpleIntegerProperty(reservationId);
        this.restaurantId = new SimpleIntegerProperty(restaurantId);
        this.nom = new SimpleStringProperty("");
        this.email = new SimpleStringProperty("");
        this.date = new SimpleStringProperty(""); // Initialize date property
    }


    public Reservation(int reservationId, int restaurantId, String nom, String email, String date, int nbrPersonne) {
        this.reservationId = new SimpleIntegerProperty(reservationId);
        this.restaurantId = new SimpleIntegerProperty(restaurantId);


        this.nom = new SimpleStringProperty(nom);
        this.email = new SimpleStringProperty(email);
        this.date = new SimpleStringProperty(date);
        this.nbrPersonne = nbrPersonne;
    }

    public Reservation(int selectedRestaurantId, String nom, String email, String date, int nbrPersonne, IntegerProperty reservationId) {
        this.reservationId = reservationId;
        this.date = new SimpleStringProperty(date); // Initialize date property
    }

    // Getters and setters for properties
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getRestaurantId() {
        return restaurantId.get();
    }

    public void setRestaurantId(int restaurantId) {
        this.restaurantId.set(restaurantId);
    }

    public IntegerProperty restaurantIdProperty() {
        return restaurantId;
    }

    public String getNom() {
        return nom.get();
    }

    public void setNom(String nom) {
        this.nom.set(nom);
    }

    public StringProperty nomProperty() {
        return nom;
    }

    public String getEmail() {
        return email.get();
    }

    public void setEmail(String email) {
        this.email.set(email);
    }

    public StringProperty emailProperty() {
        return email;
    }

    public String getDate() {
        return date.get();
    }

    public void setDate(String date) {
        this.date.set(date);
    }

    public StringProperty dateProperty() {
        return date;
    }

    public int getNbrPersonne() {
        return nbrPersonne;
    }

    public void setNbrPersonne(int nbrPersonne) {
        this.nbrPersonne = nbrPersonne;
    }

    public IntegerProperty NbrPersonneProperty() {
        return new SimpleIntegerProperty(nbrPersonne);
    }

    public int getReservationId() {
        return reservationId.get();
    }

    public void setReservationId(int reservationId) {
        this.reservationId.set(reservationId);
    }

    public IntegerProperty reservationIdProperty() {
        return reservationId;
    }
}
