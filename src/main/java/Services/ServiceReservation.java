package Services;

import Entity.Reservation;
import Entity.Restaurant;
import Util.MyDB;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class ServiceReservation implements Ireservation<Reservation> {

    private final Connection connection;

    public ServiceReservation() {
        this.connection = MyDB.getInstance().getConnection();
    }

    @Override
    public void ajouter(Reservation reservation) throws SQLException {
        String req = "INSERT INTO reservation (idR, nom, email, date, nbr_personne) VALUES (?, ?, ?, ?, ?)";
        try (PreparedStatement pre = connection.prepareStatement(req)) {
            pre.setInt(1, reservation.getRestaurantId());
            pre.setString(2, reservation.getNom());
            pre.setString(3, reservation.getEmail());
            pre.setString(4, reservation.getDate());
            pre.setInt(5, reservation.getNbrPersonne());
            pre.executeUpdate();
            System.out.println("Reservation added successfully!");
        }
    }

    @Override
    public void modifier(Reservation reservation) throws SQLException {
        String query = "UPDATE reservation SET nom=?, email=?, date=?, nbr_personne=? WHERE id=?";
        try (PreparedStatement preparedStatement = connection.prepareStatement(query)) {
            preparedStatement.setString(1, reservation.getNom());
            preparedStatement.setString(2, reservation.getEmail());
            preparedStatement.setString(3, reservation.getDate());
            preparedStatement.setInt(4, reservation.getNbrPersonne());
            preparedStatement.setInt(5, reservation.getReservationId());
            preparedStatement.executeUpdate();
            System.out.println("Reservation modified successfully!");
        }
    }

    @Override
    public void supprimer(Reservation reservation) throws SQLException {
        String query = "DELETE FROM reservation WHERE id=?";
        try (PreparedStatement preparedStatement = connection.prepareStatement(query)) {
            preparedStatement.setInt(1, reservation.getReservationId());
            preparedStatement.executeUpdate();
            System.out.println("Reservation deleted successfully!");
        }
    }

    @Override
    public List<Reservation> afficher() throws SQLException {
        List<Reservation> reservations = new ArrayList<>();
        String query = "SELECT reservation.*, restaurant.nom AS restaurant_nom " +
                "FROM reservation " +
                "INNER JOIN restaurant ON reservation.idR = restaurant.idR";

        try (PreparedStatement preparedStatement = connection.prepareStatement(query);
             ResultSet resultSet = preparedStatement.executeQuery()) {
            while (resultSet.next()) {
                int id = resultSet.getInt("id");
                int restaurantId = resultSet.getInt("idR");
                String nom = resultSet.getString("nom");
                String email = resultSet.getString("email");
                String date = resultSet.getString("date");
                int nbrPersonne = resultSet.getInt("nbr_personne");
                String restaurantName = resultSet.getString("restaurant_nom");

                // Create a new Reservation object with the fetched data
                Reservation reservation = new Reservation(id, restaurantId, nom, email, date, nbrPersonne);
                Restaurant restaurant = new Restaurant();
                restaurant.setNom(restaurantName);
                reservation.setRestaurant(restaurant);

                reservations.add(reservation);
            }
        }

        return reservations;
    }
}
