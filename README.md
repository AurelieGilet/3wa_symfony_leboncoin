# 3wa_symfony_leboncoin

Training project with Symfony, website type "Leboncoin".

## Initialisation Projet
Créer un repository sur Github/Gitlab

Créer un projet Symfony 6.2 et pousser celui-ci sur github. Vous devrez utilisez **.env.local** pour vos variables d'environnement.

## Création des entités

Diagramme épinglé

<img src="https://user-images.githubusercontent.com/75724762/230405068-ac0b4384-7ef4-4544-bfa1-45f4b479f631.png" width=500)>

## Feature

Liste des features à créer : 

- ### Authentification
  - [x] Créer un register ( par défaut mettre un "ROLE_USER") au user
    - [x] Au register l'user à un montant de 100 sur la propriété amount de l'entité **bank**
  - [x] Créer un login

- ### Back office
  - [x] Créer une route back office pour l'user connecté, attention seulement lui peut y acceder.
  - [x] Créer un formulaire pour ajouter une adresse
  - [x] Créer un formulaire pour modifier une adresse
  - [ ] Créer un bouton pour supprimer une adresse
  - [x] Créer un formulaire pour ajouter un amount à l'entity **Bank**
  - [x] Créer un formulaire pour ajouter une annonce
  - [x] Créer un bouton pour passer les annonces de isVisible à true ou false (toggle)

- ### Home 
  - [x] Afficher les annonces visibles sur la page home
    - [x] Quand je click sur une annonce j'accède au détail de celle-ci

- ### Annonces
  - [ ] En récupérant l'id d'une annonce j'affiche le detail ainsi que tous les commentaires. Je propose une formulaire qui permet de créer un commentaire si je suis connecté.
  - [X] L'annonce propose un bouton acheter si je suis connecté, sinon il propose un bouton se connecté.
  - [X] Lorsque je clique sur acheter, l'utilisateur se voit proposer une adresse de livraison. Si il n'en a pas ont le redirige pour en créer une.
  - [X] Quand tout est ok, l’acquisition s'enregistre en database