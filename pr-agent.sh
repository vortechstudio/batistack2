#!/bin/bash

# Configuration
PR_NUMBER="$1"                  # Numéro de la PR (en argument)
OLLAMA_MODEL="ai/llama3.1"           # Modèle Ollama à utiliser
OLLAMA_URL="http://localhost:12434/engines/llama.cpp/v1/chat/completions"

# Vérification des dépendances
for cmd in git curl jq gh; do
    if ! command -v $cmd &> /dev/null; then
        echo "❌ Commande manquante : $cmd"
        exit 1
    fi
done

if [ -z "$PR_NUMBER" ]; then
    echo "❌ Utilisation : $0 <numero_pr>"
    exit 1
fi

# 🔄 Récupération des commits
echo "📥 Récupération des commits de la PR #$PR_NUMBER..."
COMMITS=$(gh pr view "$PR_NUMBER" --json commits --jq '.commits[].messageHeadline')

if [ -z "$COMMITS" ]; then
    echo "❌ Aucun commit trouvé pour la PR #$PR_NUMBER"
    exit 1
fi

# 🧠 Préparation prompt pour Ollama
PROMPT="Voici une liste de commits d'une Pull Request : [$COMMITS], Génère une description claire, professionnelle, concise et orientée utilisateur de cette PR. Écris en français. Format Markdown. Sans Résonnement. Seul les commit (feat, fix, release, breaking) doivent être pris en compte."
echo $PROMPT
