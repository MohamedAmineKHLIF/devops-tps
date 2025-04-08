# TP2 - Gestion de Serveurs Web avec Ansible
## Github : https://github.com/MohamedAmineKHLIF/devops-tps
### **Objectif :** Utiliser Ansible pour déployer et configurer un serveur web Apache sur plusieurs hôtes.

## Section 1 : Préparation de l'Inventaire
### Exercice 1 : Création de l'inventaire
Pour l'inventaire, j'ai crée le fichier **tp2/inventory/hosts**.
Il contient les 2 groupes :
- [webservers] : Les 2 serveurs apache.
- [all:vars] : Variables communes à tous les hôtes
Par la suite, j'ai crée le fichier **tp2/ansible.cfg** qui configure le comportement d'Ansible sur les hôtes.
### Exercice 2 : Vérification de l'inventaire
Pour vérifier qu'il pointe vers mon fichier d'inventaire, j'ai lancé la commande **ansible-inventory --list**. J'ai bien reçu la liste des machines que j'ai listé dans **hosts**.

## Section 2 : Création d'un Rôle pour Apache
### Exercice 1 : Structure du rôle
J'ai créé un rôle nommé apache avec la structure suivante.

    roles/

    └── apache/

    ├── tasks/
    
    │   └── main.yml
    
    ├── handlers/
    
    │   └── main.yml
    
    ├── templates/
    
    │   └── apache2.conf.j2
    
    ├── vars/
    
        └── main.yml
### Exercice 2 : Tâches pour installer Apache
Dans **roles/apache/tasks/main.yml**, j'ai ajouté les tâches pour installer Apache, le configurer et une dernière tâche pour démarrer le service sur les 2 hôtes.
Dans **roles/apache/handlers/main.yml**, j'ai ajouté un handler pour redémarrer Apache.
### Exercice 3 : Template pour la configuration
Dans **roles/apache/templates/apache2.conf.j2**, j'ai créé un template pour le fichier de configuration d'Apache. Pour ce faire, j'ai commencé par essayer d'installer et démarrer Apache sur un des hôtes. Par la suite, j'ai récupéré le fichier de configuration que je l'ai mis dans ma template.
Eventuellement, il est possible de mieux optimiser ce fichier de configuration et de l'adapter à mes besoins en gardant que ce qui j'en ai besoin. 
## Section 3 : Déploiement avec un Playbook
### Exercice 1 : Création du playbook
J'ai créé un playbook playbooks/apache.yml qui inclut le rôle apache pour le groupe webservers.
### Exercice 2 : Exécution du playbook
Ensuite, j'ai exécuté le playbook avec la commande suivante **ansible-playbook playbooks/apache.yml**. L'installation, la configuration et le démarrage d'Apache s'est bien déroulé.
Pour vérifier, j'ai lancé la commande ad-hoc **ansible webservers -a "systemctl status apache2"**. Le résultat montre que Apache est actif sur les 2 hôtes.
## Section 4 : Gestion des Variables et des Secrets
### Exercice 1 : Variables d'inventaire
Pour personnaliser la configuration d'Apache, j'ai rajouté des variables spécifiques à l'inventaire dans le fichier inventory/hosts. Par exemple, j'ai défini le port sur 80 pour web1 et 8080 pour web2.
### Exercice 2 : Fichiers de variables sécurisés
J'ai créé un fichier de variables sécurisées vars/secrets.yml pour gérer des informations sensibles, comme le mot de passe de l'administrateur Apache.
Ce fichier sensible devrait être sécurisé (via **Ansible Vault**). J'ai lancé donc la commande **ansible-vault encrypt vars/secrets.yml** qui demande un mot de passe avec lequel se fait le chiffrement et le déchiffrement de ce fichier.
J'ai rajouté le chemin vers ce fichier dans mon playbook et après j'ai lancé cette commande **ansible-playbook playbooks/apache.yml --ask-vault-pass**. L'option **--ask-vault-pass** permet à Ansible de demander le mot de passe du vault afin de pouvoir lire le contenu du fichier chiffré.
