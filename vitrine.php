<!DOCTYPE>
<html>
	<head> 
		<title> Vitrine </title>
    </head>
    <body>

        <H1><center>Ma vitrine</center></H1>

        <div align="left">
            <a href="connexion.html"> Accueil</a>
        </div>
    <div align="center">
        <form method="POST" action="">

        <br>
        <label for="Genre">Genre</label>
        <select name="Genre" id="genre">
        <option value="Homme" > Homme</option>
        <option value="Femme" >  Femme</option>       
        </select> 
       

        <br>
        <label for="TypeVetement">TypeVetement</label>
        <select name="TypeVetement" id="TypeVetement">
        <option value="Pantalon" > Pantalon </option>
        <option value="Robe" > Robe</option>
        <option value="Veste" > Veste</option>     
        <option value="Chemise" > Chemise</option>               
        </select>       
     

        <br>
        <label for="Taille">Taille</label>
        <select name="Taille" id="Taille">
        <option value="1an-6ans" > 1an-6ans </option>
        <option value="7ans-12ans" > 7ans-12 ans</option>
        <option value="13ans-18ans" > 13-18 ans</option>     
        <option value="Adulte" > Adulte</option>               
        </select>    
     

        <br>
        <label for="Couleur">Couleur</label>
        <select name="Couleur" id="Couleur">
        <option value="Beige" > Beige </option>
        <option value="Bleu" > Bleu</option>
        <option value="Noir" > Noir</option>     
        <option value="Rouge" > Rouge</option>
        <option value="Vert" > Vert</option>                  
        </select>    
       
        <br>
        <label for="Quantite">Quantite</label>         
        <input type="text" id="Quantite" name="Quantite"><br>
        

        <input type="radio" name="BtonConsultationAchat" value="consultation" id="consultation" checked="checked" /> <label for="consultation">Consultation</label>
        <input type="radio" name="BtonConsultationAchat" value="achat" id="achat" /> <label for="achat">Achat</label><br>

        <input type="submit" name="Submit" value="Submit">

        </form>
    </div>

    </body>
</html>
<?php



// verifie si le submit est activé
if(isset($_POST['Submit'])){
    //récupération des valeurs pour mettre dans des variables
    $genre = $_POST['Genre'];
    $TypeVetement = $_POST['TypeVetement'];
    $Taille = $_POST['Taille'];
    $Couleur = $_POST['Couleur'];
    $Quantite = $_POST['Quantite'];
    $BtonConsultationAchat = $_POST['BtonConsultationAchat'];

    //connexion BDD
    $pdo=new PDO("mysql:host=127.0.0.1;dbname=achatenligne","root",""); 
            //echo "<br>Connexion BDD OK<br>";
            //echo "<br>"; 

            //si bouton == consultation
            if($BtonConsultationAchat == 'consultation'){
                
                    //requete pour afficher la table stock
                    $req = $pdo->prepare("SELECT * FROM stock");
                    $req->execute();
                    //tableau pour présenter les noms des champs
                   
                    echo'<div align="center"><table><tr><td>Genre</td><td>TypeVetement</td><td>Taille</td><td>Couleur</td><td>Prix</td></tr></div>';

                    //on recupere chaque valeur de la requete et on le place dans la variable $data en faisant une boucle
                    while ($data = $req->fetch()){
                        //sous forme de ligne et colonne
                        echo'<tr><td>'.$data['Genre'].'</td><td>'.$data['TypeVet'].'</td><td>'.$data['Taille'].'</td><td>'.$data['Couleur'].'</td><td>'.$data['PrixUnite'].'</td></tr>';
                    }
                    echo'</table>';
                    
                    
                

            }
            
            //sinon si le bouton achat est choisi et $quantité numerique et  >0 
            else if($BtonConsultationAchat == 'achat' and  is_numeric($Quantite) and $Quantite > 0){  
                        //requete pour chercher l'article selectionné    
                        $req=$pdo->prepare("SELECT  QuantiteDispo,TypeVet  FROM stock WHERE TypeVet='$TypeVetement' and Taille = '$Taille' and Couleur='$Couleur' and Genre='$genre'");
                        $req->execute();// execute la requete
                      
            
                        // Si la requete a un nombre de tour égal à 0 alors la personne n'existe pas et est ensuite envoyé vers la page *enregistrer.html*
                        if($req->rowCount()==0){ 
                            //si l'article demander existe pas
                           
                            echo "<center> l'article demander n'existe pas </center>";
                            header("Refresh: 3; URL=vitrine.php");
                        }
                        else{  
                               
                            //recupere les valeur de la colonne de la variable requete(ici qte) et la stock dans $result
                            //mais vu qu'on a preciser la condition de la requete typeVet alors c'est juste la valeur de 
                            $result = $req->fetchColumn(0);
                            //verification si la quantité est inferieur ou egal a 0
                            if($result <= 0){
                                
                                
                                echo "<center>cette article n'a plus de stock</center>";
                                header("Refresh: 3; URL=vitrine.php");
                            }
                            else{
                            //soustraction de la  valeur et stock dans une nouvelle variable
                            $Quantite = $result-$Quantite;
                            //requete update qui permet de mettre la nouvelle quantité soustraite dans le typeVetement selectionner
                            $req1=$pdo->prepare("UPDATE stock SET QuantiteDispo='$Quantite' WHERE TypeVet='$TypeVetement' and Taille = '$Taille' and Couleur='$Couleur' and Genre='$genre'");
                            $req1->execute();

                            
                            echo"<center>Demande validée</center>";
                            header("Refresh: 3; URL=vitrine.php");
                            
                            }
                        }
            }
            //sinon si $Quantite = 0 ou $Quantite < 0
            else if ($Quantite== 0 or $Quantite < 0){

                
                echo"<center>vous devez mettre une quantité supérieur à 0</center>";
                //refresh la page apres 3sec
                header("Refresh: 3; URL=vitrine.php");
            }
            // si quantité pas numerique ou vide
            else if(!is_numeric($Quantite)){

                
                echo"<center>vous devez mettre un nombre</center>";
                //refresh page apres 3sec
                header("Refresh: 3; URL=vitrine.php");
            }
                        
}
else{
    
}
        
        






?>