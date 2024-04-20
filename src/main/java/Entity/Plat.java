package Entity;

public class Plat {
    private int id;
    private String nom;
    private String image;
    private float prix;
    private Restaurant restaurant;


    public Plat(int id, String nom, String image, float prix, Restaurant restaurant) {
        this.id = id;
        this.nom = nom;
        this.image = image;
        this.prix = prix;
        this.restaurant = restaurant;
    }

    public Plat() {
    }

    public Plat(String nom, String image, float prix, Restaurant restaurant) {
        this.nom = nom;
        this.image = image;
        this.prix = prix;
        this.restaurant = restaurant;
    }


    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public float getPrix() {
        return prix;
    }

    public void setPrix(float prix) {
        this.prix = prix;
    }

    public Restaurant getRestaurant() {
        return restaurant;
    }

    public void setRestaurant(Restaurant restaurant) {
        this.restaurant = restaurant;
    }

    @Override
    public String toString() {
        return "Plat{" +
                "id=" + id +
                ", nom='" + nom + '\'' +
                ", image='" + image + '\'' +
                ", prix=" + prix +
                ", restaurant=" + restaurant +
                '}';
    }

}
