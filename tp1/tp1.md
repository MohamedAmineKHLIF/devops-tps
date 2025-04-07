# TP1 - Initiation à Ansible

## Section 1 : Introduction à Ansible  
### Exercice 1 : Installation d'Ansible
Comme je suis en train d'utiliser une machine Windows, je vais installer Ansible sur un environnement **WSL (Windows Subsystem for Linux)**.
Voici les étapes que j'ai fait pour ce faire :
1. Activation de WSL sur ma machine. Dans un cmd en mode administrateur : **wsl --install** 
2. Installation de Ubuntu 24.04.1 LTS via WSL : **wsl --install -d Ubuntu-22.04**
3. Une fois installé, j'ai mis à jour les paquets de ubuntu : **sudo apt update**
4. Installation des dépendances nécessaires : **sudo apt install software-properties-common**
5. Ajout du PPA d'Ansible : **sudo add-apt-repository --yes --update ppa:ansible/ansible**
==> Le PPA (Personal Package Archive) officiel d'Ansible permet l'installation de la dernière version stable d'Ansible.
6. Installation de Ansible : **sudo apt install ansible**
7. Vérification de la bonne installation : **ansible --version**
==> Affiche la version installée. Dans mon cas : ansible [core 2.17.10].
### Exercice 2 : Configuration de base
D'abord, j'ai créé 2 VMs avec virtual box et je les ai configué en mode bridge afin d'avoir des @ips appartenant au réseau de ma machine hôte.
Sur chaque VM, j'ai installé openssh-server pour pouvoir communiquer avec Ansible.
Ensuite, j'ai généré une clé avec **ssh-keygen -t ed25519 -f ~/.ssh/ansible_key**, que j'ai copié sur chaque VM avec **ssh-copy-id -i ~/.ssh/ansible_key.pub ansibleX@10.75.17.XX**. 
Pour l'inventaire, j'ai crée le fichier **tp1/inventory/hosts**.
Il contient les 2 groupes :
- [webservers] : Contient les 2 serveurs web
- [all:vars] : Variables communes à tous les hôtes
Par la suite, j'ai crée le fichier **tp1/ansible.cfg** qui configure le comportement d'Ansible sur les hôtes.
Pour vérifier qu'il pointe vers mon fichier d'inventaire, j'ai lancé la commande **ansible-inventory --list**. J'ai bien reçu la liste des machines que j'ai listé dans **hosts**.
## Section 2 : Commandes de base d'Ansible 
### Exercice 1 : Ping des hôtes :
J'ai essayé la commande **ansible all -m ping**. Ce qui se passe c'est :
**Configuration** : Ansible lit la configuration et récupére le chemin vers le fichier des inventaires. ==> **Inventaire** : Cherche les hôtes dans l'inventaire. ==> **Connexion** : Se connecte via SSH avec la clé spécifiée. ==> **Execution** : execute les pings sur les machines.
Le résultat finale était : 
web1 | SUCCESS => {"ping": "pong"}
web2 | SUCCESS => {"ping": "pong"}
### Exercice 2 : Exécution de commandes simples :
J'ai essayé la commande **ansible all -a "uname -a"**.
Le résultat obtenu montre qu'on a pu executer une commande simple et ramener les resultats de chaque machine.
## Section 3 : Playbooks de base
### Exercice 1 : Création d'un playbook simple :
J'ai commencé par créer le playbook d'installation de curl.
Ansible lit le playbook séquentiellement : il commence par collecter les infos systèmes (Facts), puis exécute chaque tâche en vérifiant d'abord l'état actuel avant d'agir.
Pour ce playbook, il utilise le module apt pour : 1) mettre à jour le cache si nécessaire, 2) installer curl seulement s'il est absent.
Les opérations sont idempotentes et parallélisées sur les hôtes, avec des résultats retournés en JSON.
### Exercice 2 : 
J'ai lancé la commande **ansible-playbook playbooks/curl.yml**. Le résultat obtenu montre que web1 et web2 ont subi des changements en installant curl.
Pour vérifier, j'ai lancé la commande ad-hoc (comment précédemment) : **ansible webservers -a "curl --version"**.
Elle montre bel et bien que curl est installé sur les 2 machines avec la version 8.5.0

## Section 4 : Variables et Templates :  
### Exercice 1 : Utilisation de variables :
J'ai commencé par créer un fichier **group_vars/webservers.yml**, ou j'ai mis la liste des paquets que je souhaite installer : curl, htop et git.
Ensuite, j'ai créé un autre playbook **playbooks/paquets.yml** et je l'ai executé avec la commande : **ansible-playbook playbooks/paquets.yml**.
### Exercice 2 : Utilisation de templates :
J'ai commencé par créer le fichier **templates/motd.j2**. Il contient le template que je vais le mettre dans le fichier /etc/motd de chaque machine.
Ensuite j'ai créé le playbook **playbooks/motd.yml**. 
Enfin, j'ai executé mon playbook avec **ansible-playbook playbooks/motd.yml**. Et pour vérifier, j'ai lancé la commande ad-hoc : **ansible webservers -a "cat /etc/motd"**.

## Section 5 : Rôles et Best Practices : 
J'ai conçu un rôle Ansible pour déployer Apache sur des serveurs distants. La structure comprend : - **roles/apache/tasks/main.yml** : Définit les actions à exécuter : **Installation d’Apache** et **Déploiement de la configuration** via **roles/apache/templates/apache.conf.j2**.
- **roles/apache/handlers/main.yml** : Réagit aux changements de configuration : il **redémarre Apache si le template est modifié**.
- **roles/apache/templates/apache.conf.j2** : génère une configuration personnalisée : par exemple ici j'ai défini le port d'écoute et le server name.
- **roles/apache/vars/main.yml** : contient les variables qu'on peut customiser. Ici c'est apache_port que j'ai mis à 80.
- **tp1/playbooks/apache.yml** : playbook principal qui déclenche le déploiement sur le groupe webservers.

Puis j'ai lancé la commande **ansible-playbook playooks/apache.yml**. Le résultat montre le bon déroulement du process sur les 2 VMs. Pour vérifier encore, j'ai lancé la commande ad-hoc **ansible webservers -a "apache2 -v"**. Le résultat montre l'installation de **Apache/2.4.58**.
