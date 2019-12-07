ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a8a53bc40db64af89260c583062c9b3d)](https://www.codacy.com/manual/bashokusan/P7?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=bashokusan/P7&amp;utm_campaign=Badge_Grade)

## Installation

* Cette application a été développée avec le framework [Symfony](https://symfony.com/)
1. Cloner le projet pour installer son contenu
```
git clone https://github.com/bashokusan/P8.git
```
2. Utiliser Composer pour installer les dépendances dans le dossier vendor
```
cd P7/
composer install
```
3. Créer la database
```
bin/console doctrine:database:create
```
4. Importer les tables dans la database
```
bin/console doctrine:migrations:migrate
```
## Testing
Lancer les tests avec phpunit
```
bin/phpunit
```
## Contribuer
Consulter [CONTRIBUTING.md](https://github.com/bashokusan/P8/blob/master/CONTRIBUTING.md)
