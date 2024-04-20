
package Controller;
import Entity.Restaurant;
import javafx.scene.control.TableCell;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

import java.io.File;

class ImageTableCell extends TableCell<Restaurant, String> {
    private final ImageView imageView = new ImageView();

    @Override
    protected void updateItem(String imagePath, boolean empty) {
        super.updateItem(imagePath, empty);

        if (empty || imagePath == null) {
            setGraphic(null);
        } else {
            imageView.setImage(new Image(new File(imagePath).toURI().toString()));
            setGraphic(imageView);
        }
    }
}
