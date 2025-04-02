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

