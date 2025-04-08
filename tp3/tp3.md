# TP3 - Déploiement d'une Application Web avec Ansible
## Github : https://github.com/MohamedAmineKHLIF/devops-tps
### **Objectif :** Utiliser Ansible pour déployer une application web complète, incluant un serveur web, une base de données, et l'application elle-même.

## Section 1 : Préparation de l'Inventaire
J’ai commencé par créer un fichier d’inventaire : tp2/inventory/hosts, dans lequel j’ai défini trois groupes d’hôtes :
- [webservers] : contient le serveur web1, qui héberge Apache.
- [dbservers] : contient le serveur db1, pour MySQL.
- [appservers] : contient le serveur app1, qui héberge le script PHP de l’application.
- [all:vars] : regroupe les variables communes à tous les hôtes (comme ansible_user, etc.).
Ensuite, j’ai créé un fichier de configuration tp2/ansible.cfg pour définir les paramètres de base d’Ansible (comme le chemin vers l’inventaire, etc.).
## Section 2 : Création des Rôles
#### Rôle Apache (roles/apache): 
Ce rôle est appliqué au groupe webservers. Il effectue les actions suivantes :
- Installation du serveur Apache2.
- Configuration du service et démarrage automatique.
- Installation de PHP.
- Copie du fichier index.php depuis le rôle webapp vers le dossier /var/www/html.
#### Rôle MySQL (roles/mysql)
Ce rôle est destiné au groupe dbservers. Il comprend :
- Installation de MySQL Server.
- Modification du fichier de configuration pour que MySQL écoute sur toutes les interfaces (bind-address = 0.0.0.0).
- Démarrage et activation du service MySQL.
- Installation du module python3-pymysql pour permettre la gestion via Ansible.
- Modification du plugin d’authentification de l’utilisateur root pour permettre une connexion avec mot de passe (mysql_native_password).
- Création de la base de données webdb, de la table users, et insertion de données d’exemple.
- Création d’un utilisateur distant root@10.75.17.56 (web1) avec tous les privilèges sur la base webdb.
#### Rôle Webapp (roles/webapp)
Ce rôle contient le fichier index.php situé dans files/. Ce script PHP :
- Se connecte à la base webdb sur le serveur MySQL.
- Récupère les noms des utilisateurs.
- Affiche dynamiquement la liste sur la page web.
## Section 3 : Déploiement avec le Playbook

J'ai créé un playbook **playbooks/deploy.yml** qui inclut le rôle apache pour le groupe webservers, le rôle mysql pour dbservers et le rôle webapp pour appservers.
Ensuite, j'ai exécuté le playbook avec la commande suivante **ansible-playbook playbooks/deploy.yml**.
## Résultat final
- L’application web est accessible à l’adresse : http://10.75.17.56/index.php.
- Le fichier index.php interroge la base webdb sur le serveur db1 et affiche dynamiquement les utilisateurs enregistrés.
- L’ensemble de l’infrastructure est entièrement déployé de manière automatique grâce à Ansible.
