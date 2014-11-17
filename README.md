ESGI XML PROJECT
================

Installation :
-------------

Pour que le webservice reste fonction, il nécéssite le mod_rewrite d'apache d'activé ainsi qu'un nom de domaine dédier, ci-dessous un exemple de vhost : 

	<VirtualHost *:80>
    	ServerName rest.esgi-xml.local
    	DocumentRoot PROJECT_PATH/mvc/public

    	<Directory PROJECT_PATH/mvc/public>
    	    DirectoryIndex index.php
    	    AllowOverride All
    	    Order allow,deny
    	    Allow from all
    	</Directory>
   	</VirtualHost>

Si vous souhaitez modifier le nom de domaine, veillez à le renseigner dans le fichier de configuration :

	PROJECT_PATH/config/application.ini

Il y sera aussi renseigné l'url d'accès au serveur soap présent dans le dossier

	PROJECT_PATH/soap/server.php


Enfin pour installer la base de donnée peuplée, veuiller décompresser l'archive database.tar.gz

	tar -xzvf PROJECT_PATH/database.tar.gz

Et l'importer en ligne de commande : 

	mysqldump -u root -p -h localhost places < PROJECT_PATH/database/places.sql


Partie SOAP
-----

Le service soap nous sert à gérer les commentaires pour une place donnée. Il dispose de deux méthodes. Il est contactable à l'adresse "http://localhost/esgi/xml/soap/server.php"

Vous pouvez aussi consulter le wsdl à cette adresse : http://localhost/esgi/xml/soap/server.php?wsdl


insert 
------

La méthode insert permet d'ajouter un nouveau commentaire, elle attend **obligatoirement** 4 paramètres :

* string author
* string content
* integer rate
* integer place_id

et renvoie un **ArrayOfComment** corréspondant au nouvel enregistrement.

Signature WSDL : 

	<message name="insertRequest">
 		<part name="author" type="xsd:string"/>
 		<part name="content" type="xsd:string"/>
		<part name="rate" type="xsd:int"/>
		<part name="place_id" type="xsd:int"/>
	</message>
	<message name="insertResponse">
		<part name="comment" type="ns:ArrayOfComment"/>
	</message>

Find
----
La méthode find permet de récupérer l'ensembles des commentaires liés à une place, elle attend **obligatoirement** 1 paramètre 

* integer place_id

et renvoie une **collection de ArrayOfComment**.

Signature WSDL :

	<message name="findRequest">
		<part name="place_id" type="xsd:int"/>
	</message>



Partie REST
-----------

La partie Reste est en charge :

* continents
* pays
* villes
* places

Différentes routes disponibles : 

	/continents
	/countries
	/cities
	/places
	/place/id/<:id>


La routes **continents**
------------------------

**GET**

Listes des continents

**POST**

*Methode non supportée*

**PUT**

*Methode non supportée*

**DELETE**

*Methode non supportée*


La routes **countries**
------------------------

**GET**

Listes des pays, peut être filtré avec le paramètre GET 'continent_code' avec un code valide.

**POST**

*Methode non supportée*

**PUT**

*Methode non supportée*

**DELETE**

*Methode non supportée*


La routes **city**
------------------

**GET**

Reçois en paramètre : 

* coutryid ***parametre obligatoire***
* q 
* limit (par défaut fixé à 1000)

Retourne une listes de villes matchant avec le paramètre "q" si renseigné.

**POST**

*Methode non supportée*

**PUT**

*Methode non supportée*

**DELETE**

*Methode non supportée*


La routes **places**
------------------------

**GET**

Listes des places, peut être filtré avec le paramètre GET 'townid' avec un id ville valide ou une 404 ci rien n'est trouvé.

**POST**

*Methode non supportée*

**PUT**

*Methode non supportée*

**DELETE**

*Methode non supportée*

La routes place
---------------

**GET**

retourne une place, si celle ci n'existe pas, retourne une erreur HTTP 409.

**POST**

reçois en paramètre :

* name
* address
* description
* longitude
* latitude
* town_id

Si l'id est menquant dans l'url, ou un des paramètres GET et menquant retourne une 409.
Si la ville n'existe pas, retourne une 404

**PUT**

reçois en paramètre :

* name
* address
* description
* longitude
* latitude
* town_id

Si l'id est menquant dans l'url retourne une 409, chaques paramètres GET sont optionnel.

**DELETE**

*Methode non supportée*