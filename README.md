# Initialisation 
## help 
`bin/behat --help`

##Profils définis
`cat behat.yml`


## Init
`bin/behat --init`

`find features/`


## Customisation pour Mink/Behatch
`vi features/bootstrap/FeatureContext.php`

<pre>
use Behat\MinkExtension\Context\MinkContext;
use Sanpi\Behatch\Context\BehatchContext;
</pre>

<pre>class FeatureContext extends MinkContext </pre>

<pre>   public function __construct(array $parameters)
    {
        $this->useContext('behatch', new BehatchContext($parameters));
    }
</pre>

# Langage
##Structure 
`bin/behat --story-syntax --lang=fr `

##Expressions
`bin/behat --profile chrome -dl --lang=fr `

D'autres expressions sont disponibles : 

* json
* xml
* shell
* ...

#Demos
## Demo 1 : login sur une page admin 
Page : <a hre='http://localhost/index.php'>http://localhost/index.php</a>
### Feature 
`cp utf8.feature features/admin.feature; vi features/admin.feature`

<pre>
# language: fr
Fonctionnalité: Login admin 

  Scénario: Login OK
    Quand je vais sur la page d'accueil
    Lorsque je remplis "login" avec "admin"
    Et je remplis "pass" avec "pass"
    Et je presse "Go"
    Alors j'attends 3 secondes de voir "Ajoutez un user"
    Alors je sauvegarde une capture d'écran dans "screenshot.png"
</pre>

###Launch avec rapport html 
`./bin/behat --profile chrome --format pretty,html --out ,report.html features/`


##Demo 2 : test fail 
###Définition feature
`vi features/admin.feature`

<pre>
  Scénario: Login KO
    Quand je vais sur la page d'accueil
    Lorsque je remplis "login" avec "admin"
    Et je presse "Go"
    Alors j'attends 3 secondes de voir "Password mancant"
</pre>

###Launch avec rapport html et option rerun
`./bin/behat --profile chrome --format pretty,html --out ,report.html features/ --rerun run.log`

###Ajout point d'arrêt et rerun

`vi features/admin.feature`

<pre>
    Alors je pose un point d'arrêt
</pre>

`./bin/behat --profile chrome --format pretty,html --out ,report.html features/ --rerun run.log` 

###Correction 
`vi features/admin.feature`
<pre>
    #Alors je pose un point d'arrêt
    Alors j'attends 3 secondes de voir "Password manquant"
</pre>

`./bin/behat --profile chrome --format pretty,html --out ,report.html features/ --rerun run.log` 

`rm run.log`

##Demo 3 : Définition d'une Step personelle et en utilisant le concept Meta Step -> "loggé admin" 
###Définition
`cp utf8.feature features/user.feature; vi features/user.feature`

<pre>
# language: fr
Fonctionnalité: Ajout d'utilisateur

  Scénario: Ajout OK
    Quand je suis connecté en admin
    Alors je pose un point d'arrêt
    Lorsque je remplis "name" avec "Seguillon"
    Et je remplis "email" avec "joel.seguillon@niji.fr"
    Et je remplis "twitter" avec "@Jseguillon"
    Et je presse "Go"
    Alors j'attends 3 secondes de voir "Jseguillon ajouté"
</pre>

###Launch dry-run et ajout snippet
`./bin/behat --profile chrome features/user.feature --dry-run` 

`./bin/behat --profile chrome features/user.feature  --dry-run --append-snippets`

`vi features/bootstrap/FeatureContext.php`

###Définition Méta-Step 
<pre>
    public function jeSuisConnecteEnAdmin()
    {
    ////
    //Meta Step 
    //
       return array(
            new Step\Given("je vais sur la page d'accueil"),
            new Step\When("je remplis \"login\" avec \"admin\""),
            new Step\When("je remplis \"pass\" avec \"pass\""),
            new Step\When("je presse \"Go\""),
            new Step\Then("j'attends 3 secondes de voir \"Ajoutez un user\""),
        );
</pre>

<pre>
use Behat\Behat\Context\Step; 
</pre>

###Launch et vérification 
`./bin/behat --profile chrome --format pretty,html --out ,report.html features/user.feature`


##Demo 4 : Tables exemples
###Définition 
`vi features/user.feature`

<pre>
  @massif
  Plan du scénario: Ajout massif
    Quand je suis connecté en admin
    Lorsque je remplis "name" avec "&lt;nom&gt;"
    Et je remplis "email" avec "&lt;email&gt;"
    Et je remplis "twitter" avec "&lttwitter&gt;"
    Et je presse "Go"
    Alors j'attends 3 secondes de voir "&lt;twitter&gt; ajouté"

    Exemples:
      | nom | email | twitter |
      | Seguillon | joel.seguillon at gmail.com | @Jseguillon |
      | Drubreuil | tony.dubreil@niji.fr | @tonydbrl |
      | Niji | contact@niji.fr | @Niji_Digital | 
</pre> 

###Launch et vérfication rapport
`./bin/behat --profile chrome --format pretty,html --out ,report.html features/user.feature --tags '@massif'`
<-voir report.html

En cas de besoin de debug : ajouter --expand

`./bin/behat --profile chrome --format pretty,html --out ,report.html features/user.feature --tags '@massif' --expand`

###Précision
Notons également l'utilisation du tag : @massif. Combinaisons permises : 

* `@tag1&&@tag2`
* `@tag1 ~tag3`
 
 
## Sortie Junit 
###Launch avec sortie junit
`./bin/behat --profile chrome --format junit,pretty,html --out junit,,report.html features/`

###Examen sortie
`find junit/ -type f -print -exec  cat {} \; `

#Aller plus loin : intégration avec un robot 
##Préparation features et bootstrap
`rm -Rf features/; mkdir features/; cp -R tapster.features/* features/`

##Launch Robot et launch appium 
`node server.js -c testcalibration.json`

`appium --udid fee71390b435ca62890c213a474017333763ed35 --robot-address 0.0.0.0 --robot-port 4242 --app TestApp.app --pre-launch -g DEBUG`


##Launch test
`./bin/behat --profile default features/Tapster.feature`

##Vérification log 
<pre>
debug: Request received with params: {"using":"name","value":"SensorSwitch"}

info: Pushing command to appium work queue: "au.getElementByName('SensorSwitch')"
debug: Sending command to instruments: au.getElementByName('SensorSwitch')

</pre> 

==> Le robot retrouve bien le bouton tout seul :) 
