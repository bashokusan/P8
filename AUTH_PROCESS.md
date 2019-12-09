Guide d'implémentation de l'authentification
==========

Description des fichiers concernés par le processus d'authentification :

### Entité User (namespace App\Entity)

Un utilisateur de l'application est représenté par l'entité User qui implémente l'interface UserInterface (namespace Symfony\Component\Security\Core\User).
```php
/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    //...
}
```
### Security.yaml (config/packages/security.yaml)

Les utilisateurs sont récupérer grâce à l'entité User. La propriéé username est utilisé pour l'authentification.
```yaml
providers:
    in_database:
        entity:
            class: App\Entity\User
            property: username
```
L'encodeur utilisé pour le chiffrement est celui par défaut.
```yaml
encoders:
    App\Entity\User:
        algorithm: auto
```
Le firewall définit le processus d'authentification dans l'application.
Le provider est celui définit ci-dessus. L'authentification est réalisé via le formulaire dont la route est spécifié dans le paramètre login_path.
```yaml
firewalls:
    dev:
        pattern: ^/(_(profiler|wdt)|css|images|js)/
        security: false

    main:
        anonymous: true
        provider: in_database
        form_login:
            login_path: login
            check_path: login
            always_use_default_target_path:  true
            default_target_path:  /
        logout: ~
```
Un utilisateur ayant pour rôle ROLE_ADMIN aura aussi les droits définit par le rôle ROLE_USER.
```yaml
role_hierarchy:
    ROLE_ADMIN: ROLE_USER
```
Les restrictions d'accès sont définit dans le paramètre access_control.
- l'url /login est accessible sans authentification.
- le reste du site est accessible aux utilisateurs ayant au moins le rôle ROLE_USER.

```yaml
access_control:
    - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: ^/, roles: ROLE_USER }
```
Outre le paramètre access_control, il est aussi possible de définir des restrictions directement dans un controleur grâce à l'annotation IsGranted comme c'est le cas dans le UserController (namespace App\Controller).
```php
/**
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    //...
}
```
### TaskVoter (namespace App\Security\Voter)

Des restrictions plus fines sont réalisés grâve au Voter. Il permet de définir des droits d'accès en fonction de paramètres définit par le développeur.

Actuellement, l'action de suppression d'une tâche (TASK_DELETE) est restreinte de la façon suivante :
- l'auteur de la tâche n'est pas définit (null), alors seul un utilisateur ayant le rôle ROLE_ADMIN peut supprimer la tâche.
```php
if(null == $subject->getAuthor()){
    return in_array('ROLE_ADMIN', $user->getRoles());
}
```
- l'auteur de la tâche est l'utilisateur connecté.
```php
return $subject->getAuthor() == $user;
```
Ces restrictions sont intégrées à la méthode deleteTaskAction du TaskController (namespace App\Controller).
```php
public function deleteTaskAction(Task $task)
{
    $this->denyAccessUnlessGranted('TASK_DELETE', $task);
    
    //...
}
```
### SecurityController (namespace App\Controller)
Le controller SecurityController va définir la logique du processsus d'authentification et l'affichage des erreurs dans la vue contenant le formulaire de connexion.
```php
public function loginAction(AuthenticationUtils $authenticationUtils)
{
    //...
}
```
### Formulaire de connexion

Le formulaire de connection se trouve dans templates/security/login.html.twig. Il appel la méthode login du SecurityController décrit ci-dessus.