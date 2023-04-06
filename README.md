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
  - Créer un register ( par défaut mettre un "ROLE_USER") au user
    - Au register l'user à un montant de 100 sur la propriété amount de l'entité **bank**
  - Créer un login

- ### Back office
  - Créer une route back office pour l'user connecté, attention seulement lui peut y acceder.
  - Créer un formulaire pour ajouter une address
  - Créer un formulaire pour modifier une address
  - Créer un bouton pour supprimer une address
  - Créer un formulaire pour ajouter un amount à l'entity **Bank**
  - Créer un formulaire pour ajouter une annonce
  - Créer un bouton pour passer les annonces de isVisible à true ou false (toggle)

- ### Home 
  - Afficher les annonces visibles sur la page home
    - Quand je click sur une annonce j'accède au détail de celle-ci

- ### Annonces
  - En récupérant l'id d'une annonce j'affiche le detail ainsi que tous les commentaires. Je propose une formulaire qui permet de créer un commentaire si je suis connecté.
  - L'annonce propose un bouton acheter si je suis connecté, sinon il propose un bouton se connecté.
  - Lorsque je clique sur acheter, l'utilisateur se voit proposer une address de livraison. Si il en à pas ont le redirige pour en créer une.
  - Quand tout est ok, l’acquisition s'enregistre en database