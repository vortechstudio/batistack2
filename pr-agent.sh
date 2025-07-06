#!/bin/bash

# Configuration
PR_NUMBER="$1"                  # Numéro de la PR (en argument)
OLLAMA_MODEL="llama3"           # Modèle Ollama à utiliser
OLLAMA_URL="http://localhost:11434/api/generate"

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
PROMPT="Voici une liste de commits d'une Pull Request : [$COMMITS], Génère une description claire, professionnelle, concise et orientée utilisateur de cette PR. Écris en français. Format Markdown. Sans Résonnement"
echo $PROMPT

# 📤 Envoi à Ollama
echo "🧠 Envoi des commits à Ollama ($OLLAMA_MODEL)..."
DESCRIPTION=$(curl -s -X POST "$OLLAMA_URL" \
  -H "Content-Type: application/json" \
  -d '{
        "model": "'"$OLLAMA_MODEL"'",
        "prompt": "'"$(echo $PROMPT | sed 's/"/\\"/g')"'",
        "stream": false
      }' | jq -r '.response')

# 📝 Affichage et confirmation
echo -e "\n📝 Nouvelle description générée :\n---------------------------------"
echo "$DESCRIPTION"
echo "---------------------------------"

read -p "Souhaitez-vous mettre à jour la description de la PR ? (o/n) : " CONFIRM
if [[ "$CONFIRM" != "o" ]]; then
    echo "❌ Opération annulée."
    exit 0
fi

# 🚀 Mise à jour de la PR sur GitHub
echo "🚀 Mise à jour de la PR sur GitHub..."
gh pr edit "$PR_NUMBER" --body "$DESCRIPTION"

echo "✅ PR #$PR_NUMBER mise à jour avec succès."
