# Makefile à la racine de ton projet Symfony

# Définition de variables (optionnel mais recommandé pour la propreté)
# Utiliser 'symfony' comme commande de base peut être utile
SYMFONY_CONSOLE = symfony console
DOCKER_COMPOSE = docker compose # Ou 'docker-compose' si tu n'as pas la version V2

# -----------------------------------------------------------------------------
# Cibles principales
# -----------------------------------------------------------------------------

.PHONY: help install start stop status build cache git

help:
	@echo "Utilisation : make [cible]"
	@echo ""
	@echo "Cibles disponibles :"
	@echo "  help           - Affiche cette aide."
	@echo "  install        - Installe les dépendances Composer."
	@echo "  start          - Démarre le serveur Symfony et/ou les services Docker."
	@echo "  stop           - Arrête le serveur Symfony et/ou les services Docker."
	@echo "  status         - Affiche le statut des services (Symfony, Docker)."
	@echo "  build          - Construit les assets frontend (Webpack Encore)."
	@echo "  cc         	- Vide le cache Symfony."
	@echo "  cc-hard        - Vide le cache Symfony et redémarre le serveur."
	@echo "  git            - Affiche le statut Git et pousse les changements."
	@echo ""
	@echo "  db-create      - Crée la base de données (si elle n'existe pas)."
	@echo "  db-migrate     - Exécute les migrations Doctrine."
	@echo "  db-fixtures    - Charge les fixtures Doctrine."
	@echo "  db-reset       - Supprime, recrée, migre et charge les fixtures (ATTENTION : efface les données !)."
	@echo ""
	@echo "  user-make      - Génère une nouvelle entité User."
	@echo "  security-login - Génère le formulaire de connexion Symfony."
	@echo ""
	@echo "  test           - Exécute les tests PHPUnit."
	@echo "  lint           - Exécute les linters (PHP CS Fixer, etc.)."
	@echo "  cs-fix         - Corrige les problèmes de style PHP."
	@echo "  up             - Alias pour 'start'."
	@echo "  down           - Alias pour 'stop'."
	@echo "  ps             - Alias pour 'status'."
	@echo " ts-watch		- Lance le compilateur ts en mode watch"


# -----------------------------------------------------------------------------
# Commandes générales du projet
# -----------------------------------------------------------------------------

install:
	@echo "--> Installation des dépendances Composer..."
	composer install

start:
	@echo "--> Démarrage du serveur Symfony..."
	symfony server:start -d # -d pour démarrer en arrière-plan
	@echo "--> Démarrage des services Docker (si docker-compose.yml existe)..."
	-$(DOCKER_COMPOSE) up -d --build || true # Le '-' permet de ne pas échouer si docker-compose.yml n'existe pas
	@echo "Votre serveur Symfony devrait être accessible à l'adresse indiquée par 'symfony server:start'"

stop:
	@echo "--> Arrêt du serveur Symfony..."
	symfony server:stop
	@echo "--> Arrêt des services Docker..."
	-$(DOCKER_COMPOSE) down || true

status:
	@echo "--> Statut du serveur Symfony :"
	symfony server:status
	@echo "--> Statut des services Docker :"
	-$(DOCKER_COMPOSE) ps || true

build:
	@echo "--> Construction des assets frontend..."
	npm run build

cc:
	@echo "--> Vidage du cache Symfony..."
	$(SYMFONY_CONSOLE) cache:clear

cc-hard:
	@echo "--> Vidage du cache Symfony..."
	$(SYMFONY_CONSOLE) cache:clear

git:
	@echo "--> Statut Git :"
	git status
	@echo ""
	@echo "--> Commitez et poussez vos changements : git add . && git commit -m '...' && git push"

# -----------------------------------------------------------------------------
# Commandes Doctrine
# -----------------------------------------------------------------------------

db-create:
	@echo "--> Création de la base de données..."
	$(SYMFONY_CONSOLE) doctrine:database:create

db-migrate:
	@echo "--> Exécution des migrations Doctrine..."
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction # --no-interaction pour éviter la confirmation

db-fixtures:
	@echo "--> Chargement des fixtures Doctrine..."
	$(SYMFONY_CONSOLE) doctrine:fixtures:load --append # --append pour ajouter, --purge-and-load pour tout effacer avant

db-reset:
	@echo "--> ATTENTION : Suppression et recréation de la base de données, exécution des migrations et chargement des fixtures !"
	@echo "--> Ceci effacera TOUTES les données existantes de la base !"
	$(SYMFONY_CONSOLE) doctrine:database:drop --force --if-exists
	$(SYMFONY_CONSOLE) doctrine:database:create
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction

# -----------------------------------------------------------------------------
# Commandes MakerBundle
# -----------------------------------------------------------------------------

user-make:
	@echo "--> Génération d'une nouvelle entité User..."
	$(SYMFONY_CONSOLE) make:user

security-login:
	@echo "--> Génération du formulaire de connexion Symfony..."
	$(SYMFONY_CONSOLE) make:security:form-login

# -----------------------------------------------------------------------------
# Commandes de qualité de code et tests
# -----------------------------------------------------------------------------

test:
	@echo "--> Exécution des tests PHPUnit..."
	vendor/bin/phpunit

lint:
	@echo "--> Exécution du linter PHP-CS-Fixer..."
	vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix:
	@echo "--> Correction des problèmes de style PHP avec PHP-CS-Fixer..."
	vendor/bin/php-cs-fixer fix

# -----------------------------------------------------------------------------
# Alias (simplifie les commandes courantes)
# -----------------------------------------------------------------------------

up: start
down: stop
ps: status

# -----------------------------------------------------------------------------
# Commandes typescript
# -----------------------------------------------------------------------------

ts-one:
	@echo "Lancement du compilateur ts"
	npx tsc

ts-watch:
	@echo "Lancement du compilateur ts en mode watch"
	npx tsc --watch

stan:
	@echo "launch php-stan"
	vendor/bin/phpstan analyse > last_php_stan.rapport

cs-fixer:
	@echo "launch php-cs-fixer"
	vendor/bin/php-cs-fixer fix
