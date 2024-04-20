package Services;

import java.sql.SQLException;
import java.util.List;

public interface Irestaurant<R> {
    void ajouter(R r) throws SQLException;
    void modifier (R r) throws SQLException;
    void supprimer (R r) throws SQLException;
    List<R> afficher() throws SQLException;


}
