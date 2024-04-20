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

public class ServiceRestaurant implements Irestaurant<Restaurant> {
    public int getRestaurantIdByName(String name) throws SQLException {
        int restaurantId = -1; // Default value if not found
        String query = "SELECT idR FROM restaurant WHERE nom = ?";

        try (PreparedStatement preparedStatement = connection.prepareStatement(query)) {
            preparedStatement.setString(1, name);
            try (ResultSet resultSet = preparedStatement.executeQuery()) {
                if (resultSet.next()) {
                    restaurantId = resultSet.getInt("id");
                }
            }
        }

        return restaurantId;
    }

    // Method to insert a Plat into the database
    public void insertPlat(Plat plat) throws SQLException {
        Connection connection = null;
        PreparedStatement preparedStatement = null;

        try {
            // Establish connection
            connection = MyDB.getInstance().getConnection();

            // Prepare the SQL statement
            String sql = "INSERT INTO plat (id, nom, image, prix) VALUES (?, ?, ?, ?)";
            preparedStatement = connection.prepareStatement(sql);
            preparedStatement.setInt(1, plat.getId());
            preparedStatement.setString(2, plat.getNom());
            preparedStatement.setString(3, plat.getImage());
            preparedStatement.setFloat(4, plat.getPrix());

            // Execute the update
            preparedStatement.executeUpdate();
        } finally {
            // Close resources
            if (preparedStatement != null) {
                preparedStatement.close();
            }
            if (connection != null) {
                connection.close();
            }
        }
    }



    private Connection connection;

    public ServiceRestaurant() {
        connection = MyDB.getInstance().getConnection();
    }

    @Override
    public void ajouter(Restaurant restaurant) throws SQLException {
        String req = "INSERT INTO restaurant (nom, localisation, image, description) VALUES (?,?, ?, ?)";
        try (PreparedStatement pre = connection.prepareStatement(req)) {
            pre.setString(1, restaurant.getNom());
            pre.setString(2, restaurant.getLocalisataion());
            pre.setString(3,restaurant.getImage());
            pre.setString(4, restaurant.getDescription());

            pre.executeUpdate();
            System.out.println("Restaurant added successfully!");
        }


    }

    @Override
    public void modifier(Restaurant restaurant) throws SQLException {
        String req = "UPDATE restaurant SET nom=?, localisation=?, image=?, description=? WHERE idR=?";
        try (PreparedStatement pre = connection.prepareStatement(req)) {
            pre.setString(1, restaurant.getNom());
            pre.setString(2, restaurant.getLocalisataion());
            pre.setString(3, restaurant.getImage());
            pre.setString(4, restaurant.getDescription());
            pre.setInt(5, restaurant.getIdR()); // Assuming id is the primary key

            int affectedRows = pre.executeUpdate();
            if (affectedRows == 1) {
                System.out.println("Restaurant updated successfully!");
            } else {
                System.out.println("Failed to update restaurant. No rows affected.");
            }
        }
    }


    @Override
    public void supprimer(Restaurant restaurant) throws SQLException {
        String req = "DELETE FROM restaurant WHERE idR=?";
        try (PreparedStatement pre = connection.prepareStatement(req)) {
            pre.setInt(1, restaurant.getIdR()); // Assuming id is the primary key

            int affectedRows = pre.executeUpdate();
            if (affectedRows == 1) {
                System.out.println("Restaurant deleted successfully!");
            } else {
                System.out.println("Failed to delete restaurant. No rows affected.");
            }
        }
    }


    public ObservableList<Restaurant> afficher() throws SQLException {
        String req = "SELECT * FROM restaurant";
        ObservableList<Restaurant> list = FXCollections.observableArrayList();
        PreparedStatement pre= connection.prepareStatement(req);
        ResultSet res = pre.executeQuery();

        while (res.next()) {
            Restaurant restaurant = new Restaurant();
            restaurant.setIdR(res.getInt("idR"));
            // res.getInt("IDformation"),
            // res.getInt("iduser"),
            restaurant.setNom( res.getString("nom"));
            restaurant.setLocalisataion( res.getString("localisation"));
            restaurant.setImage( res.getString("image"));
            restaurant.setDescription( res.getString("description"));
            list.add(restaurant);

        }

        return list ;
    }


    public List<Restaurant> getAllRestaurants() throws SQLException {
        String query = "SELECT * FROM restaurant";
        List<Restaurant> restaurants = new ArrayList<>();

        try (PreparedStatement preparedStatement = connection.prepareStatement(query)) {
            ResultSet resultSet = preparedStatement.executeQuery();
            while (resultSet.next()) {
                Restaurant restaurant = new Restaurant();
                restaurant.setIdR(resultSet.getInt("idR"));
                restaurant.setNom(resultSet.getString("nom"));
                restaurant.setLocalisataion(resultSet.getString("localisation"));
                restaurant.setImage(resultSet.getString("image"));
                restaurant.setDescription(resultSet.getString("description"));
                restaurants.add(restaurant);
            }
        }
        return restaurants;
    }

    public List<String> getAllRestaurantNames() throws SQLException {
        List<String> restaurantNames = new ArrayList<>();

        // Logic to retrieve restaurant names from the database
        // For example:
        // ResultSet resultSet = databaseConnection.createStatement().executeQuery("SELECT name FROM restaurants");
        // while (resultSet.next()) {
        //     restaurantNames.add(resultSet.getString("name"));
        // }

        // Dummy data for demonstration
        restaurantNames.add("Restaurant 1");
        restaurantNames.add("Restaurant 2");
        restaurantNames.add("Restaurant 3");

        return restaurantNames;
    }
}