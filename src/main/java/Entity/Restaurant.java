package Entity;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

public class Restaurant {

    int idR;
    String nom,localisataion,image,description;
    ImageView imageView;

    public Restaurant(int idR, String nom, String localisataion, String image, String description) {
        this.idR = idR;
        this.nom = nom;
        this.localisataion = localisataion;
        this.image = image;
        this.description = description;
        this.imageView = new ImageView(new Image("file:" + image)); // Initialize the ImageView with the image file
        this.imageView.setFitWidth(100); // Set the width of the image (adjust as needed)
        this.imageView.setFitHeight(100);
    }

    public Restaurant(String nom, String localisataion, String image, String description) {
        this.nom = nom;
        this.localisataion = localisataion;
        this.image = image;
        this.description = description;
    }

    public Restaurant() {

    }

    public Restaurant(String restaurantName) {
    }

    public int getIdR() {
        return idR;
    }

    public void setIdR(int idR) {
        this.idR = idR;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getLocalisataion() {
        return localisataion;
    }

    public void setLocalisataion(String localisataion) {
        this.localisataion = localisataion;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    @Override
    public String toString() {
        return "Restaurant{" +
                "idR=" + idR +
                ", nom='" + nom + '\'' +
                ", localisataion='" + localisataion + '\'' +
                ", image='" + image + '\'' +
                ", description='" + description + '\'' +
                '}';
    }


}
