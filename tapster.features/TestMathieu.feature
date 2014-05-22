# language: fr
Fonctionnalité: Wikipedia

  @javascript
  Scénario: Test
  	Quand je vais sur la page d'accueil
  	Alors je remplis "searchInput" avec "Selenium (informatique)"
  	Alors je pose un point d'arrêt
  	Alors je presse "searchButton"
  	Alors je pose un point d'arrêt