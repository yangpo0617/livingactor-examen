### Proposez une approche pour gérer les autorisations et sécuriser les endpoints.

On peut utiliser le composant `Security` de Symfony pour gérer les autorisations et sécuriser les endpoints.

https://api-platform.com/docs/symfony/security/


### Identifiez précisément pourquoi ce problème se produit et proposez une solution en utilisant des fonctionnalités

1. Stocker les données en cache, mettre à jour le cache pour chaque modification des données.
2. Utiliser des index sur les colonnes utilisées dans les requêtes.
3. Utiliser "Eager loading" 