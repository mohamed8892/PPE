
package PPE;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Statement;



public class BddAccess {

	// Attributs
	private Connection cnx;
	private Statement stmt;
	private ResultSet rs;
	private ResultSetMetaData resMeta;
	private int nbCols;
	
	private PPE refPPE;
	// Methodes
	
	// Recherche et chargement du driver en memoire
	public void chargerDriver() {
		
		try {
			Class.forName("com.mysql.jdbc.Driver");
			System.out.println("Driver trouve!");
			
		} catch (ClassNotFoundException e) {
			
			System.out.println("Driver NON trouve!");
		}
	}
	
	// Connexion a la BDD
	public void bddConnect(String bddName) {
		try {
			cnx = DriverManager.getConnection("jdbc:mysql://localhost/"+bddName, "root", "");
			System.out.println("BDD trouvee!");
		} catch (SQLException e) {
			
			System.out.println("BDD NON trouvee!");
		}
	}
	
	// Creation du conteneur de requete
	public void creerRequete() {
		try {
			stmt = cnx.createStatement();
		} catch (SQLException e) {
			
			System.out.println("Probleme creation requete !!");
		}
	}
	
	// Envoi de la requete Select
	public void envoiRequeteSel(String req) {
		try {
			rs = stmt.executeQuery(req);
		} catch (SQLException e) {
			
			System.out.println("Probleme envoi requete SELECT !!");
		}
	}
	
	// Envoi de la requete update
	public void envoiRequeteUpdate(String req) {
		try {
			
			
			stmt.executeUpdate(req);
			
		} catch (SQLException e) {
			
			System.out.println(e.getMessage());
				
			System.out.println("Probleme envoi requete Update !!");
		}
	}
	
	// Recuperation et traitement de la reponse
	public void rechercheResultats() {
		
		//Recuperer de rs les meta data
		//la metadata va chercher le type de structuration de la BDD
		try {
			resMeta = rs.getMetaData();
		} catch (SQLException e1) {
			
			e1.printStackTrace();
		}
		//recuperer le nombre de colonne de la reponse
		
		try {
			nbCols = resMeta.getColumnCount();
		} catch (SQLException e) {
			
			e.printStackTrace();
		}	
		
		//affichage des noms des colonnes
		for(int i = 1; i<= nbCols; i++) {
			try {
				System.out.print(resMeta.getColumnName(i) + " |"+" " );
			} catch (SQLException e) {
				
				e.printStackTrace();
			}
		}
		System.out.println();
		System.out.println("--------------------");
		
		//traitement de la requete
		try {
			while(rs.next()) {
				for(int i = 1; i <= nbCols; i++) {
					System.out.print(rs.getObject(i).toString() + " ");
				}
				System.out.println();
			}
		} catch (SQLException e) {
			
			e.printStackTrace();
		}
	}

	// ----------------------------------------------
	
	// Recuperation et traitement de la reponse et affichage
	// dans la zone de texte de l'IHM
		public void rechercheResultatsVersIhm() {
			
			//Recuperer de rs les meta data
			//la metadata va chercher le type de structuration de la BDD
			try {
				resMeta = rs.getMetaData();
			} catch (SQLException e1) {
				
				e1.printStackTrace();
			}
			//recuperer le nombre de colonne de la reponse
			
			try {
				nbCols = resMeta.getColumnCount();
			} catch (SQLException e) {
				
				e.printStackTrace();
			}	
			
			//affichage des noms des colonnes
			for(int i = 1; i<= nbCols; i++) {
				try {
					//System.out.print(resMeta.getColumnName(i) + " |"+" " );
					refPPE.afficheZoneResultats(resMeta.getColumnName(i) + " |"+" " );
				} catch (SQLException e) {
					
					e.printStackTrace();
				}
			}
			//System.out.println();
			refPPE.afficheZoneResultats("\n");
			//System.out.println("--------------------");
			refPPE.afficheZoneResultats("--------------------");
			refPPE.afficheZoneResultats("\n");
			
			//traitement de la requete
			try {
				while(rs.next()) {
					for(int i = 1; i <= nbCols; i++) {
						//System.out.print(rs.getObject(i).toString() + " ");
						refPPE.afficheZoneResultats(rs.getObject(i).toString() + " ");
					}
					//System.out.println();
					refPPE.afficheZoneResultats("\n");
				}
			} catch (SQLException e) {
				
				e.printStackTrace();
			}
		}
	
	// ----------------------------------------------
	public void setRefPPE(PPE refPPE) {
		this.refPPE = refPPE;
	}
	
}
