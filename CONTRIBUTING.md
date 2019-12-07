Contribuer
==========
## Guide
1. Fork du repository
([How to](https://help.github.com/en/github/getting-started-with-github/fork-a-repo))

2. Installer le projet (voir [README.md](https://github.com/bashokusan/P8/blob/master/README.md))

3. Créer une nouvelle branch
    ```
    git branch yourbranch
    ```
4. Codez !

5. Tester l'application
    ```
    bin/phpunit --coverage-html web/test-coverage
    ```

6. Commit et push du code
    ```
    git add .
    git commit -m "mon commit"
    git push -u origin branch
    ```
7. Ouvrir une [pull request](https://help.github.com/en/github/collaborating-with-issues-and-pull-requests/about-pull-requests)

### Standards de qualité à respecter
- [PSR-1: Basic Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
- [PSR-2: Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
- [PSR-4: Autoloading Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
- [Symfony Coding Standards](https://symfony.com/doc/current/contributing/code/standards.html)