
## Afro - Atomic Framework REST Only

Afro est un microframework PHP concu pour concevoir de manière très legère des API REST en PHP, de la version 5.3 à 7.
Pour acceder a la page de demonstration, 

- Clonez localement de contenu du repository.
- Créez ensuite le host local : 127.0.0.1 afro.local
- Créez le vhost corresopndant :

><VirtualHost *:80>
	ServerName afro.local
	ServerAlias www.afro.local
	  DocumentRoot "**afro path**/www"
	  <Directory "**afro path**/www/">
	    AllowOverride All
	    Require local
	  </Directory>
</VirtualHost>
>

Vous pourrez alors accéder a la briève documentation de ce framework.