@echo off
setlocal enabledelayedexpansion

:: Configurer les variables d'environnement nécessaires
set "OLLAMA_SERVER=http://86.217.43.98:11434"

:: Récupération des messages de commit de la PR
for /f "delims=" %%A in ('gh pr view --json commits ^| jq -r ".commits[].messageHeadline"') do (
    set "commit_messages=!commit_messages!%%A\n"
)

:: Affiche les commits pour debug
echo Commits :
echo !commit_messages!

:: Génération du résumé via Ollama
:: Préparer le JSON (on remplace les " par \" pour JSON correct)
set "PROMPT=Voici la liste des messages de commit pour une pull request GitHub : !commit_messages! Génère un résumé clair en français, en quelques lignes, de ce que cette PR contient."

:: On écrit le JSON dans un fichier temporaire
> body.json echo {
>> body.json echo   "model": "llama3",
>> body.json echo   "prompt": "!PROMPT!",
>> body.json echo   "stream": false
>> body.json echo }

:: Appel à l’API
curl -s %OLLAMA_SERVER%/api/generate ^
  -H "Content-Type: application/json" ^
  -d @body.json > response.json

:: Extraire le résumé avec jq
for /f "delims=" %%A in ('jq -r ".response" response.json') do (
    set "summary=%%A"
)

:: Affiche le résumé pour debug
echo.
echo Résumé généré :
echo !summary!

:: Mise à jour de la PR
gh pr edit --body "!summary!"

:: Nettoyage
del body.json
del response.json

endlocal
pause
