package Services;

import Entity.Plat;
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

public class ServicePlat implements IPlat<Plat> {

    private Connection connection;

    public ServicePlat() {
        connection = MyDB.getInstance().getConnection();
    }

    @Override
    public List<Plat> getAllPlats() throws SQLException {
        String query = "SELECT * FROM plat";
        List<Plat> plats = new ArrayList<>();
        try (PreparedStatement preparedStatement = connection.prepareStatement(query)) {
            ResultSet resultSet = preparedStatement.executeQuery();
            while (resultSet.next()) {
                Plat plat = new Plat();
                plat.setId(resultSet.getInt("id"));
                plat.setNom(resultSet.getString("nom"));
                plat.setImage(resultSet.getString("image"));
                plat.setPrix(resultSet.getInt("prix"));

                // Assuming the "idR" column is the foreign key referencing the Restaurant table
                // You may need to fetch the Restaurant details and set it here
                int restaurantId = resultSet.getInt("idR");
                Restaurant restaurant = fetchRestaurantById(restaurantId);
                plat.setRestaurant(restaurant);


                plats.add(plat);
            }
        }
        return plats;
    }

    // Helper method to fetch Restaurant details by ID
    private Restaurant fetchRestaurantById(int id) throws SQLException {
        String query = "SELECT * FROM restaurant WHERE idR = ?";
        try (PreparedStatement preparedStatement = connection.prepareStatement(query)) {
            preparedStatement.setInt(1, id);
            ResultSet resultSet = preparedStatement.executeQuery();
            if (resultSet.next()) {
                Restaurant restaurant = new Restaurant();
                restaurant.setIdR(resultSet.getInt("idR"));
                restaurant.setNom(resultSet.getString("nom"));
                restaurant.setLocalisataion(resultSet.getString("localisation"));
                restaurant.setImage(resultSet.getString("image"));
                restaurant.setDescription(resultSet.getString("description"));
                return restaurant;
            }
        }
        return null; // Return null if no restaurant found with the given ID
    }


    @Override
    public ObservableList<Plat> afficher() throws SQLException {
        List<Plat> plats = getAllPlats();
        return FXCollections.observableArrayList(plats);
    }

    @Override
    public void ajouter(Plat plat) throws SQLException {
        String req = "INSERT INTO plat (idR,nom, image, prix) VALUES (?,?, ?, ?)";
        try (PreparedStatement pre = connection.prepareStatement(req)) {
            pre.setInt(1,plat.getRestaurant().getIdR());
            pre.setString(2, plat.getNom());
            pre.setString(3, plat.getImage());
            pre.setFloat(4, plat.getPrix());
            pre.executeUpdate();
            System.out.println("Plat added successfully!");
        }
    }

    @Override
    public void modifier(Plat plat) throws SQLException {
        String req = "UPDATE plat SET nom=?, image=?, prix=? WHERE id=?";
        try (PreparedStatement pre = connection.prepareStatement(req)) {
            pre.setString(1, plat.getNom());
            pre.setString(2, plat.getImage());
            pre.setFloat(3, plat.getPrix());
            pre.setInt(4, plat.getId());
            pre.executeUpdate();
            System.out.println("Plat updated successfully!");
        }
    }

    @Override
    public void supprimer(int id) throws SQLException {
        String req = "DELETE FROM plat WHERE id=?";
        try (PreparedStatement pre = connection.prepareStatement(req)) {
            pre.setInt(1, id);
            pre.executeUpdate();
            System.out.println("Plat deleted successfully!");
        }
    }
}
