#!/bin/bash

set -e

BRANCH_SOURCE="production"
BRANCH_TARGET="master"
MODEL_NAME="llama3"  # ou autre (mistral, etc.)
OLLAMA_API="http://localhost:11434/api/generate"
TEMP_DIR=".release_temp"

mkdir -p $TEMP_DIR

# Récupération des SHAs des commits entre les branches
COMMITS=$(git log origin/$BRANCH_TARGET..origin/$BRANCH_SOURCE --pretty=format:"%H")

if [ -z "$COMMITS" ]; then
  echo "Aucun commit entre $BRANCH_TARGET et $BRANCH_SOURCE"
  exit 1
fi

echo "Commits trouvés :"
echo "$COMMITS"

PR_LIST=()

# Récupération des PR associés à chaque commit
for COMMIT in $COMMITS; do
  PR_JSON=$(gh pr list --search "$COMMIT" --state merged --json title,number,body,url,author 2>/dev/null)
  PRS=$(echo "$PR_JSON" | jq -r '.[] | "\n### PR #\(.number): \(.title)\n\(.body)\nAuteur: \(.author.login)\nURL: \(.url)"')
  PR_LIST+=("$PRS")
done

PR_SUMMARY=$(printf "%s\n" "${PR_LIST[@]}")

# 🧠 Prompt pour Ollama
cat > $TEMP_DIR/prompt.txt <<EOF
Tu es un assistant technique. À partir des informations de Pull Requests suivantes, génère une note de version professionnelle, claire et concise à destination d'une équipe produit ou client.

${PR_SUMMARY}

Le format attendu est :
- une **intro** d’une phrase
- une **liste à puces** par fonctionnalité ou correction
- pas de détails techniques inutiles
- texte en **français professionnel**

Commence :
EOF

echo "🔁 Génération via Ollama..."
RELEASE_NOTE=$(curl -s -X POST "$OLLAMA_API" \
  -H "Content-Type: application/json" \
  -d "{
    \"model\": \"$MODEL_NAME\",
    \"prompt\": \"$(cat $TEMP_DIR/prompt.txt | sed 's/"/\\"/g')\",
    \"stream\": false
  }" | jq -r '.response')

echo -e "\n📝 Note de version générée :\n$RELEASE_NOTE"
