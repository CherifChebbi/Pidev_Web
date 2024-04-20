package Services;

import Entity.Plat;
import javafx.collections.ObservableList;

import java.sql.SQLException;
import java.util.List;

public interface IPlat<P> {
    void ajouter(P p) throws SQLException;

    List<Plat> getAllPlats() throws SQLException;

    ObservableList<P> afficher() throws SQLException;
    void modifier(P p) throws SQLException;
    void supprimer(int id) throws SQLException;

}
