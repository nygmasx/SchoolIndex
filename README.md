# MathIndex

Projet de fin d'année BTS2 - Symfony

## Identifiants selon les rôles

Pour accéder à différentes parties de l'application, voici les identifiants par défaut&nbsp;:

| Email                     | Mot de passe | Rôles                                    |
|---------------------------|--------------|------------------------------------------|
| jean@gmail.com       | xxx          | ROLE_STUDENT                             |
| sophie@example.com   | xxx          | ROLE_TEACHER                             |                        |
| admin@example.com    | xxx          | ROLE_ADMIN                               |

### Initialisation du projet

Voici la procédure à suivre :

À l'intérieur d'un terminale de commande, effectuez les étapes suivantes&nbsp;:
```bash
composer install
  
symfony console doctrine:database:create

symfony console d:s:u --force

symfony console d:f:l
```

## 🌐 Accès au projet

[Accès au site](http://127.0.0.1:8000)&nbsp;:
```bash
127.0.0.1:8000
```
[Accès à mailcatcher](http://127.0.0.1:1080) pour vérifier le mot de passe oublié&nbsp;:
```bash
127.0.0.1:1080
```

## Auteurs

- [@Yacine Guerda](https://github.com/BouderBaden)

- [@Imrane Sallak](https://github.com/nygmasx)

- [@Rafael Perucho](https://github.com/PeruchoRafael)
